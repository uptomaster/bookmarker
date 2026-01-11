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
========================= */
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

/* =========================
   id 유효성 검증
   - 숫자가 아니면 차단
========================= */
if (!isset($_GET["id"]) || !ctype_digit($_GET["id"])) {
    header("Location: mypage.php");
    exit;
}

$bookmark_id = (int)$_GET["id"];

/* =========================
   내 소유 북마크인지 확인
   - IDOR 방어
========================= */
$stmt = $pdo->prepare(
    "SELECT id, title, url
     FROM bookmark
     WHERE id = ? AND user_id = ?"
);
$stmt->execute([$bookmark_id, $_SESSION["user_id"]]);
$bookmark = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$bookmark) {
    header("Location: mypage.php");
    exit;
}

/* =========================
   북마크 수정 처리 (POST)
   - CSRF 검증
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

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
            "UPDATE bookmark
             SET title = ?, url = ?
             WHERE id = ? AND user_id = ?"
        );
        $stmt->execute([
            $title,
            $url,
            $bookmark_id,
            $_SESSION["user_id"]
        ]);
    }

    /* 수정 완료 후 목록으로 이동 */
    header("Location: mypage.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
<meta charset="UTF-8">
<?php include "head.php"; ?>
<link rel="icon" type="image/png" href="/bookmarker/bookmarkerlogo-Photoroom.png">
<title>북마크 수정 | Bookmarker</title>

<style>
/* =========================
   디자인 변수
========================= */
:root {
  --bg:#f7f9fc;
  --card:#fff;
  --primary:#4f46e5;
  --primary-dark:#4338ca;
  --text:#1f2937;
  --subtext:#6b7280;
  --border:#e5e7eb;
}

* { box-sizing:border-box; }

body {
  margin:0;
  font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",
               Roboto,"Apple SD Gothic Neo","Noto Sans KR",sans-serif;
  background:var(--bg);
  color:var(--text);
}

/* =========================
   컨테이너
========================= */
.container {
  max-width:520px;
  margin:80px auto;
  background:var(--card);
  padding:32px;
  border-radius:14px;
  box-shadow:0 10px 30px rgba(0,0,0,.06);
}

h2 { margin-top:0; }

label {
  font-size:14px;
  color:var(--subtext);
}

input {
  width:100%;
  padding:12px 14px;
  margin:8px 0 18px;
  border-radius:10px;
  border:1px solid var(--border);
  font-size:14px;
}

input:focus {
  outline:none;
  border-color:var(--primary);
}

/* =========================
   버튼 영역
========================= */
.actions {
  display:flex;
  gap:10px;
}

button,
a.btn {
  padding:10px 18px;
  border-radius:10px;
  border:none;
  background:var(--primary);
  color:#fff;
  font-size:14px;
  cursor:pointer;
  text-decoration:none;
  text-align:center;
}

button:hover,
a.btn:hover {
  background:var(--primary-dark);
}

a.cancel {
  background:#fff;
  color:var(--text);
  border:1px solid var(--border);
}

a.cancel:hover {
  background:#f3f4f6;
}
</style>
</head>
<body>
<link rel="stylesheet" href="style.css">
<?php include "header.php"; ?>

<div class="container">
  <h2>북마크 수정</h2>

  <form method="post">
    <!-- CSRF 토큰 -->
    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars($_SESSION["csrf_token"]) ?>">

    <label>제목</label>
    <input type="text" name="title"
           value="<?= htmlspecialchars($bookmark["title"]) ?>">

    <label>URL</label>
    <input type="url" name="url" required
           value="<?= htmlspecialchars($bookmark["url"]) ?>">

    <div class="actions">
      <button type="submit">저장</button>
      <a href="mypage.php" class="btn cancel">취소</a>
    </div>
  </form>
</div>

</body>
</html>
