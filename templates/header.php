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
    <link rel="icon" type="image/x-icon" href="assets/images/favicon.ico">
    <meta name="theme-color" content="#0a0e27">
    <style>
/* Quicksand Font Override for Live Dashboard */
body, .nav-text, .card-title, .metric-subtitle, .stat-label, .info-item label, 
.data-table td, .category-header h4, .section-subtitle, p, span, div {
    font-family: 'Quicksand', sans-serif !important;
}

/* Keep Orbitron for specific futuristic elements */
.sidebar-title, .header-title, .section-title, .metric, .stat-value, 
.nav-section-title, .stat-number {
    font-family: 'Orbitron', monospace !important;
}

/* Enhanced readability with Quicksand */
.nav-text {
    font-weight: 500;
}

.card-title {
    font-weight: 600;
    letter-spacing: 0.5px;
}

.metric-subtitle {
    font-weight: 400;
}

.data-table th {
    font-family: 'Quicksand', sans-serif !important;
    font-weight: 700;
}

.data-table td {
    font-family: 'Quicksand', sans-serif !important;
    font-weight: 400;
}

.info-item label {
    font-weight: 600;
}

.info-item span {
    font-weight: 500;
}

/* Button text with Quicksand */
.btn, .btn-secondary {
    font-family: 'Quicksand', sans-serif !important;
    font-weight: 600;
}

/* Status and badge text */
.status-badge, .badge, .nav-badge {
    font-family: 'Quicksand', sans-serif !important;
    font-weight: 600;
}

/* Warning and error text */
.warning, .error, .info {
    font-family: 'Quicksand', sans-serif !important;
}

.warning h4, .error h2, .info h4 {
    font-family: 'Orbitron', monospace !important;
}

/* Table headers keep Quicksand but with proper weight */
.data-table th {
    font-family: 'Quicksand', sans-serif !important;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 1px;
}

/* Category cards */
.category-card {
    font-family: 'Quicksand', sans-serif !important;
}

.category-stat .stat-label {
    font-family: 'Quicksand', sans-serif !important;
    font-weight: 500;
}

/* System status */
.status-label, .status-value {
    font-family: 'Quicksand', sans-serif !important;
}

.status-label {
    font-weight: 600;
}

.status-value {
    font-weight: 700;
}

/* Footer text */
.footer-content, .footer-bottom {
    font-family: 'Quicksand', sans-serif !important;
}

/* Enhanced contrast for better readability */
.nav-text, .card-title, .metric-subtitle {
    color: var(--light-color);
}

/* Improved button typography */
.btn {
    letter-spacing: 0.5px;
}

.btn-secondary {
    letter-spacing: 0.3px;
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
