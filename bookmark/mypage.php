<?php
/* =========================
   공통 보안 초기화
   - 세션 시작
   - 세션 타임아웃
   - CSRF 토큰 준비
========================= */
require_once "bootstrap.php";
require_once "db.php";

/* =========================
   로그인 체크 (인가)
   - 로그인 안 된 사용자는 접근 차단
========================= */
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

/* =========================
   북마크 추가 처리 (POST)
   - CSRF 검증
   - PRG 패턴 적용
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["url"])) {

    /* CSRF 토큰 검증 */
    if (
        !isset($_POST["csrf_token"]) ||
        !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
    ) {
        http_response_code(403);
        exit("잘못된 요청입니다.");
    }

    $title = trim($_POST["title"]);
    $url   = trim($_POST["url"]);

    if ($url !== "") {
        $stmt = $pdo->prepare(
            "INSERT INTO bookmark (user_id, title, url)
             VALUES (?, ?, ?)"
        );
        $stmt->execute([
            $_SESSION["user_id"],
            $title,
            $url
        ]);
    }

    /* POST → Redirect → GET
       - 새로고침 시 중복 저장 방지 */
    header("Location: mypage.php");
    exit;
}

/* =========================
   내 북마크 목록 조회
   - user_id 기반 접근 제어
========================= */
$stmt = $pdo->prepare(
    "SELECT id, title, url, created_at
     FROM bookmark
     WHERE user_id = ?
     ORDER BY created_at DESC"
);
$stmt->execute([$_SESSION["user_id"]]);
$bookmarks = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<title>마이페이지 | Bookmark</title>

<style>
/* =========================
   디자인 변수
========================= */
:root {
    --bg: #f7f9fc;
    --card: #ffffff;
    --primary: #4f46e5;
    --primary-dark: #4338ca;
    --danger: #ef4444;
    --text: #1f2937;
    --subtext: #6b7280;
    --border: #e5e7eb;
}

/* =========================
   기본 레이아웃
========================= */
* { box-sizing: border-box; }

body {
    margin: 0;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
                 Roboto, "Apple SD Gothic Neo", "Noto Sans KR", sans-serif;
    background: var(--bg);
    color: var(--text);
}

/* =========================
   컨테이너
========================= */
.container {
    max-width: 720px;
    margin: 60px auto;
    background: var(--card);
    padding: 32px;
    border-radius: 14px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.06);
}

.user {
    color: var(--subtext);
    margin-bottom: 24px;
}

hr {
    border: none;
    border-top: 1px solid var(--border);
    margin: 28px 0;
}

/* =========================
   입력 폼
========================= */
input[type="text"],
input[type="url"] {
    width: 100%;
    padding: 12px 14px;
    margin-bottom: 12px;
    border-radius: 10px;
    border: 1px solid var(--border);
    font-size: 14px;
}

input:focus {
    outline: none;
    border-color: var(--primary);
}

/* =========================
   버튼
========================= */
button {
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    background: var(--primary);
    color: white;
    font-size: 14px;
    cursor: pointer;
}

button:hover {
    background: var(--primary-dark);
}

button.delete {
    background: transparent;
    color: var(--danger);
    border: 1px solid var(--danger);
}

button.delete:hover {
    background: var(--danger);
    color: white;
}

.edit-btn {
    display: inline-block;
    padding: 8px 14px;
    margin-right: 6px;
    border-radius: 10px;
    border: 1px solid var(--border);
    text-decoration: none;
    font-size: 13px;
    color: var(--text);
    background: #fff;
}

.edit-btn:hover {
    background: #f3f4f6;
}

/* =========================
   북마크 목록
========================= */
ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

li {
    padding: 16px;
    border-radius: 12px;
    border: 1px solid var(--border);
    margin-bottom: 12px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.bookmark-info a {
    font-weight: 600;
    color: var(--primary);
    text-decoration: none;
}

.bookmark-info a:hover {
    text-decoration: underline;
}

.bookmark-info small {
    display: block;
    color: var(--subtext);
    margin-top: 4px;
}

/* =========================
   반응형
========================= */
@media (max-width: 600px) {
    .container {
        margin: 20px;
        padding: 24px;
    }

    li {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>
</head>
<body>
<link rel="stylesheet" href="style.css">
<?php include "header.php"; ?>

<div class="container">

<h2>마이페이지</h2>
<p class="user">
    환영합니다,
    <strong><?= htmlspecialchars($_SESSION["user_email"]) ?></strong> 님
</p>

<hr>

<h3>북마크 추가</h3>
<form method="post">
    <!-- CSRF 토큰 -->
    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

    <input type="text" name="title" placeholder="제목 (선택)">
    <input type="url" name="url" placeholder="https://example.com" required>
    <button type="submit">저장</button>
</form>

<hr>

<h3>내 북마크 목록</h3>

<?php if (count($bookmarks) === 0): ?>
    <p>아직 저장한 북마크가 없습니다.</p>
<?php else: ?>
<ul>
<?php foreach ($bookmarks as $bm): ?>
<li>
    <div class="bookmark-info">
        <a href="<?= htmlspecialchars($bm["url"]) ?>" target="_blank">
            <?= htmlspecialchars($bm["title"] ?: $bm["url"]) ?>
        </a>
        <small><?= $bm["created_at"] ?></small>
    </div>

    <div>
        <a href="edit.php?id=<?= $bm["id"] ?>" class="edit-btn">수정</a>

        <form method="post" action="delete.php" style="display:inline;">
            <input type="hidden" name="bookmark_id" value="<?= $bm["id"] ?>">
            <input type="hidden" name="csrf_token"
                   value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">
            <button type="submit" class="delete"
                onclick="return confirm('정말 삭제할까요?')">
                삭제
            </button>
        </form>
    </div>
</li>
<?php endforeach; ?>
</ul>
<?php endif; ?>

<hr>

<a href="logout.php" class="logout">로그아웃</a>

</div>
</body>
</html>
