<?php
// 安装向导系统 - 数据库安装程序
session_start();

define('INSTALL_ROOT', dirname(__FILE__));

// 安全检查 - 防止直接访问视图文件
// 初始化session安装步骤
if (!isset($_SESSION['install_step'])) {
    $_SESSION['install_step'] = 1;
}

$allowed_steps = [1, 2, 3, 4, 5];
$request_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// 强制顺序访问
if ($request_step > $_SESSION['install_step']) {
    header('Location: ?step=' . $_SESSION['install_step']);
    exit;
} else if ($_SESSION['install_step'] >= 1 && $request_step <= $_SESSION['install_step']) {
    // 允许回退到之前的步骤
    $_SESSION['install_step'] = $request_step;
}

if (!in_array($request_step, $allowed_steps)) {
    header('Location: ?step=1');
    exit;
}

// 第一步：处理当前步骤
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

// 第二步：检查是否已安装
if (file_exists(INSTALL_ROOT . '/config/install.lock') && $step != 5) {//step5为完成页面
    die('请勿重复安装<br>如果需要重新安装，请删除 config/install.lock 文件');
}

// 第三步：路由到不同安装步骤
switch ($step) {
    case 1:
        include 'views/welcome.php';
        break;
    case 2:
        include 'views/check_requirements.php';
        break;
    case 3:
        include 'views/database_setup.php';
        break;
    case 4:
        include 'views/admin_setup.php';
        break;
    case 5:
        include 'views/complete.php';
        break;
    default:
        header('Location: ?step=1');
        exit;
}