<?php
session_start();
require_once "db.php";

/* 이미 로그인 상태면 마이페이지로 */
if (isset($_SESSION["user_id"])) {
    header("Location: mypage.php");
    exit;
}

$message = "";

/* =========================
   로그인 처리
========================= */
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email    = trim($_POST["email"]);
    $password = $_POST["password"];

    if ($email === "" || $password === "") {
        $message = "이메일과 비밀번호를 모두 입력하세요.";
    } else {
        $stmt = $pdo->prepare(
            "SELECT id, email, password FROM user WHERE email = ?"
        );
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION["user_id"]    = $user["id"];
            $_SESSION["user_email"] = $user["email"];
            header("Location: mypage.php");
            exit;
        } else {
            $message = "이메일 또는 비밀번호가 올바르지 않습니다.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>로그인 | Bookmark</title>

    <style>
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

    * {
        box-sizing: border-box;
    }

    body {
        margin: 0;
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI",
                     Roboto, "Apple SD Gothic Neo", "Noto Sans KR", sans-serif;
        background: var(--bg);
        color: var(--text);
    }

    .container {
        max-width: 420px;
        margin: 80px auto;
        background: var(--card);
        padding: 32px;
        border-radius: 14px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }

    h2 {
        margin-top: 0;
        text-align: center;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 12px 14px;
        margin-bottom: 14px;
        border-radius: 10px;
        border: 1px solid var(--border);
        font-size: 14px;
    }

    input:focus {
        outline: none;
        border-color: var(--primary);
    }

    button {
        width: 100%;
        padding: 12px;
        border-radius: 10px;
        border: none;
        background: var(--primary);
        color: white;
        font-size: 15px;
        cursor: pointer;
        transition: 0.2s;
    }

    button:hover {
        background: var(--primary-dark);
    }

    .message {
        margin-top: 14px;
        color: var(--danger);
        font-size: 14px;
        text-align: center;
    }

    .links {
        margin-top: 20px;
        text-align: center;
        font-size: 14px;
    }

    .links a {
        color: var(--primary);
        text-decoration: none;
    }

    .links a:hover {
        text-decoration: underline;
    }
    </style>
</head>
<body>

<?php include "header.php"; ?>

<div class="container">

    <h2>로그인</h2>

    <form method="post">
        <input type="email" name="email" placeholder="이메일" required>
        <input type="password" name="password" placeholder="비밀번호" required>
        <button type="submit">로그인</button>
    </form>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="links">
        아직 계정이 없나요?  
        <a href="register.php">회원가입</a>
    </div>

</div>

</body>
</html>
