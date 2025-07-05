<?php
/**
 * SOMAS Moodle API Connection Verification
 * Run this script to verify your Moodle API configuration
 */

// Load configuration
require_once '../config/config.php';
require_once '../classes/MoodleApiClient.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SOMAS API Connection Test</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px; }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .error { background: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .warning { background: #fff3cd; color: #856404; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .info { background: #cce5ff; color: #004085; padding: 15px; border-radius: 5px; margin: 20px 0; }
        code { background: #f8f9fa; padding: 2px 4px; border-radius: 3px; }
        .btn { background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; display: inline-block; margin: 10px 5px 0 0; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #1e7e34; }
    </style>
</head>
<body>

<h1>🔧 SOMAS Moodle API Connection Test</h1>
<hr>

<?php
try {
    // Initialize API client
    $apiClient = new MoodleApiClient(MOODLE_URL, MOODLE_TOKEN, MOODLE_REST_FORMAT);
    
    echo "<h2>Testing API Connection...</h2>";
    echo "<p><strong>Moodle URL:</strong> " . MOODLE_URL . "</p>";
    echo "<p><strong>API Endpoint:</strong> " . MOODLE_URL . "/webservice/rest/server.php</p>";
    echo "<p><strong>Token:</strong> " . substr(MOODLE_TOKEN, 0, 8) . "..." . substr(MOODLE_TOKEN, -4) . "</p>";
    
    // Test 1: Get site information
    echo "<h3>Test 1: Site Information</h3>";
    $siteInfo = $apiClient->getSiteInfo();
    
    if ($siteInfo) {
        echo "<div class='success'>";
        echo "<strong>✅ SUCCESS:</strong> Connected to SOMAS Moodle successfully!<br>";
        echo "<strong>Site Name:</strong> " . ($siteInfo['sitename'] ?? 'N/A') . "<br>";
        echo "<strong>Moodle Version:</strong> " . ($siteInfo['release'] ?? 'N/A') . "<br>";
        echo "<strong>Site URL:</strong> " . ($siteInfo['siteurl'] ?? 'N/A') . "<br>";
        echo "<strong>Admin Email:</strong> " . ($siteInfo['adminemail'] ?? 'N/A') . "<br>";
        echo "</div>";
    }
    
    // Test 2: Get users (limited)
    echo "<h3>Test 2: User Access (Detailed)</h3>";
    try {
        // Test 1: Basic user call
        echo "<h4>2a. Basic User API Call</h4>";
        $users = $apiClient->getUsers();
        $userCount = count($users['users'] ?? []);
        echo "<div class='success'>";
        echo "<strong>✅ SUCCESS:</strong> Basic user access works<br>";
        echo "<strong>Total Users Found:</strong> " . $userCount . "<br>";
        echo "</div>";
        
        // Test 2: User details access
        if ($userCount > 0) {
            echo "<h4>2b. User Details Access</h4>";
            $sampleUser = $users['users'][0] ?? null;
            if ($sampleUser && isset($sampleUser['email'])) {
                echo "<div class='success'>";
                echo "<strong>✅ SUCCESS:</strong> Can access detailed user information<br>";
                echo "<strong>Sample User:</strong> " . htmlspecialchars($sampleUser['fullname'] ?? 'N/A') . "<br>";
                echo "</div>";
            } else {
                echo "<div class='warning'>";
                echo "<strong>⚠️ WARNING:</strong> Limited user details access<br>";
                echo "Can get user count but not detailed information.";
                echo "</div>";
            }
        }
        
    } catch (Exception $e) {
        echo "<h4>2a. User API Diagnostics</h4>";
        echo "<div class='error'>";
        echo "<strong>❌ ERROR:</strong> " . htmlspecialchars($e->getMessage()) . "<br>";
        
        // Provide specific guidance based on error type
        if (strpos($e->getMessage(), 'Invalid parameter') !== false) {
            echo "<br><strong>🔧 Specific Issue:</strong> Invalid parameter in user API call<br>";
            echo "<strong>Likely Causes:</strong><br>";
            echo "• Web service user lacks 'moodle/user:viewalldetails' capability<br>";
            echo "• Web service user lacks 'moodle/user:viewdetails' capability<br>";
            echo "• User profile access is restricted in your Moodle configuration<br>";
            echo "<br><strong>Solutions:</strong><br>";
            echo "1. Go to Site Administration → Users → Permissions → Define roles<br>";
            echo "2. Edit your web service user role<br>";
            echo "3. Add these capabilities: moodle/user:viewalldetails, moodle/user:viewdetails<br>";
            echo "4. Or create a custom role with user viewing permissions<br>";
        }
        echo "</div>";
        
        // Test alternative user access
        echo "<h4>2b. Alternative User Count Method</h4>";
        try {
            $userCount = $apiClient->getUserCount();
            if ($userCount > 0) {
                echo "<div class='success'>";
                echo "<strong>✅ PARTIAL SUCCESS:</strong> Can get basic user count<br>";
                echo "<strong>User Count:</strong> " . $userCount . "<br>";
                echo "<strong>Note:</strong> Dashboard will work with limited user analytics<br>";
                echo "</div>";
            }
        } catch (Exception $e2) {
            echo "<div class='error'>";
            echo "<strong>❌ FAILED:</strong> Cannot access any user information<br>";
            echo "</div>";
        }
    }
    
    // Test 3: Get courses
    echo "<h3>Test 3: Course Access</h3>";
    try {
        $courses = $apiClient->getCourses();
        $courseCount = count($courses);
        echo "<div class='success'>";
        echo "<strong>✅ SUCCESS:</strong> Can access course data<br>";
        echo "<strong>Total Courses Found:</strong> " . $courseCount . "<br>";
        
        // Show first few courses
        if ($courseCount > 0) {
            echo "<strong>Sample Courses:</strong><br>";
            foreach (array_slice($courses, 0, 5) as $course) {
                if ($course['id'] != 1) { // Skip site course
                    echo "- " . htmlspecialchars($course['fullname']) . " (ID: " . $course['id'] . ")<br>";
                }
            }
        }
        echo "</div>";
    } catch (Exception $e) {
        echo "<div class='error'>";
        echo "<strong>❌ ERROR:</strong> Cannot access course data: " . htmlspecialchars($e->getMessage());
        echo "</div>";
    }
    
    // Test 4: Test enrollment access
    echo "<h3>Test 4: Enrollment Access</h3>";
    try {
        $courses = $apiClient->getCourses();
        $testCourse = null;
        
        // Find a course to test enrollments
        foreach ($courses as $course) {
            if ($course['id'] != 1) {
                $testCourse = $course;
                break;
            }
        }
        
        if ($testCourse) {
            $enrollments = $apiClient->getCourseEnrollments($testCourse['id']);
            echo "<div class='success'>";
            echo "<strong>✅ SUCCESS:</strong> Can access enrollment data<br>";
            echo "<strong>Test Course:</strong> " . htmlspecialchars($testCourse['fullname']) . "<br>";
            echo "<strong>Enrollments:</strong> " . count($enrollments) . "<br>";
            echo "</div>";
        } else {
            echo "<div class='warning'>";
            echo "<strong>⚠️ WARNING:</strong> No courses available to test enrollments";
            echo "</div>";
        }
    } catch (Exception $e) {
        echo "<div class='warning'>";
        echo "<strong>⚠️ WARNING:</strong> Limited enrollment access: " . htmlspecialchars($e->getMessage()) . "<br>";
        echo "Some enrollment features may not work properly.";
        echo "</div>";
    }
    
    // Test 5: Check required web service functions
    echo "<h3>Test 5: Required Web Service Functions</h3>";
    $requiredFunctions = [
        'core_webservice_get_site_info' => 'Get site information',
        'core_user_get_users' => 'Get user list',
        'core_course_get_courses' => 'Get course list',
        'core_enrol_get_enrolled_users' => 'Get course enrollments',
        'core_completion_get_course_completion_status' => 'Get completion status'
    ];
    
    echo "<p>The following functions should be enabled in your Moodle web service:</p>";
    echo "<ul>";
    foreach ($requiredFunctions as $function => $description) {
        echo "<li><code>" . $function . "</code> - " . $description . "</li>";
    }
    echo "</ul>";
    
    echo "<div class='info'>";
    echo "<h4>🎉 Connection Test Complete!</h4>";
    echo "<p>Your SOMAS Moodle API connection is working. You can now use the analytics dashboard.</p>";
    echo "<a href='../index.php' class='btn btn-success'>📊 Go to Analytics Dashboard</a>";
    echo "<a href='https://somas.snap.co.ke' class='btn' target='_blank'>🌐 Visit SOMAS Moodle</a>";
    echo "</div>";
    
    echo "<div class='info'>";
    echo "<h4>📋 Next Steps:</h4>";
    echo "<ol>";
    echo "<li>If all tests pass, your dashboard should work correctly</li>";
    echo "<li>If you see warnings, check your web service user capabilities in Moodle</li>";
    echo "<li>Ensure all required functions are enabled in your web service</li>";
    echo "<li>Contact your Moodle administrator if you encounter persistent issues</li>";
    echo "</ol>";
    echo "</div>";
    
} catch (Exception $e) {
    echo "<div class='error'>";
    echo "<h3>❌ CONNECTION FAILED</h3>";
    echo "<p><strong>Error:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    
    echo "<h4>🔧 Troubleshooting Steps:</h4>";
    echo "<ol>";
    echo "<li><strong>Check your Moodle URL:</strong> Ensure " . MOODLE_URL . " is accessible</li>";
    echo "<li><strong>Verify Web Services:</strong> Go to Site Administration → Server → Web services</li>";
    echo "<li><strong>Enable Web Services:</strong> Make sure 'Enable web services' is checked</li>";
    echo "<li><strong>Check Token:</strong> Verify your token is valid and not expired</li>";
    echo "<li><strong>User Capabilities:</strong> Ensure the web service user has required permissions</li>";
    echo "<li><strong>Protocol:</strong> Make sure REST protocol is enabled</li>";
    echo "<li><strong>Firewall:</strong> Check if your server can access " . MOODLE_URL . "</li>";
    echo "</ol>";
    
    echo "<div class='info'>";
    echo "<h4>🆘 Need Help?</h4>";
    echo "<p>If you continue to have issues:</p>";
    echo "<ul>";
    echo "<li>Check your Moodle error logs</li>";
    echo "<li>Contact your SOMAS system administrator</li>";
    echo "<li>Verify your web service token hasn't expired</li>";
    echo "</ul>";
    echo "</div>";
    echo "</div>";
}

echo "<hr>";
echo "<p><small>SOMAS Analytics Dashboard - Connection Verification Tool</small></p>";
?>

</body>
</html>
