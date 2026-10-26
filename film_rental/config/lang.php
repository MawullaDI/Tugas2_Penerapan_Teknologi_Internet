<?php
$TEXT = [
 'id' => [
  'app_title'=>'Peminjaman Film','login'=>'Masuk','logout'=>'Keluar','username'=>'Nama Pengguna','password'=>'Kata Sandi',
  'login_failed'=>'Nama pengguna atau kata sandi salah.','login_empty'=>'Nama pengguna dan kata sandi wajib diisi.',
  'list'=>'Daftar Peminjaman','add'=>'Tambah Peminjaman','edit'=>'Ubah Peminjaman','delete'=>'Hapus','save'=>'Simpan','cancel'=>'Batal',
  'no'=>'No','borrower'=>'Nama Peminjam','film'=>'Judul Film','borrow_date'=>'Tanggal Pinjam','return_date'=>'Tanggal Kembali',
  'status'=>'Status','note'=>'Catatan','action'=>'Aksi','borrowed'=>'Dipinjam','returned'=>'Dikembalikan','late'=>'Terlambat',
  'confirm_delete'=>'Yakin ingin menghapus data ini?','empty'=>'Belum ada data.','saved'=>'Data berhasil disimpan.',
  'updated'=>'Data berhasil diubah.','deleted'=>'Data berhasil dihapus.','not_found'=>'Data tidak ditemukan.',
  'err_borrower'=>'Nama peminjam wajib diisi (maks. 100 karakter).','err_film'=>'Judul film wajib diisi (maks. 150 karakter).',
  'err_date'=>'Format tanggal tidak valid.','err_range'=>'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
  'err_status'=>'Status tidak valid.','err_csrf'=>'Token tidak valid, muat ulang halaman.','language'=>'Bahasa','hello'=>'Halo'
 ],
 'en' => [
  'app_title'=>'Film Rental','login'=>'Login','logout'=>'Logout','username'=>'Username','password'=>'Password',
  'login_failed'=>'Wrong username or password.','login_empty'=>'Username and password are required.',
  'list'=>'Rental List','add'=>'Add Rental','edit'=>'Edit Rental','delete'=>'Delete','save'=>'Save','cancel'=>'Cancel',
  'no'=>'No','borrower'=>'Borrower','film'=>'Film Title','borrow_date'=>'Borrow Date','return_date'=>'Return Date',
  'status'=>'Status','note'=>'Note','action'=>'Action','borrowed'=>'Borrowed','returned'=>'Returned','late'=>'Late',
  'confirm_delete'=>'Delete this record?','empty'=>'No data yet.','saved'=>'Data saved successfully.',
  'updated'=>'Data updated successfully.','deleted'=>'Data deleted successfully.','not_found'=>'Record not found.',
  'err_borrower'=>'Borrower name is required (max 100 chars).','err_film'=>'Film title is required (max 150 chars).',
  'err_date'=>'Invalid date format.','err_range'=>'Return date cannot be before borrow date.',
  'err_status'=>'Invalid status.','err_csrf'=>'Invalid token, reload the page.','language'=>'Language','hello'=>'Hello'
 ]
];
function t($key){
  global $TEXT;
  $l = $_SESSION['lang'] ?? 'id';
  return $TEXT[$l][$key] ?? $key;
}
