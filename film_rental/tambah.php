<?php
require_once 'config/config.php';
require_login();

$errors = [];
$data = ['nama_peminjam'=>'','judul_film'=>'','tanggal_pinjam'=>date('Y-m-d'),
         'tanggal_kembali'=>date('Y-m-d', strtotime('+3 days')),'status'=>'dipinjam','catatan'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid($_POST['csrf'] ?? '')) {
        $errors[] = t('err_csrf');
    } else {
        [$data, $errors] = validate_rental($_POST);
        if (!$errors) {
            $stmt = $conn->prepare('INSERT INTO peminjaman (nama_peminjam, judul_film, tanggal_pinjam, tanggal_kembali, status, catatan) VALUES (?,?,?,?,?,?)');
            $stmt->bind_param('ssssss', $data['nama_peminjam'], $data['judul_film'], $data['tanggal_pinjam'], $data['tanggal_kembali'], $data['status'], $data['catatan']);
            $stmt->execute();
            $_SESSION['flash'] = 'saved';
            header('Location: index.php'); exit;
        }
    }
}
page_header(t('add'));
?>
<h2><?= e(t('add')) ?></h2>
<form method="POST" action="tambah.php">
  <?php render_form_fields($data, $errors); ?>
</form>
<?php page_footer(); ?>
