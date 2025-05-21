<?php $_SESSION['install_step'] = 2;  ?>
<!DOCTYPE html>
<html>
<head>
    <title>SQLectric数据库安装向导 - 欢迎</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        h1 { color: #333; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 5px; }
        .actions { margin-top: 20px; text-align: right; }
        .btn { padding: 10px 15px; background: #4CAF50; color: white; border: none; border-radius: 4px; cursor: pointer; }
    </style>
</head>

<body>
    <h1>欢迎使用 SQLectric 数据库安装向导</h1>
    <div class="content">
        <p>这将引导您完成系统的数据库安装过程。</p>
        <p>安装过程包括以下步骤：</p>
        <ol>
            <li>当前位置</li>
            <li>检查系统环境</li>
            <li>配置数据库信息</li>
            <li>建立管理员账户</li>
            <li>完工咯</li>
        </ol>
    </div>
    <div class="actions">
        <form method="post" action="?step=2">
            <input type="hidden" name="welcome_complete" value="1">
            <button type="submit" class="btn">开始 →</button>
        </form>
    </div>
</body>
</html>