# Product Requirement Document (PRD)
## Sistem Manajemen Inventaris Alat Pelanggan (Telco Lifecycle Tracker)

| Metadata | Informasi |
| :--- | :--- |
| **Versi** | 1.1 (Updated Final) |
| **Tanggal** | 26 Mei 2026 |
| **Arsitektur Sistem** | Full Web-Based (Laravel + Vanilla CSS + JQuery) |
| **Status** | Ready for Development |

> [!NOTE]
> **Teknologi Utama:** Sistem ini akan dibangun sepenuhnya menggunakan framework **Laravel** untuk backend dan templating Blade, **Vanilla CSS (CSS Murni)** untuk antarmuka pengguna, serta **jQuery** untuk penanganan interaktivitas sisi klien (terutama visualisasi akordeon responsif pada tampilan seluler teknisi). Tailind CSS ditiadakan.

---

## 1. Ringkasan Eksekutif & Batasan Sistem

### 1.1 Tujuan Produk
Sistem ini bertindak sebagai **Data Hub & Early Warning System** terpusat untuk memantau siklus hidup (*lifecycle*) perangkat Modem dan STB yang digunakan oleh pelanggan melalui perantara teknisi lapangan. 

Fokus utama sistem:
* **Transparansi data audit** (Siapa teknisinya, Apa barangnya, dan Siapa pelanggannya).
* **Akurasi status inventaris**.
* **Validasi fisik** melalui foto bukti.
* **Kemudahan navigasi** teknisi di lapangan.

### 1.2 Batasan Sistem (Out of Scope)
> [!WARNING]
> **Bukan Sistem Eksekutor WO:**  
> Sistem tidak menerbitkan atau mengelola tiket Work Order (WO) baru. Pembuatan WO tetap dilakukan secara manual oleh Admin di aplikasi pusat eksternal perusahaan.

> [!WARNING]
> **Bukan Sistem Input Gudang Massal:**  
> Tidak ada modul untuk menginput stok barang baru dari pabrikan secara massal di aplikasi ini. Data barang bergerak secara organik dari pecahan 3 alur utama yang tersedia.

---

## 2. Manajemen Akses & Peran Pengguna (RBAC)

### 2.1 Super Admin & Admin (Desktop Web View)
* **Manajemen Data:** Mengelola master data pelanggan (termasuk nomor telepon) dan pool perangkat.
* **Persetujuan (Approval):** Melakukan persetujuan (Approval/ACC) pada alur Pengambilan, Pengembalian, dan Dismantling yang semuanya bermuara di tabel transaksi.
* **Bypass Input:** Memiliki hak Bypass Input data pelanggan baru jika terjadi ketidaksinkronan data dari pusat.
* **Bulk Import:** Melakukan Bulk Import data pelanggan via Excel beserta resolusi konflik datanya secara massal.
* **Dashboard Monitoring:** Memantau Dashboard Early Warning System (Peringatan Umur Perangkat pada Tabel Barang).

### 2.2 Teknisi (Mobile-First Responsive Web View)
* **Request Pengambilan Barang:** Melakukan request pengambilan barang berdasarkan ID Pelanggan.
* **Input Pengembalian Barang (Rusak):** Melakukan pengembalian barang rusak dari rumah pelanggan ke kantor.
* **Input Dismantling (Pembongkaran):** Melakukan pembongkaran perangkat bagi pelanggan putus layanan.
* **Bypass Input Pelanggan (On-The-Fly):** Melakukan input data pelanggan secara darurat langsung dari form teknisi jika ID Pelanggan tidak ditemukan.
* **Konfirmasi:** Melakukan konfirmasi pengambilan fisik barang di gudang.
* **Navigasi & Informasi:** Mengakses data detail pelanggan, nomor telepon, dan Navigasi Google Maps (Hanya aktif jika status barang sudah `In Hand`).
* **Input Lapangan:** Melakukan input data lapangan (Upload Foto Bukti) untuk alur Pemasangan, Pengembalian (Rusak), dan Dismantling.

---

## 3. Spesifikasi Fungsional & Alur Kerja Core

### 3.1 Alur 1: Pengambilan & Pemasangan Barang (Deployment)
1. **Request:** Teknisi menginput Request Barang dengan memasukkan `id_pelanggan`.
2. **Approval Awal:** Admin menerima notifikasi request, memeriksa ketersediaan fisik, lalu menginput `serial_number` (SN), memilih `jenis_merek` (contoh: STB Huawei), dan mengisi `tipe_perangkat` (contoh: huawei790) yang akan diserahkan. Admin klik **Approve**. (Status transaksi: `Ready to Pick Up`).
3. **Pengambilan:** Teknisi mengambil fisik barang di gudang dan mengklik tombol **Konfirmasi Pengambilan**. Status transaksi berubah menjadi `In Hand`.
4. **Trigger Navigasi:** Begitu status menjadi `In Hand`, aplikasi teknisi membuka detail data nomor telepon pelanggan. Tombol **"Buka Google Maps"** aktif jika data koordinat `latitude`,`longitude` sudah tersedia. Jika koordinat kosong (belum terisi dari impor awal Excel), tombol Maps dinonaktifkan, dan teknisi wajib mengambil koordinat lokasi (via input manual atau tombol auto-detect dengan Geolocation API) sebelum memasang perangkat.
5. **Pemasangan:** Teknisi memasang alat di lokasi, mengisi koordinat `latitude`/`longitude` (jika belum ada), mengambil **Foto Bukti Terpasang**, lalu klik **Selesai**.
6. **Otomatisasi Sistem:** Status transaksi berubah menjadi `Approved_by_Admin`. Secara paralel, status perangkat di tabel master barang (`devices`) otomatis berubah menjadi `Terpasang`, dan field `tanggal_pasang_awal` mencatat waktu eksekusi.

### 3.2 Alur 2: Pengembalian Barang (Return / Rusak)
1. **Inisiasi Return:** Teknisi membuka form Pengembalian, memilih `id_pelanggan` dan memilih `serial_number` aktif yang sedang terpasang di rumah pelanggan tersebut.
2. **Input Bukti:** Teknisi wajib mengisi teks `alasan_rusak` dan mengunggah **Foto Fisik Perangkat**. Status transaksi masuk antrean `Pending`.
3. **Verifikasi Admin:** Admin menerima fisik barang di kantor, memverifikasi kecocokan SN, lalu klik **ACC Pengembalian**.
4. **Otomatisasi Sistem:** Status transaksi berubah menjadi `Approved_by_Admin`. Secara paralel:
   * Hubungan perangkat dengan pelanggan terputus.
   * Status perangkat di tabel master barang (`devices`) otomatis berubah menjadi `Rusak`.
   * Kolom `alasan_rusak` di tabel barang akan terisi.

### 3.3 Alur 3: Dismantling (Pelanggan Putus Layanan)
1. **Inisiasi Dismantling:** Teknisi membuka form Dismantling dan memilih `id_pelanggan`. Sistem otomatis memunculkan seluruh list SN yang aktif di rumah tersebut dalam bentuk checkbox, **serta ditambahkan opsi 'Lainnya' (Other) untuk menginput manual Serial Number jika perangkat tidak terdaftar di database**.
2. **Input Bukti:** Teknisi mencentang perangkat yang dicabut (dan/atau mengetikkan SN manual pada opsi 'Lainnya') dan mengunggah **Foto Bukti Pembongkaran**. Status transaksi masuk antrean `Pending`.
3. **Verifikasi Admin:** Admin menerima perangkat di kantor, mencocokkan fisik, lalu klik **ACC Dismantling**.
4. **Otomatisasi Sistem:** Status transaksi berubah menjadi `Approved_by_Admin`. Secara paralel:
   * Status pelanggan berubah menjadi `Terminated`.
   * Status kondisi perangkat di tabel master barang (`devices`) otomatis diperbarui menjadi `Dismantling`.

---

## 4. Fitur Mitigasi & Penanganan Kontingensi Data

### 4.1 Fitur Bypass Input Pelanggan (On-The-Fly Input)
* Jika **Teknisi**, **Admin**, atau **Super Admin** mencari `id_pelanggan` pada modul Request Pengambilan, Pengembalian, atau Dismantling, dan sistem merespons *"Data Tidak Ditemukan"*, maka sistem akan mengaktifkan **Pop-up Form Input Darurat (Bypass)**.
* User (Teknisi/Admin/Super Admin) dapat langsung menginput secara manual: `id_pelanggan`, `nama_pelanggan`, `no_telepon`, `alamat_pemasangan`, `latitude` (bisa diisi manual atau dideteksi otomatis via GPS/Geolocation API), dan `longitude`.
* Setelah disimpan, data tersebut otomatis masuk ke tabel master pelanggan (`customers`), sehingga teknisi tidak terhambat dan langsung bisa mendapatkan akses peta navigasi serta melanjutkan penginputan form.

### 4.2 Modul Bulk Import Excel & Resolusi Konflik
* **Kolom File Excel:** Data yang diimpor meliputi `id_pelanggan`, `nama_pelanggan`, `no_telepon`, dan `alamat_pemasangan` (tanpa koordinat).
* **Tahap 1 (Pre-Validation):** Sistem membaca file di memori sementara, memvalidasi format data dasar, dan mendeteksi duplikasi ID Pelanggan terhadap database.
* **Tahap 2 (Pop-up Preview):** Menampilkan ringkasan total data sukses dan total data konflik (duplikat). Sistem menampilkan **Tabel Komparasi Side-by-Side** yang menyoroti perbedaan teks antara data lama di database dan data baru di Excel menggunakan warna sorotan. Di bawah tabel, detail data yang konflik akan didaftarkan secara rinci.
* **Tahap 3 (Strategi Penanganan):** Admin memilih salah satu opsi resolusi secara **massal** untuk seluruh data yang konflik sebelum eksekusi:
  * **Skip (Lewati):** Mengabaikan seluruh data Excel yang duplikat, mempertahankan data lama di database.
  * **Overwrite (Timpa):** Memperbarui seluruh data lama di database dengan data baru dari Excel.
  * **Keep Both (Simpan Semua):** Tetap memasukkan seluruh data Excel yang duplikat dengan memodifikasi ID Pelanggan menggunakan akhiran otomatis (Contoh: `PLG001_DUP1`).

---

## 5. Fitur Dashboard Monitoring Perangkat (Early Warning System)

Halaman ini merupakan view utama pada **Menu Barang** (Tabel Master Perangkat) di sisi Admin Desktop yang berfungsi sebagai indikator visual kualitas perangkat di lapangan:

* **Logika Pengurutan:** Sistem secara otomatis menempatkan perangkat dengan masa pakai terlama (`tanggal_pasang_awal`) di baris paling atas (*Top Row*).
* **Visualisasi Peringatan:** Jika masa pakai perangkat telah melewati ambang batas **3 Tahun (36 Bulan)**, maka baris (*row*) tabel tersebut otomatis berubah warna menjadi **Kuning Soft/Pastel**.
* **Komponen Data yang Ditampilkan:**
  * Serial Number
  * Jenis & Merek Perangkat (`jenis_merek`)
  * Tipe Spesifik Perangkat (`tipe_perangkat`)
  * Status Kondisi
  * Detail Pelanggan (ID, Nama, & No Telp tempat barang terpasang)
  * Detail Teknisi Terakhir
  * Tanggal Pasang
  * Durasi Pakai
  * Catatan Alasan Rusak

---

## 6. Spesifikasi Database Efektif (Database Schema Specs)

Arsitektur database dirancang sangat efektif: 3 Alur kerja disatukan dalam tabel transaksi (`assignments`) dipisahkan oleh kolom `ENUM`, sedangkan Tabel Barang (`devices`) berdiri sendiri sebagai pusat pencatatan kondisi alat.

### 6.1 Tabel Pelanggan (`customers`)
* `id_pelanggan` (Varchar, Primary Key, Unique)
* `nama_pelanggan` (Varchar)
* `no_telepon` (Varchar)
* `alamat_pemasangan` (Text)
* `latitude` (Decimal, 10, 8, Nullable) $\rightarrow$ *Kosong saat import Excel awal, diisi oleh teknisi saat pemasangan*
* `longitude` (Decimal, 11, 8, Nullable) $\rightarrow$ *Kosong saat import Excel awal, diisi oleh teknisi saat pemasangan*
* `status_langganan` (Enum: 'Active', 'Suspended', 'Terminated')

### 6.2 Tabel Master Barang / Pool Perangkat (`devices`)
* `serial_number` (Varchar, Primary Key, Unique)
* `jenis_merek` (Varchar/Enum) $\rightarrow$ *Gabungan Jenis & Merek, contoh: 'STB Huawei', 'STB ZTE', 'Modem ZTE', 'Modem Huawei'*
* `tipe_perangkat` (Varchar) $\rightarrow$ *Tipe spesifik hardware, contoh: 'huawei790'*
* `status_kondisi` (Enum: `'Terpasang'`, `'Rusak'`, `'Dismantling'`)
* `alasan_rusak` (Text, Nullable) $\rightarrow$ *Otomatis terisi jika alur transaksi = Pengembalian*
* `tanggal_pasang_awal` (Datetime, Nullable) $\rightarrow$ *Digunakan untuk menghitung umur pakai baris kuning*

### 6.3 Tabel Pengguna / Akun (`users`)
* `id_user` (Varchar, Primary Key)
* `nama_jelas` (Varchar) $\rightarrow$ *Untuk melacak nama nyata teknisi/admin/superadmin di log*
* `username` (Varchar, Unique)
* `password` (Varchar, Hashed)
* `role` (Enum: `'Super Admin'`, `'Admin'`, `'Teknisi'`)

### 6.4 Tabel Jantung Sistem / Log Transaksi 3 Alur (`assignments`)
* `id_transaksi` (BigInt, Primary Key, Auto Increment)
* `id_pelanggan` (Foreign Key $\rightarrow$ `customers.id_pelanggan`)
* `id_teknisi` (Foreign Key $\rightarrow$ `users.id_user`)
* `serial_number` (Foreign Key $\rightarrow$ `devices.serial_number`)
* `tipe_alur` (Enum: `'Pengambilan'`, `'Pengembalian'`, `'Dismantling'`) $\rightarrow$ *Pusat pemisah 3 alur kerja*
* `status_approval` (Enum: `'Pending'`, `'In_Hand'`, `'Approved_by_Admin'`, `'Rejected'`)
* `foto_bukti` (Varchar/Text - Path URL file gambar di storage)
* `created_at` & `updated_at` (Timestamp)

---

## 7. Aturan Estetika & UX Aplikasi (Soft Blue Theme)

* **Skema Warna:** Seluruh elemen tombol aksi utama (Call to Action) wajib menggunakan palet warna Soft Blue (`#2B6CB0`) untuk kenyamanan visual (*relax feeling*). **Warna hijau ditiadakan total.**
* **Desain Responsif Teknisi:** Aplikasi sisi teknisi wajib menggunakan komponen **Interactive Accordion Card** berbasis jQuery untuk menghemat ruang layar ponsel.

---

> [!IMPORTANT]
> Dokumen PRD Versi 1.1 ini sudah dikunci, bersifat mutlak, dan siap diserahkan kepada programmer Anda untuk langsung dibuatkan struktur database dan kodenya. Proses perancangan selesai!