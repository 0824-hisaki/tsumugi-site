<?php
declare(strict_types=1);
require __DIR__ . '/../includes/admin.php';
require_login();

$items = news_all();
$publishedCount = count(array_filter($items, function (array $n): bool {
    return !empty($n['published']);
}));

admin_head('お知らせ一覧');
?>
<div class="head">
  <div>
    <h1>お知らせ</h1>
    <p class="muted">全<?= count($items) ?>件（公開 <?= $publishedCount ?>件 / 下書き <?= count($items) - $publishedCount ?>件）。トップページには、公開中の新しい4件が表示されます。</p>
  </div>
  <a class="btn" href="edit.php">＋ お知らせを追加</a>
</div>

<?php if (!$items): ?>
  <section class="panel empty">
    <p>まだお知らせがありません。「お知らせを追加」から、最初の1件を書いてみましょう。</p>
  </section>
<?php else: ?>
  <div class="table-wrap">
    <table>
      <thead>
        <tr><th>状態</th><th>日付</th><th>区分</th><th>タイトル</th><th>操作</th></tr>
      </thead>
      <tbody>
<?php foreach ($items as $n): ?>
        <tr>
          <td><span class="badge <?= !empty($n['published']) ? 'on' : 'off' ?>"><?= !empty($n['published']) ? '公開中' : '下書き' ?></span></td>
          <td class="mono"><?= h(format_date($n['date'])) ?></td>
          <td><span class="tag"><?= h($n['category']) ?></span></td>
          <td class="title"><?= h($n['title']) ?></td>
          <td class="ops">
            <a class="btn small ghost" href="edit.php?id=<?= (int) $n['id'] ?>">編集</a>
            <form method="post" action="delete.php" onsubmit="return confirm('このお知らせを削除します。元に戻せません。よろしいですか？');">
              <?= csrf_field() ?>
              <input type="hidden" name="id" value="<?= (int) $n['id'] ?>">
              <button class="btn small danger" type="submit">削除</button>
            </form>
          </td>
        </tr>
<?php endforeach; ?>
      </tbody>
    </table>
  </div>
<?php endif; ?>
<?php admin_foot();
