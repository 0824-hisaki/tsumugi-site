<?php
declare(strict_types=1);

/* 管理画面の共通部分（読み込み・ページの上下の枠） */

require __DIR__ . '/lib.php';
session_boot();

function admin_head(string $title, bool $showNav = true): void
{
    $flash = take_flash();
    ?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title><?= h($title) ?> | 管理画面</title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Shippori+Mincho+B1:wght@800&family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap">
<link rel="stylesheet" href="admin.css">
</head>
<body>
<header class="top">
  <div class="wrap bar">
    <a class="brand" href="index.php"><b>TSUMUGI</b><small>お知らせ管理</small></a>
<?php if ($showNav): ?>
    <nav>
      <a href="../index.php" target="_blank" rel="noopener">サイトを見る</a>
      <form method="post" action="logout.php">
        <?= csrf_field() ?>
        <button class="link" type="submit">ログアウト</button>
      </form>
    </nav>
<?php endif; ?>
  </div>
</header>
<main class="wrap">
<?php if ($flash): ?>
  <p class="flash <?= h($flash['type']) ?>" role="status"><?= h($flash['message']) ?></p>
<?php endif; ?>
<?php
}

function admin_foot(): void
{
    ?>
</main>
</body>
</html>
<?php
}
