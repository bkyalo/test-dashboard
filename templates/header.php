<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo DASHBOARD_TITLE; ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&family=Orbitron:wght@400;700;900&display=swap" rel="stylesheet">
    <!-- Font Awesome 5 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <meta name="theme-color" content="#0a0e27">
    <style>
/* ALL FONTS USE QUICKSAND - Complete Override */
* {
    font-family: 'Quicksand', sans-serif !important;
}

body, html, div, span, h1, h2, h3, h4, h5, h6, p, a, button, input, textarea, select, 
.nav-text, .card-title, .metric-subtitle, .stat-label, .info-item label, 
.data-table td, .data-table th, .category-header h4, .section-subtitle, 
.sidebar-title, .header-title, .section-title, .metric, .stat-value, 
.nav-section-title, .stat-number, .btn, .btn-secondary, .status-badge, 
.badge, .nav-badge, .warning, .error, .info, .category-card, 
.status-label, .status-value, .footer-content, .footer-bottom {
    font-family: 'Quicksand', sans-serif !important;
}

/* Font Weight Hierarchy with Quicksand */
.sidebar-title, .header-title {
    font-weight: 900 !important;
    letter-spacing: 2px;
    text-transform: uppercase;
}

.section-title {
    font-weight: 800 !important;
    letter-spacing: 1.5px;
}

.metric, .stat-value, .stat-number {
    font-weight: 700 !important;
    letter-spacing: 1px;
}

.nav-section-title {
    font-weight: 700 !important;
    letter-spacing: 1px;
    text-transform: uppercase;
}

.card-title {
    font-weight: 600 !important;
    letter-spacing: 0.5px;
    text-transform: uppercase;
}

.nav-text {
    font-weight: 500 !important;
}

.metric-subtitle, .stat-label {
    font-weight: 400 !important;
}

.data-table th {
    font-weight: 700 !important;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.data-table td {
    font-weight: 400 !important;
}

.info-item label {
    font-weight: 600 !important;
}

.info-item span {
    font-weight: 500 !important;
}

.btn, .btn-secondary {
    font-weight: 600 !important;
    letter-spacing: 0.5px;
}

.status-badge, .badge, .nav-badge {
    font-weight: 600 !important;
    letter-spacing: 0.3px;
}

.status-label {
    font-weight: 600 !important;
}

.status-value {
    font-weight: 700 !important;
}

/* Warning and error headings */
.warning h4, .error h2, .info h4 {
    font-weight: 700 !important;
    letter-spacing: 1px;
}

/* Enhanced readability */
.sidebar-subtitle {
    font-weight: 400 !important;
    letter-spacing: 0.5px;
}

.header-subtitle {
    font-weight: 400 !important;
    letter-spacing: 0.3px;
}

/* Category cards */
.category-stat .stat-label {
    font-weight: 500 !important;
}

/* Footer styling */
.footer-content h4 {
    font-weight: 600 !important;
}

.footer-content p, .footer-content li {
    font-weight: 400 !important;
}

.footer-bottom {
    font-weight: 500 !important;
}
</style>
</head>
<body>
    <!-- Mobile Menu Toggle -->
    <button class="mobile-menu-toggle" onclick="toggleSidebar()">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside class="sidebar" id="sidebar">
        <button class="sidebar-toggle" onclick="toggleSidebarCollapse()">
            <i class="fas fa-chevron-left"></i>
        </button>
        
        <div class="sidebar-header">
            <div class="sidebar-logo">
                <i class="fas fa-rocket"></i>
            </div>
            <h1 class="sidebar-title">SOMAS</h1>
            <p class="sidebar-subtitle">Analytics Dashboard</p>
        </div>
        
        <nav class="sidebar-nav">
            <div class="nav-section">
                <div class="nav-section-title">Dashboard</div>
                <div class="nav-item">
                    <a href="#overview" class="nav-link active" onclick="showSection('overview')">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Overview</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#users" class="nav-link" onclick="showSection('users')">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="nav-text">User Analytics</span>
                        <span class="nav-badge"><?php echo number_format($overview['total_users'] ?? 0); ?></span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="#courses" class="nav-link" onclick="showSection('courses')">
                        <i class="fas fa-graduation-cap nav-icon"></i>
                        <span class="nav-text">Course Analytics</span>
                        <span class="nav-badge"><?php echo number_format($overview['total_courses'] ?? 0); ?></span>
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">System</div>
                <div class="nav-item">
                    <a href="#system" class="nav-link" onclick="showSection('system')">
                        <i class="fas fa-server nav-icon"></i>
                        <span class="nav-text">System Info</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="setup/verify_connection.php" class="nav-link">
                        <i class="fas fa-plug nav-icon"></i>
                        <span class="nav-text">Test Connection</span>
                    </a>
                </div>
                <div class="nav-item">
                    <a href="?refresh=1" class="nav-link">
                        <i class="fas fa-sync-alt nav-icon"></i>
                        <span class="nav-text">Refresh Data</span>
                    </a>
                </div>
            </div>
            
            <div class="nav-section">
                <div class="nav-section-title">External</div>
                <div class="nav-item">
                    <a href="<?php echo MOODLE_URL; ?>" target="_blank" class="nav-link">
                        <i class="fas fa-external-link-alt nav-icon"></i>
                        <span class="nav-text">Moodle Site</span>
                    </a>
                </div>
            </div>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <header class="header">
            <div class="header-content">
                <div>
                    <h1 class="header-title">
                        <i class="fas fa-chart-line"></i>
                        Analytics Dashboard
                    </h1>
                    <p class="header-subtitle">Real-time insights for SOMAS Learning Platform</p>
                </div>
                <div class="header-actions">
                    <div class="header-time">
                        <i class="fas fa-clock"></i>
                        <span id="current-time"><?php echo date('Y-m-d H:i:s'); ?></span>
                    </div>
                    <a href="?refresh=1" class="btn btn-success">
                        <i class="fas fa-sync-alt"></i>
                        Refresh
                    </a>
                </div>
            </div>
        </header>
