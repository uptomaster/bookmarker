<?php
session_start();

/* 로그인 상태면 바로 마이페이지 */
if (isset($_SESSION["user_id"])) {
    header("Location: mypage.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>Bookmark | 나만의 북마크 서비스</title>

    <style>
    :root {
        --bg: #f7f9fc;
        --card: #ffffff;
        --primary: #4f46e5;
        --primary-dark: #4338ca;
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

    .hero {
        min-height: calc(100vh - 72px);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 20px;
    }

    .hero-card {
        background: var(--card);
        padding: 48px 40px;
        border-radius: 18px;
        max-width: 520px;
        width: 100%;
        box-shadow: 0 10px 30px rgba(0,0,0,0.06);
    }

    .hero-card h1 {
        margin-top: 0;
        font-size: 28px;
    }

    .hero-card p {
        color: var(--subtext);
        margin: 18px 0 32px;
        line-height: 1.6;
    }

    .actions {
        display: flex;
        gap: 12px;
        justify-content: center;
    }

    .actions a {
        flex: 1;
        padding: 14px 0;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
    }

    .login-btn {
        background: var(--primary);
        color: white;
    }

    .login-btn:hover {
        background: var(--primary-dark);
    }

    .register-btn {
        border: 1px solid var(--border);
        color: var(--text);
        background: white;
    }

    .register-btn:hover {
        background: #f3f4f6;
    }

    @media (max-width: 500px) {
        .hero-card {
            padding: 36px 28px;
        }

        .actions {
            flex-direction: column;
        }
    }
    </style>
</head>
<body>

<?php include "header.php"; ?>

<section class="hero">
    <div class="hero-card">
        <h1>🔖 Bookmark</h1>
        <p>
            흩어져 있는 링크를 한 곳에.<br>
            로그인하고 나만의 북마크를 안전하게 관리하세요.
        </p>

        <div class="actions">
            <a href="login.php" class="login-btn">로그인</a>
            <a href="register.php" class="register-btn">회원가입</a>
        </div>
    </div>
</section>

</body>
</html>
