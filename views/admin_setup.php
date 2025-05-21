<!DOCTYPE html>
<html>
<head>
    <title>SQLectric数据库安装向导 - 管理员设置</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #333; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input { 
            width: 100%; padding: 8px; border: 1px solid #ddd; border-radius: 4px; 
        }
        .actions { margin-top: 20px; display: flex; justify-content: space-between; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-submit { background: #4CAF50; color: white; }
        .btn-prev { background: #f5f5f5; color: #333; }
        .success { color: #3c763d; background: #dff0d8; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
        .error { color: #a94442; background: #f2dede; padding: 10px; border-radius: 4px; margin-bottom: 15px; }
    </style>
</head>

<body>
    <h1>建立管理员账户</h1>
    <div class="content">
        <?php if ($_SERVER['REQUEST_METHOD'] === 'POST') { ?>
            <?php
            // 获取数据库配置
            $db_config = $_SESSION['db_config'];
            $conn = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
            if ($conn->connect_error) {
                die('<div class="error">数据库连接失败: ' . $conn->connect_error . '</div>');
            }
            
            // 执行SQL脚本
            $sql_file = INSTALL_ROOT . '/views/install.sql';
            if (!file_exists($sql_file)) {
                die('<div class="error">安装脚本文件（view/install.sql）不存在</div>');
            } 
            
            // 添加表前缀处理
            $sql = file_get_contents($sql_file);
            $sql = str_replace('{{prefix}}', $db_config['prefix'], $sql);
            // 日志输出SQL检查替换结果
            file_put_contents(INSTALL_ROOT . '/install.log', "Executing SQL:\n" . $sql, FILE_APPEND);
            
            if ($conn->multi_query($sql) === false) { ?>
                <div class="error">
                    数据库初始化失败: <?php echo $conn->error; ?>
                </div>
            <?php } else {
                // 创建管理员账户
                $username = $_POST['admin_user'];
                $password = password_hash($_POST['admin_pass'], PASSWORD_DEFAULT);
                $email = $_POST['admin_email'];
                
                // 获取数据库配置并重新连接
                $db_config = $_SESSION['db_config'];
                $conn = new mysqli($db_config['host'], $db_config['user'], $db_config['pass'], $db_config['name']);
                if ($conn->connect_error) {
                    die("<div class='error'>数据库连接失败: " . $conn->connect_error . "</div>");
                }
                
                // 检查表是否存在
                $tables = $conn->query("SHOW TABLES");
                if (!$tables) {
                    die("<div class='error'>数据库查询失败: " . $conn->error . "</div>");
                }
                file_put_contents(INSTALL_ROOT . '/install.log', "\nExisting Tables:\n" . print_r($tables->fetch_all(), true), FILE_APPEND);
                
                $tableCheck = $conn->query("SHOW TABLES LIKE '{$db_config['prefix']}administrators'");
                if ($tableCheck && $tableCheck->num_rows > 0) {
                    $stmt = $conn->prepare("INSERT INTO {$db_config['prefix']}administrators (username, password, email) VALUES (?, ?, ?)");
                    if ($stmt) {
                        $stmt->bind_param("sss", $username, $password, $email);
                        
                        if ($stmt->execute()) {
                            // 写入安装锁定文件
                            file_put_contents(INSTALL_ROOT.'/config/install.lock', date('Y-m-d H:i:s'));
                            // 跳转到安装完成页面
                            $_SESSION['install_step'] = 5;
                            header('Location: ?step=5');
                            exit;
                        } else { ?>
                            <div class="error">
                                管理员账户创建失败: <?php echo $stmt->error; ?>
                            </div>
                        <?php
                        }
                        $stmt->close();
                    } else { ?>
                        <div class="error">
                            SQL预处理失败: <?php echo $conn->error; ?>
                        </div>
                    <?php }
                } elseif (!isset($error)) { ?>
                    <div class="error">
                        administrators表不存在，请检查SQL脚本是否执行成功
                    </div>
                <?php } else {
                    // 保存数据库配置
                    $config_content = "<?php\n";
                    $config_content .= "define('DB_HOST', '{$db_config['host']}');\n";
                    $config_content .= "define('DB_USER', '{$db_config['user']}');\n";
                    $config_content .= "define('DB_PASS', '{$db_config['pass']}');\n";
                    $config_content .= "define('DB_NAME', '{$db_config['name']}');\n";
                    $config_content .= "define('DB_PREFIX', '{$db_config['prefix']}');\n";
                    file_put_contents(INSTALL_ROOT . '/config/datebase.php', $config_content);
                }
                $conn->close();
            } ?>
        <?php } ?>

        <form method="post">
            <div class="form-group">
                <label for="admin_user">管理员用户名:</label>
                <input type="text" id="admin_user" name="admin_user" required>
            </div>
            
            <div class="form-group">
                <label for="admin_pass">管理员密码:</label>
                <input type="password" id="admin_pass" name="admin_pass" required>
            </div>
            
            <div class="form-group">
                <label for="admin_email">管理员邮箱:</label>
                <input type="email" id="admin_email" name="admin_email" required>
            </div>
            
            <div class="actions">
                <a href="?step=3" class="btn btn-prev">← 上一步</a>
                <button type="submit" class="btn btn-submit">完成安装</button>
            </div>
        </form>
    </div>
</body>
</html>