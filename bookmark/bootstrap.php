<?php
// bootstrap.php

session_start();

/* 세션 타임아웃 */
$SESSION_TIMEOUT = 1800;

if (isset($_SESSION["LAST_ACTIVITY"]) &&
    time() - $_SESSION["LAST_ACTIVITY"] > $SESSION_TIMEOUT) {

    session_unset();
    session_destroy();
    header("Location: login.php");
    exit;
}

$_SESSION["LAST_ACTIVITY"] = time();

/* CSRF 토큰 생성 */
if (!isset($_SESSION["csrf_token"])) {
    $_SESSION["csrf_token"] = bin2hex(random_bytes(32));
}
