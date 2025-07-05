<?php
/**
 * SOMAS Analytics Dashboard Configuration
 */

// Moodle Configuration
define('MOODLE_URL', 'https://somas.snap.co.ke');
define('MOODLE_TOKEN', '94583525e069d65b8f982e94d5006c23');
define('MOODLE_REST_FORMAT', 'json');

// Dashboard Configuration
define('DASHBOARD_TITLE', 'SOMAS Analytics Dashboard');
define('CACHE_DURATION', 300); // 5 minutes cache
define('TIMEZONE', 'Africa/Nairobi'); // Kenya timezone

// Error Reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Session Configuration
session_start();

// Set timezone
date_default_timezone_set(TIMEZONE);
?>
