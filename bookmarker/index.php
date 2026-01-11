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
    <?php include "head.php"; ?>
    <meta charset="UTF-8">
    
    <link rel="icon" type="image/png" href="/bookmarker/bookmarkerlogo-Photoroom.png">
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

    * { box-sizing: border-box; }

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
        padding: 56px 40px;
        border-radius: 22px;
        max-width: 560px;
        width: 100%;
        box-shadow: 0 14px 40px rgba(0,0,0,0.08);
    }

    /* 🎥 캐릭터 영상 */
    .hero-video {
        width: 220px;
        height: 220px;
        margin: 0 auto 28px;
        border-radius: 28px;
        overflow: hidden;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .hero-video video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .hero-card h1 {
        margin: 0;
        font-size: 30px;
        font-weight: 800;
    }

    .hero-card p {
        color: var(--subtext);
        margin: 18px 0 36px;
        line-height: 1.6;
        font-size: 15px;
    }

    .actions {
        display: flex;
        gap: 14px;
        justify-content: center;
    }

    .actions a {
        flex: 1;
        padding: 14px 0;
        border-radius: 14px;
        text-decoration: none;
        font-weight: 600;
        font-size: 15px;
        transition: 0.2s;
    }

    .login-btn {
        background: var(--primary);
        color: white;
    }

    .login-btn:hover {
        background: var(--primary-dark);
        transform: translateY(-1px);
    }

    .register-btn {
        border: 1px solid var(--border);
        color: var(--text);
        background: white;
    }

    .register-btn:hover {
        background: #f3f4f6;
        transform: translateY(-1px);
    }

    @media (max-width: 500px) {
        .hero-card {
            padding: 40px 28px;
        }

        .hero-video {
            width: 180px;
            height: 180px;
        }

        .actions {
            flex-direction: column;
        }
    }
    </style>
</head>
<body>
<link rel="stylesheet" href="style.css">
<?php include "header.php"; ?>

<section class="hero">
    <div class="hero-card">

        <!-- 🎥 메인 캐릭터 영상 -->
        <div class="hero-video">
            <video
                src="/bookmarker/video/logo.mp4"
                autoplay
                muted
                loop
                playsinline
            ></video>
        </div>

        <h1>Bookmark</h1>
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
