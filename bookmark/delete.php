<?php
session_start();
require_once "db.php";

/* 로그인 체크 */
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

/* POST만 허용 */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: mypage.php");
    exit;
}

$bookmark_id = $_POST["bookmark_id"] ?? null;

if ($bookmark_id) {
    $stmt = $pdo->prepare(
        "DELETE FROM bookmark
         WHERE id = ? AND user_id = ?"
    );
    $stmt->execute([
        $bookmark_id,
        $_SESSION["user_id"]
    ]);
}

header("Location: mypage.php");
exit;
