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
     * Get all users
     */
    public function getUsers($criteria = []) {
        $params = [];
        if (!empty($criteria)) {
            $params['criteria'] = $criteria;
        }
        return $this->call('core_user_get_users', $params);
    }
    
    /**
     * Get all courses
     */
    public function getCourses() {
        return $this->call('core_course_get_courses');
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
     * Get course categories
     */
    public function getCourseCategories() {
        return $this->call('core_course_get_categories');
    }
}
?>
