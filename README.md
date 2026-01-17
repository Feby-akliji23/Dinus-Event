# DINUS EVENT

## Gambaran Umum

Dinus event merupakan simulasi sistem pemesanan tiket event berbasis web yang dikembangkan
menggunakan framework Laravel. Sistem ini memungkinkan admin untuk mengelola data event dan
kategori, serta memungkinkan user untuk memesan tiket tanpa melalui proses pembayaran. Setiap
pemesanan yang dilakukan akan langsung tercatat pada riwayat pembelian.

## Skema Aplikasi

## Pembeli
![Use Case Pembeli](images/usecase-pembeli.png)

## Admin
![Use Case Admin](images/usecase-admin.png)

### Struktur Database
![ERD](images/ERD.png)

**Kategori** berelasi one-to-many dengan **Event** , artinya satu kategori dapat memiliki banyak event, sementara setiap event hanya berada pada satu kategori (many-to-one dari Event ke Kategori). **User** juga memiliki relasi one-to-many dengan **Event** , karena satu user dapat membuat banyak event, tetapi satu event hanya dibuat oleh satu user. Selanjutnya, **Event** berelasi one-to-many dengan **Tiket** , sehingga satu event bisa menyediakan beberapa tipe tiket seperti premium atau reguler, sedangkan tiap tiket hanya terkait ke satu event. Pada proses transaksi, **User** berelasi one-to-many dengan **Order** karena satu user bisa melakukan banyak pemesanan, dan **Event** juga one-to-many dengan **Order** karena satu event bisa dipesan oleh banyak user. Hubungan antara **Order** dan **Tiket** secara konsep adalah **many-to-many** , karena satu order dapat berisi beberapa jenis tiket dan satu jenis tiket bisa muncul di banyak order; relasi ini diwujudkan melalui tabel penghubung **Detail Order** yang menyimpan rincian pembelian seperti jumlah dan subtotal.

## Konsep MVC

Pada proyek **ini** , pola arsitektur yang dipakai adalah **MVC (Model–View–Controller)**. alur kerjanya dimulai dari **Route → Controller → Model → View**. Ketika pengguna membuka halaman di browser, permintaan (request) itu pertama kali “masuk” melalui **routing** yang didefinisikan di file web.php. Di file ini, semua URL atau “pintu masuk” aplikasi dipetakan: mana yang termasuk halaman **user** , mana yang termasuk halaman **admin** , dan masing-masing route diarahkan ke controller yang sesuai.

Setelah route menentukan tujuan, request diteruskan ke **Controller** , yaitu bagian yang mengatur logika
aplikasi. 

Untuk sisi **admin**, controller dibagi berdasarkan fitur:

- CategoryController menangani **CRUD kategori** (tambah, lihat, ubah, hapus),
- Admin/EventController menangani pengelolaan **event** ,
- TiketController mengelola **tiket** (biasanya terkait event),
- HistoriesController menangani **riwayat pembelian versi admin** ,
- DashboardController menyiapkan data yang tampil di halaman dashboard admin, seperti
    ringkasan atau statistik sederhana.

Untuk sisi user,

- HomeController bertugas menampilkan halaman utama dengan daftar event dan kategori,
- User/EventController menampilkan **detail event** (termasuk tiket yang tersedia), dan
- OrderController mengelola proses **checkout** serta **riwayat pembelian** user.

Jadi, controller itu ibarat “pengatur jalannya proses”: dia menentukan data apa yang perlu diambil,
validasi apa yang harus dicek, lalu view mana yang harus ditampilkan.

Untuk mengambil dan mengelola data, controller memanggil **Model** , yaitu representasi tabel database
sekaligus tempat relasi antar data didefinisikan. Model yang dipakai ada Kategori, Event, Tiket, Order,
DetailOrder.

Misalnya, melalui model **Event** , controller bisa mengambil semua event (Event::all()), mengambil satu
event berdasarkan id (Event::find($id)), dan memanfaatkan relasi seperti

- $event->tikets untuk mengambil semua tiket milik event tersebut (relasi **hasMany** ),
- $event->kategori untuk mengetahui kategori event (relasi **belongsTo** ), dan
- $event->user untuk mengetahui siapa penyelenggara/pembuat event (relasi **belongsTo** ).

Pada model **Kategori** , ada relasi

- $kategori->events yang berarti satu kategori memiliki banyak event ( **hasMany** ).
- Pada model **Tiket** , relasi $tiket->event menunjukkan tiket itu milik event tertentu ( **belongsTo** ),
    dan
- $tiket->orders menunjukkan tiket tersebut bisa muncul di banyak order melalui tabel penghubung
    detail order ( **belongsToMany** ).

Lalu pada model **Order**,

- relasi $order->user menunjukkan siapa pembelinya,
- $order->event menunjukkan event yang dibeli,
- $order->tikets menunjukkan daftar tiket yang dibeli (many-to-many), dan
- $order->detailOrders menyimpan rincian item pembelian seperti jumlah dan subtotal per tiket 

Sedangkan model **DetailOrder** berperan sebagai penghubung transaksi, karena dari detail itulah kita bisa melihat order induknya ($detail->order) dan tiket yang dibeli ($detail->tiket).

Setelah data didapat dan disiapkan oleh controller, hasilnya dikirim ke **View** untuk dirender menjadi tampilan yang dilihat pengguna. Untuk halaman user, home.blade.php sebagai halaman utama, show.blade.php untuk detail event, dan halaman order untuk daftar/riwayat pembelian.

Untuk admin, ada dashboard.blade.php untuk ringkasan admin, index.blade.php untuk listing data kategori/event/history, dan beberapa komponen UI seperti event-card.blade.php, sidebar.blade.php, serta layout seperti app.blade.php, admin.blade.php agar tampilan konsisten.

Contoh alurnya bisa dijelaskan sederhana: ketika user membuka route /, route itu akan memanggil HomeController@index, artinya menjalankan fungsi index di dalam class HomeController. Fungsi ini mengambil data event dan kategori dari model, lalu mengirimnya ke view home.blade.php untuk ditampilkan. Ketika user membuka /events/{event}, request diarahkan ke User/EventController@show untuk mengambil detail event beserta relasi tiketnya, lalu dirender ke show.blade.php. Sementara untuk admin, route seperti /admin/events memanggil Admin/EventController@index untuk menampilkan daftar


event pada halaman index.blade.php. Dengan pola ini, tiap bagian punya tugas jelas: **route sebagai pengarah, controller sebagai pengatur logika, model sebagai pengelola data/relasi, dan view sebagai tampilan**.

## Menginstal dan menjalankan Laravel.

Untuk menginstal dan menjalankan Laravel, pertama **nyalakan Apache dan MySQL di XAMPP**. Setelah itu buat project Laravel baru dengan perintah composer create-project laravel/laravel ticketing_app, lalu masuk ke folder project menggunakan cd ticketing_app. Berikutnya lakukan **konfigurasi database** di file .env dengan mengisi DB_CONNECTION=mysql, DB_HOST=127.0.0.1, DB_PORT=3306, DB_DATABASE=ticketing_app, DB_USERNAME=root, dan DB_PASSWORD= (kosong). Setelah database **ticketing_app** dibuat di MySQL, jalankan php artisan migrate untuk membuat
tabel-tabelnya. Terakhir, jalankan server development dengan php artisan serve dan akses aplikasi melalui [http://127.0.0.1:8000.](http://127.0.0.1:8000.)

## Membuat, menjalankan dan memahami migration database dan fungsi model

**migration** digunakan untuk mendefinisikan struktur tabel dan relasi secara rapi, lalu **menjalankannya dengan php artisan migrate** supaya seluruh tabel benar-benar terbentuk di database sesuai rancangan.

Migration yang dibuat di projek ini mencakup

- tabel **users** lengkap dengan kebutuhan autentikasi (password reset dan sessions),
- tabel **kategoris** untuk menyimpan nama kategori yang unik,
- tabel **events** yang terhubung ke users dan kategoris melalui foreign key,
- tabel **tikets** yang terhubung ke events untuk menyimpan tipe tiket, harga, dan stok,
- tabel **orders** yang mencatat transaksi user terhadap event, dan
- tabel **detail_orders** sebagai tabel pivot yang menghubungkan order dan tiket sekaligus menyimpan detail jumlah serta subtotal harga.

Dengan migration dipastikan database konsisten, bisa diulang dari awal, dan relasinya otomatis terjaga lewat foreign key dan cascade.

Setelah database terbentuk, **model** digunakan sebagai “jembatan” antara aplikasi dan database. Model dipakai untuk membaca dan menulis data tabel, sekaligus mendefinisikan relasi agar pengambilan data lebih mudah.

Misalnya

- model **Kategori** menyediakan relasi bahwa satu kategori memiliki banyak event,
- model **Event** terhubung ke user dan kategori serta memiliki banyak tiket,
- model **Tiket** terhubung ke event dan dapat muncul di banyak order melalui tabel pivot detail_orders,
- model **Order** terhubung ke user dan event serta menyimpan daftar tiket yang dibeli beserta detailnya, dan
- model **DetailOrder** menyimpan rincian item transaksi per tiket dan menghubungkan kembali ke order dan tiket.

## Seeder

Pada proyek ini, **seeder** digunakan untuk mengisi **data awal atau data contoh** agar aplikasi bisa langsung dicoba tanpa harus memasukkan data manual dari awal.

Tujuannya supaya saat pertama kali aplikasi dijalankan, halaman seperti home dan dashboard tidak kosong, proses testing UI jadi lebih mudah karena data event, tiket, dan order sudah tersedia, serta relasi antar data tetap rapi dan konsisten, misalnya event sudah memiliki kategori, tiket terhubung ke event, dan
order memiliki detail pembelian.

Seeder yang disiapkan

- **UserSeeder** untuk membuat akun admin dan user biasa sehingga pengujian login dan hak akses bisa dilakukan,
- **CategorySeeder** untuk mengisi kategori awal seperti Konser, Seminar, dan Workshop agar fitur filter di halaman home berfungsi,
- **EventSeeder** untuk menambahkan beberapa event contoh lengkap dengan judul, deskripsi, tanggal, lokasi, dan gambar,
- **TicketSeeder** untuk menambahkan tiket reguler/premium dengan harga dan stok untuk event tertentu, serta
- **OrderSeeder** untuk membuat transaksi contoh beserta data detail_orders agar fitur riwayat pembelian bisa diuji.
- **DatabaseSeeder** untuk memanggil semua seeder tersebut secara berurutan.

Apabila hanya ingin mengisi data pada **tabel tertentu saja**

- php artisan db:seed --class=CategorySeeder
- php artisan db:seed --class=UserSeeder
- php artisan db:seed --class=EventSeeder
- php artisan db:seed --class=TicketSeeder
- php artisan db:seed --class=OrderSeeder

**Menjalankan Semua Seeder Sekaligus**

- php artisan db:seed

## Install Laravel Breeze untuk sistem autentikasi

sistem autentikasi dibangun menggunakan **Laravel Breeze** , yaitu starter kit autentikasi resmi dari Laravel yang menyediakan fitur dasar seperti **register, login, logout, dan reset password** , sehingga pengembangan tidak perlu membuat auth dari nol.

Breeze berperan sebagai pondasi akses pengguna untuk seluruh fitur, karena hanya pengguna yang sudah login yang dapat mengakses route tertentu, termasuk fitur event, order, dan halaman admin.

Komponen Breeze yang digunakan mencakup

- **routes autentikasi** di auth.php untuk endpoint login/register/logout,
- **controller autentikasi** di folder app/Http/Controllers/Auth/ untuk memproses login dan register,
- **view autentikasi** di resources/views/auth/ untuk tampilan form,
- serta tabel **users** dari migration sebagai tempat penyimpanan data user, role, dan password.

Alur kerjanya sederhana: pengguna membuka halaman /login, mengisi form, diproses oleh controller, lalu session disimpan; setelah berhasil login pengguna bisa mengakses route yang dilindungi middleware auth, dan saat logout session dihapus sehingga kembali ke halaman login. Untuk kebutuhan admin, route admin
dilindungi oleh middleware auth dan admin, sehingga Breeze memastikan pengguna sudah login terlebih dulu, kemudian middleware admin memastikan role-nya memang admin.

Instalasinya dilakukan dengan composer require laravel/breeze --dev lalu php artisan breeze:install, kemudian melengkapi aset frontend dengan menjalankan npm install dan npm run build

## Implementasi CRUD

Pada proyek ini, implementasi **CRUD** dibagi jelas antara fitur **admin** dan **user**. Untuk **CRUD Kategori** , admin menggunakan route resource di web.php yang mengarah ke CategoryController, sehingga proses seperti melihat daftar kategori, menambah, mengubah, dan menghapus data ditangani melalui method index, store, update, dan destroy. Seluruhnya ditampilkan pada index.blade.php dalam bentuk list kategori, dengan bantuan modal untuk aksi tambah, edit, dan hapus, lalu setelah submit controller memproses dan melakukan redirect kembali ke halaman list.

Untuk **CRUD Event** , admin juga memakai route resource yang terhubung ke EventController, lengkap dengan halaman list (index.blade.php), form tambah (create.blade.php), form edit (edit.blade.php), dan halaman detail (show.blade.php); alurnya pengguna admin mengisi form create atau edit, data diproses
lewat store atau update, lalu diarahkan kembali ke daftar event. Selanjutnya,

**CRUD Tiket** dilakukan admin dari dalam halaman detail event, di mana daftar tiket ditampilkan di show.blade.php dan aksi tambah/edit/hapus tiket dilakukan lewat modal yang memanggil TiketController (method store, update, destroy), kemudian sistem me-redirect kembali ke halaman detail event agar admin langsung melihat perubahan tiketnya.

Untuk sisi **user** , proses pemesanan dilakukan melalui fitur **Order/Checkout** yang dibuat melalui mekanisme fetch di show.blade.php (JavaScript checkout) yang mengirim data ke OrderController untuk menyimpan order beserta detail pembeliannya; setelah transaksi terbentuk, user dapat melihat **riwayat pembelian** pada index.blade.php dan membuka **detail order** pada show.blade.php.


