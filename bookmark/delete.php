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
   POST 요청만 허용
========================= */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: mypage.php");
    exit;
}

/* =========================
   CSRF 토큰 검증
   - 위조 요청 차단
========================= */
if (
    !isset($_POST["csrf_token"]) ||
    !hash_equals($_SESSION["csrf_token"], $_POST["csrf_token"])
) {
    http_response_code(403);
    exit("잘못된 요청입니다.");
}

/* =========================
   삭제 대상 ID 확인
========================= */
$bookmark_id = $_POST["bookmark_id"] ?? null;

if ($bookmark_id && ctype_digit((string)$bookmark_id)) {

    /* =========================
       본인 소유 북마크만 삭제
       - IDOR 방어
    ========================= */
    $stmt = $pdo->prepare(
        "DELETE FROM bookmark
         WHERE id = ? AND user_id = ?"
    );
    $stmt->execute([
        $bookmark_id,
        $_SESSION["user_id"]
    ]);
}

/* =========================
   삭제 후 목록으로 이동
========================= */
header("Location: mypage.php");
exit;
