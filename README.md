# CRUD Inventaris — Tugas Rutin 8

Memenuhi 10 requirement pada gambar:
1. Database inventaris_db dengan 3 tabel + FK.
2. Minimal 5 seed per tabel.
3. PDO Singleton Pattern.
4. List produk dengan JOIN kategori dan supplier.
5. Create dengan dropdown kategori/supplier.
6. Edit dan delete dengan konfirmasi.
7. Prepared statements.
8. htmlspecialchars() untuk output.
9. Flash message + redirect.
10. UI rapi/responsive.

Bonus yang sudah dibuat: pencarian, pagination, dan transaction saat delete.

## Cara menjalankan XAMPP

1. Salin folder `CRUD_Inventaris_Tugas_Rutin_8` ke `C:\xampp\htdocs\`.
2. Buka XAMPP dan nyalakan Apache + MySQL.
3. Buka `http://localhost/phpmyadmin`.
4. Import file `database/inventaris_db.sql`.
5. Buka `http://localhost/CRUD_Inventaris_Tugas_Rutin_8/public/`.

Jika password MySQL root kamu bukan kosong, ubah di `config/Database.php`.
