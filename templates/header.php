<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo DASHBOARD_TITLE; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header-brand">
                <img src="assets/images/somas-logo.png" alt="SOMAS" class="logo" onerror="this.style.display='none'">
                <h1><?php echo DASHBOARD_TITLE; ?></h1>
            </div>
            <div class="header-info">
                <span class="last-updated">Last Updated: <?php echo date('Y-m-d H:i:s'); ?></span>
                <a href="?refresh=1" class="refresh-btn">🔄 Refresh Data</a>
                <a href="setup/verify_connection.php" class="test-btn">🔧 Test Connection</a>
            </div>
        </div>
    </header>
    <main class="main">
        <div class="container">
