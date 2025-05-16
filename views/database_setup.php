<!DOCTYPE html>
<html>
<head>
    <title>SQLectric数据库安装向导 - 数据库设置</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #333; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="password"] { 
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; 
        }
        .actions { margin-top: 20px; display: flex; justify-content: space-between; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-next { background: #4CAF50; color: white; }
        .btn-prev { background: #f5f5f5; color: #333; }
        .error { color: #a94442; background: #f2dede; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <h1>数据库设置</h1>
    <div class="content">
        <?php if (isset($_POST['db_host'])): ?>
            <?php
            // 处理表单提交
            $db_host = $_POST['db_host'];
            $db_user = $_POST['db_user'];
            $db_pass = $_POST['db_pass'];
            $db_name = $_POST['db_name'];
            $db_prefix = $_POST['db_prefix'];
            
            // 测试数据库连接
            $conn = @new mysqli($db_host, $db_user, $db_pass, $db_name);
            if ($conn->connect_error): ?>
                <div class="error">
                    数据库连接失败: <?php echo $conn->connect_error; ?>
                </div>
            <?php else: 
                $conn->close();
                // 保存配置到会话
                $_SESSION['db_config'] = [
                    'host' => $db_host,
                    'user' => $db_user,
                    'pass' => $db_pass,
                    'name' => $db_name,
                    'prefix' => $db_prefix
                ];
                
                // 保存配置到文件
                $config = [
                    'host' => $db_host,
                    'username' => $db_user,
                    'password' => $db_pass,
                    'database' => $db_name,
                    'prefix' => $db_prefix
                ];
                file_put_contents(dirname(__DIR__).'/config/database.php', '<?php return '.var_export($config, true).';');
                
                header('Location: ?step=4');
                exit;
            endif; ?>
        <?php endif; ?>

        <form method="post">
            <div class="form-group">
                <label for="db_prefix">数据表前缀:</label>
                <input type="text" id="db_prefix" name="db_prefix" value="SQLectric_" required>
                <small style="color:#666">用于区别同一数据库中的多个应用</small>
            </div>

            <div class="form-group">
                <label for="db_host">数据库主机:</label>
                <input type="text" id="db_host" name="db_host" value="localhost" required>
            </div>
            
            <div class="form-group">
                <label for="db_user">数据库用户名:</label>
                <input type="text" id="db_user" name="db_user" required>
            </div>
            
            <div class="form-group">
                <label for="db_pass">数据库密码:</label>
                <input type="password" id="db_pass" name="db_pass">
            </div>
            
            <div class="form-group">
                <label for="db_name">数据库名称:</label>
                <input type="text" id="db_name" name="db_name" required>
            </div>
            
            <div class="actions">
                <a href="?step=2" class="btn btn-prev">← 上一步</a>
                <button type="submit" class="btn btn-next">下一步 →</button>
            </div>
        </form>
    </div>
</body>
</html>