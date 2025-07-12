Cara menjalankan
1. Migrasikan uas.sql
2. Jalankan server dengan command php -S localhost:8000
3. Untuk melakukan login, gunakan:
{
    username: "raja_iblis",
    password: "admin123"
}
pada path: /api/auth/login.
4. Untuk mendapatkan informasi admin, gunakan path: /api/auth/me.
5. Tiap path api yang akan digunakan pada data mahasiswa berikut:
- /api/students untuk mengambil seluruh data mahasiswa
- /api/students/{nim} untuk mengambil satu data mahasiswa
- /api/students/create untuk membuat satu data mahasiswa
- /api/students/update/{nim} untuk membuat mengubah satu data mahasiswa
- /api/students/delete/{nim} untuk membuat menghapus satu data mahasiswa
6. Token berlaku selama 1 jam.