<?php
session_start();
$userId = $_GET["userId"];

$mysqli = new mysqli('127.0.0.1', 'site2', 'wemmew-dAzfiz-4dumco', 'site2');
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

if ($stmt = $mysqli->prepare("select email from users where userId=?")) {
    $stmt->bind_param("i", $userId);
    $result = $stmt->execute();
    $stmt->bind_result($email);

    if (!$stmt->fetch()) {
        die("用户不存在");
    }

    $stmt->close();
    $mysqli->close();


    $info = $email;
    if ($_SESSION['email'] != $email) {
        $info = '没有权限查看他人信息';
    }
}

?>
<html>
    <head>小明的网站</head>
    <meta charset="UTF-8">
    <body>
        <h1>个人信息</h1>
        <div>注册邮箱</div>
        <div><?php echo $info; ?></div>
    </body>
</html>
