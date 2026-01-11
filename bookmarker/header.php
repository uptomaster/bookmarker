<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<header class="header">
    <div class="header-inner">
        <div class="logo">
            <a href="index.php">🔖 Bookmark</a>
        </div>

        <?php if (isset($_SESSION["user_email"])): ?>
            <nav class="nav">
                <span class="user-email">
                    <?= htmlspecialchars($_SESSION["user_email"]) ?>
                </span>
                <a href="mypage.php">마이페이지</a>
                <a href="logout.php" class="logout-btn">로그아웃</a>
            </nav>
        <?php endif; ?>
        <button id="themeToggle" class="theme-btn">
        🌙
        </button>

    </div>
</header>
