<?php
declare(strict_types=1);
require __DIR__ . '/../includes/admin.php';

// パスワードが設定済みなら、この画面は使えません
if (has_password()) {
    redirect('login.php');
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $pw = (string) ($_POST['password'] ?? '');
    $pw2 = (string) ($_POST['password2'] ?? '');
    if (mb_strlen($pw) < 8) {
        $error = 'パスワードは8文字以上で入力してください。';
    } elseif ($pw !== $pw2) {
        $error = '確認用のパスワードが一致しません。';
    } elseif (!set_password($pw)) {
        $error = 'パスワードを保存できませんでした。data フォルダに書き込み権限があるか確認してください。';
    } else {
        login();
        flash('ok', 'パスワードを設定しました。お知らせを追加できます。');
        redirect('index.php');
    }
}

admin_head('初期設定', false);
?>
<section class="panel narrow">
  <h1>はじめに、パスワードを決めます</h1>
  <p class="muted">管理画面に入るためのパスワードです。8文字以上で、他のサービスと別のものにしてください。この設定は最初の1回だけ行います。</p>
<?php if ($error): ?>
  <p class="flash error" role="alert"><?= h($error) ?></p>
<?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <div class="field"><label for="password">パスワード（8文字以上）</label><input id="password" name="password" type="password" autocomplete="new-password" required minlength="8"></div>
    <div class="field"><label for="password2">パスワード（確認）</label><input id="password2" name="password2" type="password" autocomplete="new-password" required minlength="8"></div>
    <button class="btn" type="submit">設定して始める</button>
  </form>
</section>
<?php admin_foot();
