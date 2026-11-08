<?php
declare(strict_types=1);
require __DIR__ . '/../includes/admin.php';

if (!has_password()) {
    redirect('setup.php');
}
if (is_logged_in()) {
    redirect('index.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    if (verify_password((string) ($_POST['password'] ?? ''))) {
        login();
        redirect('index.php');
    }
    usleep(800000); // 総当たりを遅くするため、失敗時に少し待たせます
    $error = 'パスワードが違います。';
}

admin_head('ログイン', false);
?>
<section class="panel narrow">
  <h1>ログイン</h1>
<?php if ($error): ?>
  <p class="flash error" role="alert"><?= h($error) ?></p>
<?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="field"><label for="password">パスワード</label><input id="password" name="password" type="password" autocomplete="current-password" required autofocus></div>
    <button class="btn" type="submit">ログインする</button>
  </form>
</section>
<?php admin_foot();
