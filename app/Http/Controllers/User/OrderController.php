<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DetailOrder;
use App\Models\Order;
use App\Models\PaymentStatus;
use App\Models\Promo;
use App\Models\Tiket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
  {
    $user = Auth::user() ?? \App\Models\User::first();
    $orders = Order::where('user_id', $user->id)
      ->with(['event', 'paymentType', 'paymentStatus', 'promo'])
      ->orderBy('created_at', 'desc')
      ->get();
    
    return view('orders.index', compact('orders'));
  }

  // show a specific order
  public function show(Order $order)
  {
    $order->load('detailOrders.tiket.ticketType', 'event', 'paymentType', 'paymentStatus', 'promo');
    return view('orders.show', compact('order'));
  }

  // store an order (AJAX POST)
  public function store(Request $request)
  {

    $data = $request->validate([
      'event_id' => 'required|exists:events,id',
      'payment_type_id' => 'required|exists:payment_types,id',
      'promo_id' => 'nullable|integer|exists:promos,id',
      'promo_code' => 'nullable|string',
      'items' => 'required|array|min:1',
      'items.*.tiket_id' => 'required|integer|exists:tikets,id',
      'items.*.jumlah' => 'required|integer|min:1',
    ]);

    $user = Auth::user();

    try {
      // transaction
      $order = DB::transaction(function () use ($data, $user) {
        $defaultStatusId = PaymentStatus::orderBy('id')->value('id');
        if (!$defaultStatusId) {
          throw new \Exception('Status pembayaran belum tersedia.');
        }
        $total = 0;
        $lineTotals = [];
        $ticketTypeTotals = [];
        // validate stock and calculate total
        foreach ($data['items'] as $it) {
          $t = Tiket::lockForUpdate()->findOrFail($it['tiket_id']);
          if ($t->stok < $it['jumlah']) {
            $typeName = $t->ticketType?->nama ?? 'tiket';
            throw new \Exception("Stok tidak cukup untuk tipe: {$typeName}");
          }
          $lineTotal = ($t->harga ?? 0) * $it['jumlah'];
          $total += $lineTotal;
          $lineTotals[$t->id] = ($lineTotals[$t->id] ?? 0) + $lineTotal;
          if ($t->ticket_type_id) {
            $ticketTypeTotals[$t->ticket_type_id] = ($ticketTypeTotals[$t->ticket_type_id] ?? 0) + $lineTotal;
          }
        }

        [$promo, $diskon] = $this->resolvePromo($data, $total, $lineTotals, $ticketTypeTotals);

        $order = Order::create([
          'user_id' => $user->id,
          'event_id' => $data['event_id'],
          'payment_type_id' => $data['payment_type_id'],
          'payment_status_id' => $defaultStatusId,
          'promo_id' => $promo?->id,
          'subtotal_harga' => $total,
          'diskon' => $diskon,
          'order_date' => Carbon::now(),
          'total_harga' => max(0, $total - $diskon),
        ]);

        foreach ($data['items'] as $it) {
          $t = Tiket::findOrFail($it['tiket_id']);
          $subtotal = ($t->harga ?? 0) * $it['jumlah'];
          DetailOrder::create([
            'order_id' => $order->id,
            'tiket_id' => $t->id,
            'jumlah' => $it['jumlah'],
            'subtotal_harga' => $subtotal,
          ]);

          // reduce stock
          $t->stok = max(0, $t->stok - $it['jumlah']);
          $t->save();
        }

        return $order;
      });

      
      session()->flash('success', 'Pesanan berhasil dibuat.');

      return response()->json(['ok' => true, 'order_id' => $order->id, 'redirect' => route('orders.index')]);
    } catch (\Exception $e) {
      return response()->json(['ok' => false, 'message' => $e->getMessage()], 422);
    }
  }

  private function resolvePromo(array $data, float $subtotal, array $lineTotals, array $ticketTypeTotals): array
  {
    $promo = null;
    $diskon = 0;

    if (!empty($data['promo_code'])) {
      $promo = Promo::where('kode', $data['promo_code'])->lockForUpdate()->first();
      if (!$promo) {
        throw new \Exception('Kode promo tidak ditemukan.');
      }
      $diskon = $this->calculateDiscount($promo, $subtotal, $lineTotals, $ticketTypeTotals, $data['event_id']);
      if ($diskon <= 0 || !$this->isPromoValid($promo, $subtotal, $lineTotals, $ticketTypeTotals, $data['event_id'])) {
        throw new \Exception('Promo tidak valid.');
      }
    } elseif (!empty($data['promo_id'])) {
      $promo = Promo::where('id', $data['promo_id'])->lockForUpdate()->first();
      if (!$promo) {
        throw new \Exception('Promo tidak ditemukan.');
      }
      $diskon = $this->calculateDiscount($promo, $subtotal, $lineTotals, $ticketTypeTotals, $data['event_id']);
      if ($diskon <= 0 || !$this->isPromoValid($promo, $subtotal, $lineTotals, $ticketTypeTotals, $data['event_id'])) {
        throw new \Exception('Promo tidak valid.');
      }
    } else {
      $promoCandidates = $this->getApplicablePromos($subtotal, $lineTotals, $ticketTypeTotals, $data['event_id']);
      foreach ($promoCandidates as $candidate) {
        $candidateDiscount = $this->calculateDiscount($candidate, $subtotal, $lineTotals, $ticketTypeTotals, $data['event_id']);
        if ($candidateDiscount > $diskon) {
          $diskon = $candidateDiscount;
          $promo = $candidate;
        }
      }
    }

    if ($promo && $promo->kuota !== null) {
      $updated = Promo::where('id', $promo->id)
        ->whereColumn('digunakan', '<', 'kuota')
        ->increment('digunakan');
      if ($updated === 0) {
        throw new \Exception('Kuota promo sudah habis.');
      }
    }

    return [$promo, $diskon];
  }

  private function getApplicablePromos(float $subtotal, array $lineTotals, array $ticketTypeTotals, int $eventId)
  {
    $ticketIds = array_keys($lineTotals);
    $ticketTypeIds = array_keys($ticketTypeTotals);

    return Promo::query()
      ->where('aktif', true)
      ->where(function ($q) {
        $q->whereNull('mulai')->orWhere('mulai', '<=', now());
      })
      ->where(function ($q) {
        $q->whereNull('akhir')->orWhere('akhir', '>=', now());
      })
      ->where(function ($q) use ($subtotal) {
        $q->whereNull('min_transaksi')->orWhere('min_transaksi', '<=', $subtotal);
      })
      ->where(function ($q) {
        $q->whereNull('kuota')->orWhereColumn('digunakan', '<', 'kuota');
      })
      ->where(function ($q) use ($eventId, $ticketIds, $ticketTypeIds) {
        $q->where('scope', 'all')
          ->orWhere(function ($q) use ($eventId) {
            $q->where('scope', 'event')->where('event_id', $eventId);
          });

        if (!empty($ticketTypeIds)) {
          $q->orWhere(function ($q) use ($ticketTypeIds) {
            $q->where('scope', 'ticket_type')->whereIn('ticket_type_id', $ticketTypeIds);
          });
        }

        if (!empty($ticketIds)) {
          $q->orWhere(function ($q) use ($ticketIds) {
            $q->where('scope', 'ticket')->whereIn('tiket_id', $ticketIds);
          });
        }
      })
      ->get();
  }

  private function isPromoValid(Promo $promo, float $subtotal, array $lineTotals, array $ticketTypeTotals, int $eventId): bool
  {
    if (!$promo->aktif) {
      return false;
    }

    if ($promo->mulai && now()->lt($promo->mulai)) {
      return false;
    }

    if ($promo->akhir && now()->gt($promo->akhir)) {
      return false;
    }

    if ($promo->min_transaksi && $subtotal < $promo->min_transaksi) {
      return false;
    }

    if ($promo->kuota !== null && $promo->digunakan >= $promo->kuota) {
      return false;
    }

    return $this->getBaseAmount($promo, $subtotal, $lineTotals, $ticketTypeTotals, $eventId) > 0;
  }

  private function calculateDiscount(Promo $promo, float $subtotal, array $lineTotals, array $ticketTypeTotals, int $eventId): float
  {
    $base = $this->getBaseAmount($promo, $subtotal, $lineTotals, $ticketTypeTotals, $eventId);
    if ($base <= 0) {
      return 0;
    }

    $discount = $promo->tipe === 'percent'
      ? ($base * ($promo->nilai / 100))
      : $promo->nilai;

    return min($discount, $base);
  }

  private function getBaseAmount(Promo $promo, float $subtotal, array $lineTotals, array $ticketTypeTotals, int $eventId): float
  {
    if ($promo->scope === 'all') {
      return $subtotal;
    }

    if ($promo->scope === 'event') {
      return $promo->event_id == $eventId ? $subtotal : 0;
    }

    if ($promo->scope === 'ticket_type') {
      return $ticketTypeTotals[$promo->ticket_type_id] ?? 0;
    }

    if ($promo->scope === 'ticket') {
      return $lineTotals[$promo->tiket_id] ?? 0;
    }

    return 0;
  }
}
