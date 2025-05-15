<!DOCTYPE html>
<html>
<head>
    <title>数据库安装向导 - 环境检查</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #333; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .check-item { margin: 10px 0; padding: 10px; border-radius: 4px; }
        .success { background: #dff0d8; color: #3c763d; }
        .error { background: #f2dede; color: #a94442; }
        .actions { margin-top: 20px; display: flex; justify-content: space-between; }
        .btn { padding: 10px 15px; border: none; border-radius: 4px; cursor: pointer; }
        .btn-next { background: #4CAF50; color: white; }
        .btn-prev { background: #f5f5f5; color: #333; }
    </style>
</head>
<body>
    <h1>系统环境检查</h1>
    <div class="content">
        <?php
        $allPassed = true;
        
        // PHP版本检查
        $phpVersion = phpversion();
        $phpPassed = version_compare($phpVersion, '7.0.0', '>=');
        $allPassed = $allPassed && $phpPassed;
        ?>
        <div class="check-item <?php echo $phpPassed ? 'success' : 'error'; ?>">
            PHP版本 ≥ 7.0: <?php echo $phpPassed ? "✔ 通过 ($phpVersion)" : "✘ 失败 (当前: $phpVersion)"; ?>
        </div>

        <?php
        // MySQL扩展检查
        $mysqlPassed = extension_loaded('mysqli');
        $allPassed = $allPassed && $mysqlPassed;
        ?>
        <div class="check-item <?php echo $mysqlPassed ? 'success' : 'error'; ?>">
            MySQLi扩展: <?php echo $mysqlPassed ? "✔ 已安装" : "✘ 未安装"; ?>
        </div>

        <?php
        // 配置文件目录可写
        $configWritable = is_writable(INSTALL_ROOT . '/config');
        $allPassed = $allPassed && $configWritable;
        ?>
        <div class="check-item <?php echo $configWritable ? 'success' : 'error'; ?>">
            config目录可写: <?php echo $configWritable ? "✔ 可写" : "✘ 不可写"; ?>
        </div>

        <?php if (!$allPassed): ?>
            <div class="error" style="padding: 10px; margin-top: 15px;">
                请解决所有错误检查项后再继续安装
            </div>
        <?php endif; ?>
    </div>

    <div class="actions">
        <a href="?step=1" class="btn btn-prev">← 上一步</a>
        <?php if ($allPassed): ?>
            <a href="?step=3" class="btn btn-next">下一步 →</a>
        <?php else: ?>
            <button class="btn btn-next" disabled>下一步 →</button>
        <?php endif; ?>
    </div>
</body>
</html>