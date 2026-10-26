<?php
require_once 'config/config.php';
require_login();

// Delete (POST + token CSRF)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['aksi'] ?? '') === 'hapus') {
    if (csrf_valid($_POST['csrf'] ?? '')) {
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id) {
            $stmt = $conn->prepare('DELETE FROM peminjaman WHERE id = ?');
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $_SESSION['flash'] = 'deleted';
        }
    }
    header('Location: index.php');
    exit;
}

$msg = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

// Read
$result = $conn->query('SELECT * FROM peminjaman ORDER BY id DESC');
page_header(t('list'));
?>
<h2><?= e(t('list')) ?></h2>
<?php if ($msg): ?><p><b><?= e(t($msg)) ?></b></p><?php endif; ?>
<p><a href="tambah.php">[+ <?= e(t('add')) ?>]</a></p>
<table border="1" cellpadding="6" cellspacing="0">
  <tr>
    <th><?= e(t('no')) ?></th><th><?= e(t('borrower')) ?></th><th><?= e(t('film')) ?></th>
    <th><?= e(t('borrow_date')) ?></th><th><?= e(t('return_date')) ?></th>
    <th><?= e(t('status')) ?></th><th><?= e(t('note')) ?></th><th><?= e(t('action')) ?></th>
  </tr>
  <?php if ($result->num_rows === 0): ?>
    <tr><td colspan="8"><?= e(t('empty')) ?></td></tr>
  <?php endif; ?>
  <?php $no = 1; while ($r = $result->fetch_assoc()): ?>
  <tr>
    <td><?= $no++ ?></td>
    <td><?= e($r['nama_peminjam']) ?></td>
    <td><?= e($r['judul_film']) ?></td>
    <td><?= e($r['tanggal_pinjam']) ?></td>
    <td><?= e($r['tanggal_kembali']) ?></td>
    <td><?= e(status_label($r['status'])) ?></td>
    <td><?= e($r['catatan']) ?></td>
    <td>
      <a href="edit.php?id=<?= (int)$r['id'] ?>"><?= e(t('edit')) ?></a>
      <form method="POST" action="index.php" style="display:inline" onsubmit="return confirm('<?= e(t('confirm_delete')) ?>')">
        <input type="hidden" name="aksi" value="hapus">
        <input type="hidden" name="id" value="<?= (int)$r['id'] ?>">
        <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
        <button type="submit"><?= e(t('delete')) ?></button>
      </form>
    </td>
  </tr>
  <?php endwhile; ?>
</table>
<?php page_footer(); ?>
