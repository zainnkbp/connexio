# System Change Log

## 2026-06-04 00:33:00
*   **Technician Dashboard (Request Deployment)**: Menambahkan fitur *Multi-Request* di mana teknisi dapat meminta banyak jenis perangkat (Modem, STB, dll) dan menetapkan jumlah (Quantity) untuk masing-masing jenis.
*   **Database**: Menambahkan kolom `keterangan` di tabel `assignments` untuk menyimpan detail request dari teknisi.
*   **Admin Dashboard**: Menambahkan kolom "Request Perangkat" di tabel *Pending Deployments* agar Admin mengetahui secara pasti jenis perangkat apa (dan berapa banyak) yang diminta oleh teknisi, sehingga Admin dapat menyesuaikan pemilihan *Serial Number* (SN) dari gudang.

## 2026-06-04 00:25:00
*   **Technician Dashboard (Return & Dismantle)**: Diubah agar bisa menginputkan lebih dari satu perangkat (baik perangkat terdaftar via *checkboxes*, maupun perangkat bypass / manual secara dinamis).
*   **AssignmentController**: Fungsi `storeReturnRequest` dan `storeDismantleRequest` diperbarui untuk memproses *array* perangkat yang dipilih/diinput dan membuat rekaman `Assignment` (serta `Device` baru bila belum terdaftar) untuk masing-masing perangkat.
*   **UI Dashboard Teknisi**: Diperbaiki isu konten terpotong pada layar form yang panjang dengan menambahkan `max-h-[90vh]` dan `overflow-y-auto` pada form (modal) Request, Return, dan Dismantle.
