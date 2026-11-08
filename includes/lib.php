<?php
declare(strict_types=1);

/*
 * 共通部品: お知らせの読み書き、ログイン、CSRF対策
 * 公開ページと管理画面の両方から読み込みます。
 */

date_default_timezone_set('Asia/Tokyo');

define('TS_DATA', dirname(__DIR__) . '/data');
define('NEWS_FILE', TS_DATA . '/news.json');
define('CONFIG_FILE', TS_DATA . '/config.php');

// お知らせの区分。ここを書き換えると、管理画面の選択肢も変わります。
const CATEGORIES = ['イベント', 'プレスリリース', '採用', '認証', 'お知らせ'];

function h(string $s): string
{
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/* ---------- お知らせデータ ---------- */

function news_all(): array
{
    if (!is_file(NEWS_FILE)) {
        return [];
    }
    $data = json_decode((string) file_get_contents(NEWS_FILE), true);
    if (!is_array($data)) {
        return [];
    }
    usort($data, function (array $a, array $b): int {
        return [$b['date'], $b['id']] <=> [$a['date'], $a['id']];
    });
    return $data;
}

function news_published(): array
{
    return array_values(array_filter(news_all(), function (array $n): bool {
        return !empty($n['published']);
    }));
}

function news_find(int $id): ?array
{
    foreach (news_all() as $n) {
        if ((int) $n['id'] === $id) {
            return $n;
        }
    }
    return null;
}

function news_write(array $items): bool
{
    $json = json_encode(array_values($items), JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
    if ($json === false) {
        return false;
    }
    $tmp = NEWS_FILE . '.tmp';
    if (file_put_contents($tmp, $json, LOCK_EX) === false) {
        return false;
    }
    return rename($tmp, NEWS_FILE);
}

/** 読み込み→変更→保存を、他の操作と重ならないように行う */
function news_update(callable $fn): bool
{
    $lock = fopen(TS_DATA . '/.lock', 'c');
    if ($lock === false) {
        return false;
    }
    flock($lock, LOCK_EX);
    try {
        $items = $fn(news_all());
        return news_write($items);
    } finally {
        flock($lock, LOCK_UN);
        fclose($lock);
    }
}

function news_next_id(array $items): int
{
    $max = 0;
    foreach ($items as $n) {
        $max = max($max, (int) $n['id']);
    }
    return $max + 1;
}

/** 本文を段落に分けて表示用のHTMLにする（空行で段落、改行は<br>） */
function format_body(string $body): string
{
    $paras = preg_split('/\R{2,}/u', trim($body)) ?: [];
    $out = '';
    foreach ($paras as $p) {
        $out .= '<p>' . nl2br(h(trim($p)), false) . '</p>';
    }
    return $out;
}

function format_date(string $date): string
{
    return str_replace('-', '.', $date);
}

/* ---------- ログイン ---------- */

function session_boot(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_name('tsumugi_admin');
        session_set_cookie_params(['path' => '/', 'httponly' => true, 'samesite' => 'Lax']);
        session_start();
    }
}

function config(): array
{
    if (!is_file(CONFIG_FILE)) {
        return [];
    }
    $c = require CONFIG_FILE;
    return is_array($c) ? $c : [];
}

function has_password(): bool
{
    return !empty(config()['password_hash']);
}

function set_password(string $password): bool
{
    $code = "<?php\nreturn " . var_export(['password_hash' => password_hash($password, PASSWORD_DEFAULT)], true) . ";\n";
    $ok = file_put_contents(CONFIG_FILE, $code, LOCK_EX) !== false;
    if ($ok && function_exists('opcache_invalidate')) {
        opcache_invalidate(CONFIG_FILE, true);
    }
    return $ok;
}

function verify_password(string $password): bool
{
    $hash = config()['password_hash'] ?? '';
    return $hash !== '' && password_verify($password, $hash);
}

function login(): void
{
    session_regenerate_id(true);
    $_SESSION['admin'] = true;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin']);
}

function redirect(string $to): void
{
    header('Location: ' . $to);
    exit;
}

function require_login(): void
{
    if (!has_password()) {
        redirect('setup.php');
    }
    if (!is_logged_in()) {
        redirect('login.php');
    }
}

/* ---------- CSRF・一度だけ表示するメッセージ ---------- */

function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . h(csrf_token()) . '">';
}

function csrf_check(): void
{
    $sent = $_POST['csrf'] ?? '';
    if (!is_string($sent) || !hash_equals(csrf_token(), $sent)) {
        http_response_code(400);
        exit('不正なリクエストです。画面を開き直して、もう一度お試しください。');
    }
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
}

function take_flash(): ?array
{
    $f = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $f;
}
