@props(['title', 'date', 'location', 'price', 'image', 'href' => null])

@php
// Format Indonesian price
$formattedPrice = $price ? 'Rp ' . number_format($price, 0, ',', '.') : 'Harga tidak tersedia';

$formattedDate = $date
? \Carbon\Carbon::parse($date)->locale('id')->translatedFormat('d F Y, H:i')
: 'Tanggal tidak tersedia';

// Safe image URL: use external URL if provided, otherwise use asset (storage path)
$imageUrl = $image
? (filter_var($image, FILTER_VALIDATE_URL)
? $image
: asset('images/events/' . $image))
: asset('images/konser.jpeg');

@endphp

<a href="{{ $href ?? '#' }}" class="block">
    <article class="dinus-card">
        <div class="dinus-card__media">
            <img
                src="{{ $imageUrl }}"
                alt="{{ $title }}"
                class="dinus-card__img"
            >
        </div>

        <div class="dinus-card__body">
            <h2 class="dinus-card__title">
                {{ $title }}
            </h2>

            <p class="dinus-card__meta">
                {{ $formattedDate }}
            </p>

            <p class="dinus-card__meta">
                📍 {{ $location }}
            </p>

            <p class="dinus-card__price">
                {{ $formattedPrice }}
            </p>
        </div>
    </article>
</a>
