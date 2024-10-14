## 0.0.10 Indev (14-10-2024)

- Merubah userType menjadi data static, bukan lagi dinamis dari database
- Memperbarui tampilan dan cara kerja admin untuk mengelolah data kategori kelas
- Update php dan js package
- Merubah tampilan dan cara kerja pengelolahan configurasi kelas
- Menambahkan id primary pada table kelas dan merubah url route dan fungsi dari config controller yang berhubungan dengan kelas
- Menambahkan settings.json untuk vscode

## 0.0.9 Indev (10-08-2024)

- Mengganti sistem hashing password menjadi sistem enkripsi
- Menambahkan halaman untuk mengetes atau mencoba sistem enkripsi

## 0.0.8 Indev (13-10-2024)

- Merubah tampilan dan code untuk config mapel secara besar besaran
- Membuat mapel seeder
- Membuat model untuk mapel
- Membuat table untuk data mapel
- Membuat dan menambahkan desain tampilan untuk mengelolah kelas kategori di config
- Membuat route dan menambahkan fungsi di config controller untuk dapat mengelolah kelas kategori
- Membuat seeder untuk kelas kategori
- Membuat model untuk kelas kategori
- Menambahkan table untuk kelas kategori
- Membuat peringatan ketika ingin menghapus gambar slider
- Menambahkan controller untuk mengelolah kelas
- Membuat component config kelas untuk halaman config
- Membuat kelas seeder
- Membuat kelas model
- Membuat table kelas

## 0.0.7 Indev (10-08-2024)

- Merubah folder `en` di lang menjadi `id` dan merubah beberapa isi file nya menjadi bahasa indonesia
- Merubah sistem autentikasi menjadi menggunakan username
- Mempublish folder lang di laravel 
- Memperbaiki fitur remember me

## 0.0.6 Indev (12-10-2024)

- Membuat admin dapat mengatur gambar pada slider di Slider.vue
- Mengconvert semua gambar menjadi webp dan menambahkan beberapa gambar yang di perlukan
- Membuat component baru EditableImg
- Menambahkan slider card di halaman config admin, tetapi belum ada fungsi dan kegunaan nya

## 0.0.5 Indev (11-10-2024)

- Menambahkan kata sapaan kepada user di dashboard
- Mengimplementasikan library magic grid
- Menambahkan status baru pada ExamTimeCard
- Membuat sistem generate otomatis untuk configurasi reverb yang kosong di .env
- Menambahkan run_dev.sh dan setup_dev.sh
- Menambahkan file bat, untuk mempermudah tahap development
- Membuat event broadcast untuk mengupdate eam time card dan memperbaiki bug pada exam time card
- Memisahkan config jadi beberapa card
- Memperbarui tampilan list user

## 0.0.4 Indev (10-10-2024)

- Merubah tata letak halaman akun
- Menambahkan rule password yang baru dengan easter egg
- Menambahkan enum user type
- Menghapus detik pada penulisan waktu di ringkasan configData.vue
- Merubah algoritma update ExamTimeCard dari claude ai
- Mencoba menggunakan algoritma dari chatgpt
- Membuat algoritma waktu ujian di frontend (Belum jadi)
- Membuat tampilan exam time card
- Menambahkan validasi pada backend dan frontend untuk exam time
- Menambahkan validasi untuk tanggal libur di frontend dan backend
- Merubah format tanggal pada info tanggal ujian
- Menambahkan fungsi utils stringFormatDateWithDay
- Menyamakan input tanggal dan waktu sama seperti input text untuk warna border nya
- Menambahkan validasi backend untuk tanggal ujian
- Menambahkan validasi frontend untuk tanggal mulai dan selesai ujian
- Menambahkan exam date dan exam time

## 0.0.3 Indev (06-08-2024)

- Membuat project laravel
- Mengatur database menjadi mysql
- Menambahkan laravel breeze dengan vuejs dan inertia