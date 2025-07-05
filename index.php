<?php
/**
 * SOMAS Learning Analytics Dashboard
 * Main entry point
 */

// Load configuration
require_once 'config/config.php';

// Load classes
require_once 'classes/MoodleApiClient.php';
require_once 'classes/AnalyticsService.php';

// Clear cache if requested
if (isset($_GET['refresh'])) {
    session_destroy();
    session_start();
    header('Location: index.php');
    exit;
}

try {
    // Initialize API client
    $apiClient = new MoodleApiClient(MOODLE_URL, MOODLE_TOKEN, MOODLE_REST_FORMAT);
    
    // Initialize analytics service
    $analyticsService = new AnalyticsService($apiClient);
    
    // Fetch dashboard data
    $overview = $analyticsService->getDashboardOverview();
    $userStats = $analyticsService->getUserStatistics();
    $courseAnalytics = $analyticsService->getCourseAnalytics();
    $pdcAnalytics = $analyticsService->getPDCCourseAnalytics();
    $systemHealth = $analyticsService->getSystemHealth();
    
    // Include header
    include 'templates/header.php';
    
    // Include dashboard template
    include 'templates/dashboard.php';
    
    // Include footer
    include 'templates/footer.php';
    
} catch (Exception $e) {
    // Include header for error page
    include 'templates/header.php';
    
    echo '<div class="error">';
    echo '<h2>❌ Error Loading SOMAS Analytics Dashboard</h2>';
    echo '<p><strong>Error Details:</strong> ' . htmlspecialchars($e->getMessage()) . '</p>';
    
    echo '<h3>🔧 Troubleshooting Steps:</h3>';
    echo '<ol>';
    echo '<li>Check your internet connection</li>';
    echo '<li>Verify SOMAS Moodle site is accessible: <a href="' . MOODLE_URL . '" target="_blank">' . MOODLE_URL . '</a></li>';
    echo '<li>Run the connection test: <a href="setup/verify_connection.php">Test API Connection</a></li>';
    echo '<li>Check your web service token configuration</li>';
    echo '<li>Contact your SOMAS administrator if the problem persists</li>';
    echo '</ol>';
    
    echo '<div class="info" style="margin-top: 2rem;">';
    echo '<h4>🆘 Quick Actions:</h4>';
    echo '<a href="setup/verify_connection.php" class="btn-secondary" style="margin-right: 1rem;">🔧 Test Connection</a>';
    echo '<a href="?refresh=1" class="btn-secondary">🔄 Try Again</a>';
    echo '</div>';
    
    echo '</div>';
    
    // Include footer
    include 'templates/footer.php';
}
?>
