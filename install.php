<?php
// 安装向导系统 - 数据库安装程序
session_start();

define('INSTALL_ROOT', dirname(__FILE__));

// 安全检查 - 防止直接访问视图文件
$allowed_steps = [1, 2, 3, 4, 5];
$request_step = isset($_GET['step']) ? (int)$_GET['step'] : 1;
if (!in_array($request_step, $allowed_steps)) {
    header('Location: ?step=1');
    exit;
}

// 第一步：检查是否已安装
if (file_exists(INSTALL_ROOT . '/config/database.php')) {
    die('请勿重复安装<br>如果需要重新安装，请删除 config/database.php 文件');
}

// // 检查是否跳过了步骤
// if (($request_step > 2 && empty($_SESSION['db_config']))) {
//     header('Location: ?step=1');
//     exit;
// }

// 第二步：处理当前步骤
$step = isset($_GET['step']) ? (int)$_GET['step'] : 1;

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