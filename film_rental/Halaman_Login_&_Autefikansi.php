<?php
require_once 'config/config.php';
if (!empty($_SESSION['user_id'])) { header('Location: index.php'); exit; }

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if ($u === '' || $p === '') {
        $error = t('login_empty');
    } else {
        $stmt = $conn->prepare('SELECT id, username, password FROM users WHERE username = ?');
        $stmt->bind_param('s', $u);
        $stmt->execute();
        $user = $stmt->get_result()->fetch_assoc();
        if ($user && password_verify($p, $user['password'])) {
            session_regenerate_id(true);
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            header('Location: index.php'); exit;
        }
        $error = t('login_failed');
    }
}
page_header(t('login'));
?>
<h2><?= e(t('login')) ?></h2>
<?php if ($error): ?><p><b><?= e($error) ?></b></p><?php endif; ?>
<form method="POST" action="">
  <table cellpadding="4">
    <tr><td><?= e(t('username')) ?></td><td><input type="text" name="username" value="<?= e($_POST['username'] ?? '') ?>"></td></tr>
    <tr><td><?= e(t('password')) ?></td><td><input type="password" name="password"></td></tr>
  </table>
  <p><button type="submit"><?= e(t('login')) ?></button></p>
</form>
<?php page_footer(); ?>
