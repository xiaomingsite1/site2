<?php
$email = $_POST["email"];
$password = $_POST["password"];

$mysqli = new mysqli('127.0.0.1', 'site2', 'wemmew-dAzfiz-4dumco', 'site2');
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}
$hashedPassword = hash('sha256', $password);
if ($stmt = $mysqli->prepare("insert into users(email, password) values (?, ?)")) {
    $stmt->bind_param("ss", $email, $password);
    $stmt->execute();
}

$userId = $mysqli->insert_id;
$mysqli->close();
?>
<html>
    <head>小明的网站</head>
    <meta charset="UTF-8">
    <body>
        <h1>注册成功</h1>
        <div><a href="profile.php?userId=<?php echo $userId; ?>">查看注册信息</a></div>
    </body>
</html>
