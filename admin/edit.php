<?php
declare(strict_types=1);
require __DIR__ . '/../includes/admin.php';
require_login();

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$item = $id > 0 ? news_find($id) : null;
if ($id > 0 && $item === null) {
    flash('error', '指定されたお知らせが見つかりませんでした。');
    redirect('index.php');
}

$form = $item ?? [
    'date' => date('Y-m-d'),
    'category' => CATEGORIES[0],
    'title' => '',
    'body' => '',
    'published' => true,
];
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $form = [
        'date' => trim((string) ($_POST['date'] ?? '')),
        'category' => (string) ($_POST['category'] ?? ''),
        'title' => trim((string) ($_POST['title'] ?? '')),
        'body' => trim(str_replace("\r\n", "\n", (string) ($_POST['body'] ?? ''))),
        'published' => isset($_POST['published']),
    ];

    $d = DateTime::createFromFormat('Y-m-d', $form['date']);
    if (!$d || $d->format('Y-m-d') !== $form['date']) {
        $errors[] = '日付を正しく入力してください。';
    }
    if (!in_array($form['category'], CATEGORIES, true)) {
        $errors[] = '区分を選んでください。';
    }
    if ($form['title'] === '') {
        $errors[] = 'タイトルを入力してください。';
    } elseif (mb_strlen($form['title']) > 100) {
        $errors[] = 'タイトルは100文字以内にしてください。';
    }
    if (mb_strlen($form['body']) > 5000) {
        $errors[] = '本文は5000文字以内にしてください。';
    }

    if (!$errors) {
        $ok = news_update(function (array $items) use ($form, $id): array {
            $record = $form + ['updated' => date('c')];
            if ($id > 0) {
                foreach ($items as $i => $n) {
                    if ((int) $n['id'] === $id) {
                        $items[$i] = ['id' => $id] + $record;
                    }
                }
            } else {
                $items[] = ['id' => news_next_id($items)] + $record;
            }
            return $items;
        });
        if ($ok) {
            flash('ok', $id > 0 ? 'お知らせを更新しました。' : 'お知らせを追加しました。');
            redirect('index.php');
        }
        $errors[] = '保存できませんでした。data フォルダに書き込み権限があるか確認してください。';
    }
}

admin_head($id > 0 ? 'お知らせを編集' : 'お知らせを追加');
?>
<div class="head">
  <div>
    <h1><?= $id > 0 ? 'お知らせを編集' : 'お知らせを追加' ?></h1>
    <p class="muted">「公開する」にチェックが入っていると、サイトに表示されます。外すと下書きになります。</p>
  </div>
  <a class="btn ghost" href="index.php">← 一覧に戻る</a>
</div>

<?php if ($errors): ?>
  <div class="flash error" role="alert">
    <?php foreach ($errors as $e): ?><p><?= h($e) ?></p><?php endforeach; ?>
  </div>
<?php endif; ?>

<form class="panel" method="post">
  <?= csrf_field() ?>
  <div class="row">
    <div class="field"><label for="date">日付</label><input id="date" name="date" type="date" value="<?= h($form['date']) ?>" required></div>
    <div class="field"><label for="category">区分</label>
      <select id="category" name="category">
<?php foreach (CATEGORIES as $c): ?>
        <option value="<?= h($c) ?>"<?= $form['category'] === $c ? ' selected' : '' ?>><?= h($c) ?></option>
<?php endforeach; ?>
      </select>
    </div>
  </div>
  <div class="field"><label for="title">タイトル（100文字まで）</label><input id="title" name="title" type="text" maxlength="100" value="<?= h($form['title']) ?>" required></div>
  <div class="field"><label for="body">本文（5000文字まで）</label><textarea id="body" name="body" rows="12" maxlength="5000"><?= h($form['body']) ?></textarea>
    <p class="hint">空行を入れると段落が分かれます。改行は、そのまま改行として表示されます。</p></div>
  <label class="check" for="published"><input id="published" name="published" type="checkbox" value="1"<?= !empty($form['published']) ? ' checked' : '' ?>><span>公開する</span></label>
  <div class="actions">
    <button class="btn" type="submit">保存する</button>
    <a class="btn ghost" href="index.php">キャンセル</a>
  </div>
</form>
<?php admin_foot();
