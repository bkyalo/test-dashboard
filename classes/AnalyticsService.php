<?php
/**
 * Analytics Service for processing SOMAS Moodle data
 */
class AnalyticsService {
    private $apiClient;
    private $cache = [];
    
    public function __construct(MoodleApiClient $apiClient) {
        $this->apiClient = $apiClient;
    }
    
    /**
     * Get cached data or fetch from API
     */
    private function getCachedData($key, $callback, $ttl = 300) {
        $cacheKey = 'somas_analytics_' . $key;
        
        if (isset($_SESSION[$cacheKey]) && 
            isset($_SESSION[$cacheKey . '_time']) && 
            (time() - $_SESSION[$cacheKey . '_time']) < $ttl) {
            return $_SESSION[$cacheKey];
        }
        
        $data = $callback();
        $_SESSION[$cacheKey] = $data;
        $_SESSION[$cacheKey . '_time'] = time();
        
        return $data;
    }
    
    /**
     * Get dashboard overview data - with fallbacks
     */
    public function getDashboardOverview() {
        return $this->getCachedData('overview', function() {
            $siteInfo = $this->apiClient->getSiteInfo();
        
            // Try to get user count with fallbacks
            $totalUsers = 0;
            $activeUsers = 0;
        
            try {
                $users = $this->apiClient->getUsers();
                $userList = $users['users'] ?? [];
                $totalUsers = count($userList);
                $activeUsers = $this->countActiveUsers($userList);
            } catch (Exception $e) {
                // Fallback: try alternative user count method
                $totalUsers = $this->apiClient->getUserCount();
                $activeUsers = 0; // Can't calculate without user details
            }
        
            $courses = $this->apiClient->getCourses();
        
            return [
                'site_name' => $siteInfo['sitename'] ?? 'SOMAS Learning Platform',
                'moodle_version' => $siteInfo['release'] ?? 'Unknown',
                'total_users' => $totalUsers,
                'total_courses' => count($courses),
                'active_users' => $activeUsers,
                'last_updated' => date('Y-m-d H:i:s'),
                'user_access_limited' => $totalUsers > 0 && $activeUsers === 0
            ];
        });
    }
    
    /**
     * Get user statistics - with graceful degradation
     */
    public function getUserStatistics() {
        return $this->getCachedData('user_stats', function() {
            try {
                $users = $this->apiClient->getUsers();
                $userList = $users['users'] ?? [];
            
                // Full user analytics if we have access
                return $this->calculateFullUserStats($userList);
            
            } catch (Exception $e) {
                // Limited user analytics
                return [
                    'total' => $this->apiClient->getUserCount(),
                    'active_last_week' => 'N/A - Limited Access',
                    'active_last_month' => 'N/A - Limited Access',
                    'never_logged_in' => 'N/A - Limited Access',
                    'by_role' => [],
                    'recent_registrations' => [],
                    'top_active_users' => [],
                    'access_limited' => true,
                    'error_message' => 'Limited user data access due to API restrictions'
                ];
            }
        });
    }

    /**
     * Calculate full user statistics when we have access
     */
    private function calculateFullUserStats($userList) {
        $stats = [
            'total' => count($userList),
            'active_last_week' => 0,
            'active_last_month' => 0,
            'never_logged_in' => 0,
            'by_role' => [],
            'recent_registrations' => [],
            'top_active_users' => [],
            'access_limited' => false
        ];
    
        $weekAgo = time() - (7 * 24 * 60 * 60);
        $monthAgo = time() - (30 * 24 * 60 * 60);
    
        foreach ($userList as $user) {
            // Skip admin and guest users
            if (($user['id'] ?? 0) <= 2) continue;
            
            // Safely get user data with fallbacks
            $fullname = $user['fullname'] ?? $user['firstname'] . ' ' . $user['lastname'] ?? 'Unknown User';
            $email = $user['email'] ?? 'No email available';
            $lastaccess = $user['lastaccess'] ?? 0;
            $firstaccess = $user['firstaccess'] ?? 0;
        
            // Count active users
            if ($lastaccess > 0) {
                if ($lastaccess > $weekAgo) {
                    $stats['active_last_week']++;
                }
                if ($lastaccess > $monthAgo) {
                    $stats['active_last_month']++;
                
                    // Track top active users - only if we have proper data
                    if (!empty($fullname) && $fullname !== 'Unknown User') {
                        $stats['top_active_users'][] = [
                            'name' => $fullname,
                            'email' => $email,
                            'last_access' => date('Y-m-d H:i', $lastaccess)
                        ];
                    }
                }
            } else {
                $stats['never_logged_in']++;
            }
        
            // Recent registrations (last 30 days) - only if we have proper data
            if ($firstaccess > $monthAgo && !empty($fullname) && $fullname !== 'Unknown User') {
                $stats['recent_registrations'][] = [
                    'name' => $fullname,
                    'email' => $email,
                    'date' => date('Y-m-d', $firstaccess)
                ];
            }
        }
    
        // Sort top active users by last access
        usort($stats['top_active_users'], function($a, $b) {
            return strtotime($b['last_access']) - strtotime($a['last_access']);
        });
        $stats['top_active_users'] = array_slice($stats['top_active_users'], 0, 10);
    
        return $stats;
    }
    
    /**
     * Get course analytics
     */
    public function getCourseAnalytics() {
        return $this->getCachedData('course_analytics', function() {
            $courses = $this->apiClient->getCourses();
            $analytics = [
                'total_courses' => count($courses),
                'courses_with_enrollments' => 0,
                'average_enrollments' => 0,
                'popular_courses' => [],
                'course_categories' => [],
                'empty_courses' => 0,
                'total_enrollments' => 0
            ];
            
            $totalEnrollments = 0;
            $courseEnrollments = [];
            
            foreach ($courses as $course) {
                if (($course['id'] ?? 0) == 1) continue; // Skip site course
                
                try {
                    $enrollments = $this->apiClient->getCourseEnrollments($course['id']);
                    $enrollmentCount = count($enrollments);
                    
                    $analytics['total_enrollments'] += $enrollmentCount;
                    
                    if ($enrollmentCount > 0) {
                        $analytics['courses_with_enrollments']++;
                        $totalEnrollments += $enrollmentCount;
                        
                        // Fix category name access - try multiple possible fields
                        $categoryName = 'Uncategorized';
                        if (isset($course['categoryname']) && !empty($course['categoryname'])) {
                            $categoryName = $course['categoryname'];
                        } elseif (isset($course['category']) && !empty($course['category'])) {
                            $categoryName = $course['category'];
                        } elseif (isset($course['categoryid']) && $course['categoryid'] > 0) {
                            // Try to get category name from category ID
                            try {
                                $categories = $this->apiClient->getCourseCategories();
                                foreach ($categories as $cat) {
                                    if (($cat['id'] ?? 0) == $course['categoryid']) {
                                        $categoryName = $cat['name'] ?? 'Category ' . $course['categoryid'];
                                        break;
                                    }
                                }
                            } catch (Exception $e) {
                                // If category lookup fails, use category ID
                                $categoryName = 'Category ' . $course['categoryid'];
                            }
                        }
                        
                        $courseEnrollments[] = [
                            'id' => $course['id'] ?? 0,
                            'name' => $course['fullname'] ?? 'Unknown Course',
                            'shortname' => $course['shortname'] ?? 'N/A',
                            'enrollments' => $enrollmentCount,
                            'category' => $categoryName,
                            'visible' => $course['visible'] ?? 1
                        ];
                    } else {
                        $analytics['empty_courses']++;
                    }
                    
                    // Category statistics - use the same category name logic
                    if (!isset($analytics['course_categories'][$categoryName])) {
                        $analytics['course_categories'][$categoryName] = [
                            'count' => 0,
                            'enrollments' => 0
                        ];
                    }
                    $analytics['course_categories'][$categoryName]['count']++;
                    $analytics['course_categories'][$categoryName]['enrollments'] += $enrollmentCount;
                    
                } catch (Exception $e) {
                    // Skip courses we can't access
                    continue;
                }
            }
            
            if ($analytics['courses_with_enrollments'] > 0) {
                $analytics['average_enrollments'] = round($totalEnrollments / $analytics['courses_with_enrollments'], 1);
            }
            
            // Sort by enrollments and get top 10
            usort($courseEnrollments, function($a, $b) {
                return $b['enrollments'] - $a['enrollments'];
            });
            $analytics['popular_courses'] = array_slice($courseEnrollments, 0, 10);
            
            return $analytics;
        });
    }
    
    /**
     * Get PDC (Short Courses) analytics - courses with shortname starting with "PDC-"
     */
    public function getPDCCourseAnalytics() {
        return $this->getCachedData('pdc_course_analytics', function() {
            $allCourses = $this->apiClient->getCourses();
            $pdcCourses = [];
            
            // Filter courses with shortname starting with "PDC-"
            foreach ($allCourses as $course) {
                $shortname = $course['shortname'] ?? '';
                if (strpos($shortname, 'PDC-') === 0) {
                    $pdcCourses[] = $course;
                }
            }
            
            $analytics = [
                'total_pdc_courses' => count($pdcCourses),
                'pdc_courses_with_enrollments' => 0,
                'average_pdc_enrollments' => 0,
                'popular_pdc_courses' => [],
                'all_pdc_courses' => [],
                'empty_pdc_courses_list' => [],
                'pdc_course_categories' => [],
                'empty_pdc_courses' => 0,
                'total_pdc_enrollments' => 0,
                'pdc_completion_rates' => [],
                'pdc_enrollment_trends' => []
            ];
            
            if (empty($pdcCourses)) {
                return $analytics;
            }
            
            $totalEnrollments = 0;
            $courseEnrollments = [];
            $allPDCCourses = [];
            
            foreach ($pdcCourses as $course) {
                if (($course['id'] ?? 0) == 1) continue; // Skip site course
                
                try {
                    $enrollments = $this->apiClient->getCourseEnrollments($course['id']);
                    $enrollmentCount = count($enrollments);
                    
                    $analytics['total_pdc_enrollments'] += $enrollmentCount;
                    
                    if ($enrollmentCount > 0) {
                        $analytics['pdc_courses_with_enrollments']++;
                        $totalEnrollments += $enrollmentCount;
                        
                        // Get category name
                        $categoryName = 'Uncategorized';
                        if (isset($course['categoryname']) && !empty($course['categoryname'])) {
                            $categoryName = $course['categoryname'];
                        } elseif (isset($course['category']) && !empty($course['category'])) {
                            $categoryName = $course['category'];
                        } elseif (isset($course['categoryid']) && $course['categoryid'] > 0) {
                            try {
                                $categories = $this->apiClient->getCourseCategories();
                                foreach ($categories as $cat) {
                                    if (($cat['id'] ?? 0) == $course['categoryid']) {
                                        $categoryName = $cat['name'] ?? 'Category ' . $course['categoryid'];
                                        break;
                                    }
                                }
                            } catch (Exception $e) {
                                $categoryName = 'Category ' . $course['categoryid'];
                            }
                        }
                        
                        // Extract PDC course type from shortname (e.g., PDC-IT, PDC-BUS)
                        $pdcType = 'General';
                        $shortname = $course['shortname'] ?? '';
                        if (preg_match('/^PDC-([A-Z]+)/', $shortname, $matches)) {
                            $pdcType = $matches[1];
                        }
                        
                        $courseData = [
                            'id' => $course['id'] ?? 0,
                            'name' => $course['fullname'] ?? 'Unknown Course',
                            'shortname' => $course['shortname'] ?? 'N/A',
                            'enrollments' => $enrollmentCount,
                            'category' => $categoryName,
                            'pdc_type' => $pdcType,
                            'visible' => $course['visible'] ?? 1,
                            'startdate' => $course['startdate'] ?? 0,
                            'enddate' => $course['enddate'] ?? 0,
                            'created' => $course['timecreated'] ?? 0
                        ];
                        
                        // Add to all PDC courses list
                        $allPDCCourses[] = $courseData;
                        
                        // Try to get completion data
                        try {
                            $completions = $this->apiClient->getCourseCompletions($course['id']);
                            $completionCount = 0;
                            foreach ($completions as $completion) {
                                if (isset($completion['completed']) && $completion['completed']) {
                                    $completionCount++;
                                }
                            }
                            $completionRate = $enrollmentCount > 0 ? round(($completionCount / $enrollmentCount) * 100, 1) : 0;
                            $courseData['completion_rate'] = $completionRate;
                            $courseData['completions'] = $completionCount;
                            
                            $analytics['pdc_completion_rates'][] = [
                                'course' => $course['fullname'],
                                'shortname' => $course['shortname'],
                                'course_id' => $course['id'],
                                'enrollments' => $enrollmentCount,
                                'completions' => $completionCount,
                                'completion_rate' => $completionRate
                            ];
                        } catch (Exception $e) {
                            $courseData['completion_rate'] = 'N/A';
                            $courseData['completions'] = 'N/A';
                        }
                        
                    } else {
                        $analytics['empty_pdc_courses']++;
                        
                        // Add to empty courses list with details
                        $categoryName = 'Uncategorized';
                        if (isset($course['categoryname']) && !empty($course['categoryname'])) {
                            $categoryName = $course['categoryname'];
                        } elseif (isset($course['category']) && !empty($course['category'])) {
                            $categoryName = $course['category'];
                        } elseif (isset($course['categoryid']) && $course['categoryid'] > 0) {
                            try {
                                $categories = $this->apiClient->getCourseCategories();
                                foreach ($categories as $cat) {
                                    if (($cat['id'] ?? 0) == $course['categoryid']) {
                                        $categoryName = $cat['name'] ?? 'Category ' . $course['categoryid'];
                                        break;
                                    }
                                }
                            } catch (Exception $e) {
                                $categoryName = 'Category ' . $course['categoryid'];
                            }
                        }
                        
                        // Extract PDC course type from shortname
                        $pdcType = 'General';
                        $shortname = $course['shortname'] ?? '';
                        if (preg_match('/^PDC-([A-Z]+)/', $shortname, $matches)) {
                            $pdcType = $matches[1];
                        }
                        
                        $analytics['empty_pdc_courses_list'][] = [
                            'id' => $course['id'] ?? 0,
                            'name' => $course['fullname'] ?? 'Unknown Course',
                            'shortname' => $course['shortname'] ?? 'N/A',
                            'category' => $categoryName,
                            'pdc_type' => $pdcType,
                            'visible' => $course['visible'] ?? 1,
                            'startdate' => $course['startdate'] ?? 0,
                            'enddate' => $course['enddate'] ?? 0,
                            'created' => $course['timecreated'] ?? 0
                        ];
                    }
                    
                    // Category statistics for PDC courses
                    if (!isset($analytics['pdc_course_categories'][$categoryName])) {
                        $analytics['pdc_course_categories'][$categoryName] = [
                            'count' => 0,
                            'enrollments' => 0
                        ];
                    }
                    $analytics['pdc_course_categories'][$categoryName]['count']++;
                    $analytics['pdc_course_categories'][$categoryName]['enrollments'] += $enrollmentCount;
                    
                } catch (Exception $e) {
                    // Skip courses we can't access
                    continue;
                }
            }
            
            if ($analytics['pdc_courses_with_enrollments'] > 0) {
                $analytics['average_pdc_enrollments'] = round($totalEnrollments / $analytics['pdc_courses_with_enrollments'], 1);
            }
            
            // Sort by enrollments and get top 10
            usort($courseEnrollments, function($a, $b) {
                return $b['enrollments'] - $a['enrollments'];
            });
            $analytics['popular_pdc_courses'] = array_slice($courseEnrollments, 0, 10);
            
            // Sort completion rates
            usort($analytics['pdc_completion_rates'], function($a, $b) {
                $aRate = is_numeric($a['completion_rate']) ? $a['completion_rate'] : 0;
                $bRate = is_numeric($b['completion_rate']) ? $b['completion_rate'] : 0;
                return $bRate <=> $aRate;
            });
            $analytics['pdc_completion_rates'] = array_slice($analytics['pdc_completion_rates'], 0, 10);
            
            // Sort all PDC courses by name
            usort($allPDCCourses, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            $analytics['all_pdc_courses'] = $allPDCCourses;
            
            return $analytics;
        });
    }
    
    /**
     * Get system health metrics
     */
    public function getSystemHealth() {
        return $this->getCachedData('system_health', function() {
            $siteInfo = $this->apiClient->getSiteInfo();
            
            return [
                'moodle_version' => $siteInfo['release'] ?? 'Unknown',
                'php_version' => $siteInfo['phpversion'] ?? 'Unknown',
                'database_type' => $siteInfo['dbtype'] ?? 'Unknown',
                'site_url' => $siteInfo['siteurl'] ?? MOODLE_URL,
                'admin_email' => $siteInfo['adminemail'] ?? 'Not available',
                'last_check' => date('Y-m-d H:i:s')
            ];
        });
    }
    
    /**
     * Count active users (accessed in last 30 days)
     */
    private function countActiveUsers($users) {
        $monthAgo = time() - (30 * 24 * 60 * 60);
        $activeCount = 0;
        
        foreach ($users as $user) {
            if (($user['id'] ?? 0) <= 2) continue; // Skip admin/guest
            if (isset($user['lastaccess']) && $user['lastaccess'] > $monthAgo) {
                $activeCount++;
            }
        }
        
        return $activeCount;
    }
    
    /**
     * Clear all cached data
     */
    public function clearCache() {
        foreach ($_SESSION as $key => $value) {
            if (strpos($key, 'somas_analytics_') === 0) {
                unset($_SESSION[$key]);
            }
        }
    }
    
    /**
     * Debug function to check user data structure
     */
    public function debugUserData() {
        try {
            $users = $this->apiClient->getUsers();
            $userList = $users['users'] ?? [];
            
            if (!empty($userList)) {
                $sampleUser = $userList[0];
                error_log("Sample user data structure: " . print_r($sampleUser, true));
                return $sampleUser;
            }
            
            return null;
        } catch (Exception $e) {
            error_log("Debug user data error: " . $e->getMessage());
            return null;
        }
    }
}
?>
