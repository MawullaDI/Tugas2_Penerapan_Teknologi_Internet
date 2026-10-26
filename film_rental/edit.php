<?php
require_once 'config/config.php';
require_login();

$id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
if (!$id) { $_SESSION['flash'] = 'not_found'; header('Location: index.php'); exit; }

$stmt = $conn->prepare('SELECT * FROM peminjaman WHERE id = ?');
$stmt->bind_param('i', $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
if (!$row) { $_SESSION['flash'] = 'not_found'; header('Location: index.php'); exit; }

$errors = [];
$data = $row;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_valid($_POST['csrf'] ?? '')) {
        $errors[] = t('err_csrf');
    } else {
        [$data, $errors] = validate_rental($_POST);
        if (!$errors) {
            $stmt = $conn->prepare('UPDATE peminjaman SET nama_peminjam=?, judul_film=?, tanggal_pinjam=?, tanggal_kembali=?, status=?, catatan=? WHERE id=?');
            $stmt->bind_param('ssssssi', $data['nama_peminjam'], $data['judul_film'], $data['tanggal_pinjam'], $data['tanggal_kembali'], $data['status'], $data['catatan'], $id);
            $stmt->execute();
            $_SESSION['flash'] = 'updated';
            header('Location: index.php'); exit;
        }
    }
}
page_header(t('edit'));
?>
<h2><?= e(t('edit')) ?></h2>
<form method="POST" action="edit.php?id=<?= (int)$id ?>">
  <?php render_form_fields($data, $errors); ?>
</form>
<?php page_footer(); ?>
