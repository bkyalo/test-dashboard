<?php
/**
 * Moodle Web Service REST API Client
 */
class MoodleApiClient {
    private $baseUrl;
    private $token;
    private $format;
    
    public function __construct($url, $token, $format = 'json') {
        $this->baseUrl = rtrim($url, '/') . '/webservice/rest/server.php';
        $this->token = $token;
        $this->format = $format;
    }
    
    /**
     * Make API call to Moodle
     */
    public function call($function, $params = []) {
        $url = $this->baseUrl;
        
        $postData = [
            'wstoken' => $this->token,
            'wsfunction' => $function,
            'moodlewsrestformat' => $this->format
        ];
        
        // Add function parameters
        foreach ($params as $key => $value) {
            $postData[$key] = $value;
        }
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_error($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        if ($httpCode !== 200) {
            throw new Exception('HTTP Error: ' . $httpCode);
        }
        
        $data = json_decode($response, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('JSON Decode Error: ' . json_last_error_msg());
        }
        
        // Check for Moodle API errors
        if (isset($data['exception'])) {
            throw new Exception('Moodle API Error: ' . $data['message']);
        }
        
        return $data;
    }
    
    /**
     * Get site information
     */
    public function getSiteInfo() {
        return $this->call('core_webservice_get_site_info');
    }
    
    /**
     * Get all users - with fallback for restricted access
     */
    public function getUsers($criteria = []) {
        try {
            // First try without any criteria (most permissive)
            if (empty($criteria)) {
                return $this->call('core_user_get_users');
            }
            
            // If criteria provided, try with criteria
            $params = ['criteria' => $criteria];
            return $this->call('core_user_get_users', $params);
            
        } catch (Exception $e) {
            // If the above fails, try alternative approaches
            if (strpos($e->getMessage(), 'Invalid parameter') !== false) {
                // Try with minimal criteria
                try {
                    return $this->call('core_user_get_users', [
                        'criteria' => [
                            [
                                'key' => 'deleted',
                                'value' => '0'
                            ]
                        ]
                    ]);
                } catch (Exception $e2) {
                    // Last resort - return empty structure
                    return ['users' => []];
                }
            }
            throw $e;
        }
    }
    
    /**
     * Get users by field - alternative method
     */
    public function getUsersByField($field = 'id', $values = [1]) {
        try {
            return $this->call('core_user_get_users_by_field', [
                'field' => $field,
                'values' => $values
            ]);
        } catch (Exception $e) {
            return [];
        }
    }

    /**
     * Get user count - lightweight alternative
     */
    public function getUserCount() {
        try {
            $users = $this->getUsers();
            return count($users['users'] ?? []);
        } catch (Exception $e) {
            // Fallback: try to get site info which might contain user count
            try {
                $siteInfo = $this->getSiteInfo();
                // Some Moodle versions include user count in site info
                return $siteInfo['usercount'] ?? 0;
            } catch (Exception $e2) {
                return 0;
            }
        }
    }
    
    /**
     * Get all courses
     */
    public function getCourses() {
        return $this->call('core_course_get_courses');
    }
    
    /**
     * Get courses with detailed information
     */
    public function getCoursesDetailed() {
        try {
            // Try to get courses with additional fields
            return $this->call('core_course_get_courses', [
                'options' => [
                    'ids' => []  // Empty array gets all courses
                ]
            ]);
        } catch (Exception $e) {
            // Fallback to basic course call
            return $this->getCourses();
        }
    }
    
    /**
     * Get course enrollments
     */
    public function getCourseEnrollments($courseId) {
        return $this->call('core_enrol_get_enrolled_users', ['courseid' => $courseId]);
    }
    
    /**
     * Get user's course completion status
     */
    public function getCourseCompletions($courseId) {
        return $this->call('core_completion_get_course_completion_status', ['courseid' => $courseId]);
    }
    
    /**
     * Get recent activities
     */
    public function getRecentActivities($limit = 10) {
        return $this->call('core_course_get_recent_courses', ['limit' => $limit]);
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($userId) {
        return $this->call('core_user_get_users_by_field', [
            'field' => 'id',
            'values' => [$userId]
        ]);
    }
    
    /**
     * Get course categories with error handling
     */
    public function getCourseCategories() {
        try {
            return $this->call('core_course_get_categories');
        } catch (Exception $e) {
            // Return empty array if categories can't be accessed
            return [];
        }
    }
}
?>
