<?php
session_start();
$email = $_POST["email"];
$password = $_POST["password"];

$errorTimeKey = $email . "-errorTime";
$errorCountKey = $email . "-errorCount";

$memcache = new Memcache;
$memcache->connect('localhost', 11211);

$errorTime = (int)$memcache->get($errorTimeKey);
$now = time();

$errorCount = (int)$memcache->get($errorCountKey);
if ($errorCount > 10) {
    $wait = 24 * 3600 + $errorTime - $now;
    die("您已多次尝试登录失败，为了保护您的账号，还需要等待 " . $wait . " 秒，才能尝试登录。");
}

if ($errorCount > 5) {
    $wait = 60 + $errorTime - $now;
    if ($wait > 0) {
        die("您已多次尝试登录失败，为了保护您的账号，还需要等待 " . $wait . " 秒，才能尝试登录。");
    }
}

$mysqli = new mysqli('127.0.0.1', 'site2', 'wemmew-dAzfiz-4dumco', 'site2');
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

if ($stmt = $mysqli->prepare("select userId from users where email=? and password=?")) {
    $stmt->bind_param("ss", $email, $password);
    $result = $stmt->execute();
    $stmt->bind_result($userId);

    if (!$stmt->fetch()) {
        echo "账号密码错误！";

        $errorCount += 1;
        $memcache->set($errorTimeKey, $now, false, 24 * 3600);
        $memcache->set($errorCountKey, $errorCount, false, 24 * 3600);

        $stmt->close();
        $mysqli->close();
        return;
    }
    $stmt->close();
    $mysqli->close();

    $_SESSION['userId'] = $userId;
    $_SESSION['email'] = $email;
    $_SESSION['time'] = time();
    
    header("Location: profile.php?userId=" . $userId);
}

?>