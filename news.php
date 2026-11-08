<?php
declare(strict_types=1);
require __DIR__ . '/includes/lib.php';

// ?id=3 のときは1件の詳細、指定がなければ一覧を表示します（公開中のものだけ）
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$article = null;
$notFound = false;
if ($id > 0) {
    $found = news_find($id);
    if ($found !== null && !empty($found['published'])) {
        $article = $found;
    } else {
        http_response_code(404);
        $notFound = true;
    }
}
$list = $id > 0 ? [] : news_published();

if ($article) {
    $pageTitle = $article['title'] . ' | ツムギテクノロジーズ';
} elseif ($notFound) {
    $pageTitle = 'ページが見つかりません | ツムギテクノロジーズ';
} else {
    $pageTitle = 'お知らせ | ツムギテクノロジーズ';
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= h($pageTitle) ?></title>
<link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Shippori+Mincho+B1:wght@800&family=Zen+Kaku+Gothic+New:wght@400;500;700&display=swap">
<link rel="stylesheet" href="assets/news.css">
</head>
<body>
<header class="site">
  <div class="wrap bar">
    <a class="logo" href="index.php" aria-label="ツムギテクノロジーズ トップへ"><span class="names"><b>TSUMUGI</b><small>ツムギテクノロジーズ</small></span></a>
    <a class="back" href="index.php">← トップへ戻る</a>
  </div>
</header>

<main class="wrap">
<?php if ($article): ?>
  <article class="article">
    <p class="eyebrow">News / お知らせ</p>
    <div class="meta"><time datetime="<?= h($article['date']) ?>"><?= h(format_date($article['date'])) ?></time><span class="tag"><?= h($article['category']) ?></span></div>
    <h1><?= h($article['title']) ?></h1>
    <div class="body"><?= format_body($article['body']) ?></div>
    <a class="btn ghost" href="news.php">← お知らせ一覧へ</a>
  </article>
<?php elseif ($notFound): ?>
  <p class="eyebrow">404</p>
  <h1>お探しのお知らせは見つかりませんでした</h1>
  <p class="lead">公開が終了したか、アドレスが違う可能性があります。</p>
  <a class="btn" href="news.php">お知らせ一覧へ</a>
<?php else: ?>
  <p class="eyebrow">News / お知らせ</p>
  <h1>お知らせ</h1>
<?php if ($list): ?>
  <ul class="news">
<?php foreach ($list as $n): ?>
    <li><a href="news.php?id=<?= (int) $n['id'] ?>"><time datetime="<?= h($n['date']) ?>"><?= h(format_date($n['date'])) ?></time><span class="tag"><?= h($n['category']) ?></span><span class="ttl"><?= h($n['title']) ?></span><span class="go" aria-hidden="true">→</span></a></li>
<?php endforeach; ?>
  </ul>
<?php else: ?>
  <p class="empty">現在、お知らせはありません。</p>
<?php endif; ?>
<?php endif; ?>
</main>

<footer>
  <div class="wrap">
    <small>© 2026 Tsumugi Technologies Inc.</small>
    <span>このサイトは架空の企業を題材にしたデモです。</span>
  </div>
</footer>
</body>
</html>
