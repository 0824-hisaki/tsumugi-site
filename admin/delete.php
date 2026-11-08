<?php
declare(strict_types=1);
require __DIR__ . '/../includes/admin.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}
csrf_check();

$id = (int) ($_POST['id'] ?? 0);
$ok = $id > 0 && news_update(function (array $items) use ($id): array {
    return array_values(array_filter($items, function (array $n) use ($id): bool {
        return (int) $n['id'] !== $id;
    }));
});

flash($ok ? 'ok' : 'error', $ok ? 'お知らせを削除しました。' : '削除できませんでした。');
redirect('index.php');
