<?php
if (!(file_exists(INSTALL_ROOT . '/config/install.lock') && file_exists(INSTALL_ROOT . '/config/database.php'))) {//step为完成页面
    header('Location: ?step=1');
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>安装完成</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; padding: 50px; }
        .success { color: #4CAF50; font-size: 24px; margin-bottom: 20px; }
        .btn { 
            display: inline-block; 
            padding: 10px 20px; 
            background: #4CAF50; 
            color: white; 
            text-decoration: none;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="success">✓ 系统安装成功</div>
    <a href="/admin.php" class="btn">进入后台</a>
    <a href="/" class="btn">查看前台</a>
</body>
</html>