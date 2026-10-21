
```text
/----------------------\
| Mulai                |
\----------------------/
          |
          v
/----------------------\
| Petugas membuka      |
| SIMPUS-Mini          |
\----------------------/
          |
          v
/----------------------\
| Pilih menu           |
| Daftar Anggota       |
\----------------------/
          |
          v
/----------------------\
| Cari anggota dengan  |
| nama / nomor anggota |
\----------------------/
          |
          v
     /-------------------\
     | Anggota ditemukan?|
     \-------------------/
       | Ya          | Tidak
       v             v
/----------------\  /--------------------\
| Buka detail    |  | Tampilkan pesan    |
| anggota        |  | anggota tidak ada  |
\----------------/  \--------------------/
       |                     |
       v                     v
/----------------------\  /----------\
| Lihat daftar         |  | Selesai  |
| peminjaman aktif     |  \----------/
\----------------------/
       |
       v
  /-------------------------\
  | Ada buku melewati       |
  | tanggal jatuh tempo?    |
  \-------------------------/
    | Ya                 | Tidak
    v                    v
/--------------------\  /---------------------\
| Tandai pinjaman   |  | Tampilkan status    |
| sebagai terlambat |  | tidak ada yang      |
\--------------------/  | terlambat           |
    |                    \---------------------/
    v                              |
/----------------------\           |
| Catat / sampaikan    |           |
| tindak lanjut kepada|           |
| anggota             |           |
\----------------------/           |
    |                              |
    v                              v
/----------\                  /----------\
| Selesai  |                  | Selesai  |
\----------/                  \----------/
```


```text
/--------------------------\
| Petugas memilih anggota |
| dan buku                |
\--------------------------/
             |
             v
/----------------------------------\
| Periksa apakah anggota ini masih |
| meminjam buku yang sama          |
\----------------------------------/
       | Ya                   | Tidak
       v                      v
/-------------------------\  /----------------------\
| Tampilkan peringatan:   |  | Simpan peminjaman    |
| buku ini masih dipinjam |  | baru                 |
\-------------------------/  \----------------------/
       |                              |
       v                              v
/-------------------------\     /----------\
| Jangan buat peminjaman  |     | Selesai  |
| duplikat                |     \----------/
\-------------------------/
       |
       v
/----------\
| Selesai  |
\----------/
```
