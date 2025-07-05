<div class="dashboard">
    <!-- System Status Alert -->
    <?php if (isset($systemHealth)): ?>
    <div class="system-status">
        <div class="status-item">
            <i class="fas fa-heartbeat status-icon pulse"></i>
            <div>
                <div class="status-label">System Status</div>
                <div class="status-value">Online & Operational</div>
            </div>
        </div>
        <div class="status-item">
            <i class="fas fa-code-branch status-icon"></i>
            <div>
                <div class="status-label">Moodle Version</div>
                <div class="status-value"><?php echo htmlspecialchars($systemHealth['moodle_version']); ?></div>
            </div>
        </div>
        <div class="status-item">
            <i class="fas fa-database status-icon"></i>
            <div>
                <div class="status-label">Database</div>
                <div class="status-value"><?php echo htmlspecialchars($systemHealth['database_type']); ?></div>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- Overview Section -->
    <div id="overview" class="dashboard-section">
        <div class="cards-grid">
            <div class="card primary">
                <div class="card-header">
                    <i class="fas fa-users card-icon"></i>
                    <h3 class="card-title">Total Users</h3>
                </div>
                <div class="card-content">
                    <div class="metric"><?php echo number_format($overview['total_users']); ?></div>
                    <div class="metric-subtitle">Registered Learners</div>
                </div>
            </div>
            
            <div class="card success">
                <div class="card-header">
                    <i class="fas fa-user-check card-icon"></i>
                    <h3 class="card-title">Active Users</h3>
                </div>
                <div class="card-content">
                    <div class="metric"><?php echo number_format($overview['active_users']); ?></div>
                    <div class="metric-subtitle">Last 30 Days</div>
                </div>
            </div>
            
            <div class="card warning">
                <div class="card-header">
                    <i class="fas fa-book-open card-icon"></i>
                    <h3 class="card-title">Total Courses</h3>
                </div>
                <div class="card-content">
                    <div class="metric"><?php echo number_format($overview['total_courses']); ?></div>
                    <div class="metric-subtitle">Available Courses</div>
                </div>
            </div>
            
            <div class="card danger">
                <div class="card-header">
                    <i class="fas fa-user-graduate card-icon"></i>
                    <h3 class="card-title">Enrollments</h3>
                </div>
                <div class="card-content">
                    <div class="metric"><?php echo number_format($courseAnalytics['total_enrollments']); ?></div>
                    <div class="metric-subtitle">Course Enrollments</div>
                </div>
            </div>
        </div>
        
        <!-- Most Popular PDC Courses in Overview -->
        <?php if (!empty($pdcAnalytics['popular_pdc_courses'])): ?>
        <div class="section overview-pdc-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-star section-icon"></i>
                    Top 5 Popular PDC Courses
                </h2>
                <div class="section-actions">
                    <a href="#short-courses" onclick="showSection('short-courses')" class="btn-secondary">
                        <i class="fas fa-certificate"></i>
                        View All PDC Courses
                    </a>
                </div>
            </div>
            
            <div class="table-container" style="margin: 0;">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-certificate"></i> Course Name</th>
                            <th><i class="fas fa-tag"></i> Short Name</th>
                            <th><i class="fas fa-users"></i> Enrollments</th>
                            <th><i class="fas fa-trophy"></i> Completion Rate</th>
                            <th><i class="fas fa-external-link-alt"></i> Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($pdcAnalytics['popular_pdc_courses'], 0, 5) as $course): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($course['name']); ?></td>
                            <td><code><?php echo htmlspecialchars($course['shortname']); ?></code></td>
                            <td><span class="badge"><?php echo number_format($course['enrollments']); ?></span></td>
                            <td>
                                <?php if (isset($course['completion_rate']) && is_numeric($course['completion_rate'])): ?>
                                    <span class="completion-rate" data-rate="<?php echo $course['completion_rate']; ?>">
                                        <?php echo $course['completion_rate']; ?>%
                                    </span>
                                <?php else: ?>
                                    <span class="completion-rate-na">N/A</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <a href="<?php echo MOODLE_URL; ?>/course/view.php?id=<?php echo $course['id']; ?>" 
                                   target="_blank" 
                                   class="action-btn view-btn" 
                                   title="View Course">
                                    <i class="fas fa-external-link-alt"></i> View
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else: ?>
        <div class="section overview-pdc-section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-star section-icon"></i>
                    Top 5 Popular PDC Courses
                </h2>
                <div class="section-actions">
                    <a href="#short-courses" onclick="showSection('short-courses')" class="btn-secondary">
                        <i class="fas fa-certificate"></i>
                        View All PDC Courses
                    </a>
                </div>
            </div>
            
            <div class="info" style="margin: 1rem 0;">
                <p><strong>No PDC courses found with enrollments.</strong> This could mean:</p>
                <ul>
                    <li>No PDC courses have been created yet</li>
                    <li>PDC courses have no enrollments</li>
                    <li>Limited API access to course data</li>
                </ul>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- User Analytics Section -->
    <div id="users" class="dashboard-section">
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-chart-bar section-icon"></i>
                    User Activity Analytics
                </h2>
                <div class="section-actions">
                    <button class="btn-secondary" onclick="toggleSection('user-details')">
                        <i class="fas fa-eye"></i>
                        Toggle Details
                    </button>
                </div>
            </div>
            
            <?php if (isset($userStats['access_limited']) && $userStats['access_limited']): ?>
            <div class="warning" style="margin: 2rem;">
                <h4><i class="fas fa-exclamation-triangle"></i> Limited User Data Access</h4>
                <p>Your web service token has restricted access to detailed user information. Basic user count is available, but detailed analytics require additional permissions.</p>
                <br>
                <strong>To enable full user analytics:</strong>
                <ol>
                    <li>Contact your Moodle administrator</li>
                    <li>Request additional capabilities for your web service user</li>
                    <li>Ensure the web service can access user profiles and activity data</li>
                </ol>
            </div>
            <?php endif; ?>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-calendar-week stat-icon"></i>
                        <div class="stat-label">Active Last Week</div>
                    </div>
                    <div class="stat-value">
                        <?php 
                        if (isset($userStats['access_limited']) && $userStats['access_limited']) {
                            echo 'N/A';
                        } else {
                            echo number_format($userStats['active_last_week']); 
                        }
                        ?>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-calendar-alt stat-icon"></i>
                        <div class="stat-label">Active Last Month</div>
                    </div>
                    <div class="stat-value">
                        <?php 
                        if (isset($userStats['access_limited']) && $userStats['access_limited']) {
                            echo 'N/A';
                        } else {
                            echo number_format($userStats['active_last_month']); 
                        }
                        ?>
                    </div>
                </div>
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-user-times stat-icon"></i>
                        <div class="stat-label">Never Logged In</div>
                    </div>
                    <div class="stat-value">
                        <?php 
                        if (isset($userStats['access_limited']) && $userStats['access_limited']) {
                            echo 'N/A';
                        } else {
                            echo number_format($userStats['never_logged_in']); 
                        }
                        ?>
                    </div>
                </div>
            </div>
            
            <div id="user-details" class="collapsible-content">
                <?php if (isset($userStats['access_limited']) && $userStats['access_limited']): ?>
                <div class="info" style="margin: 2rem;">
                    <h4><i class="fas fa-info-circle"></i> User Data Access Information</h4>
                    <p><strong>Available Data:</strong> Basic user count from site information</p>
                    <p><strong>Restricted Data:</strong> User activity, login history, registration details</p>
                </div>
                <?php else: ?>
                <!-- Show full user details when access is available -->
                <?php if (!empty($userStats['recent_registrations'])): ?>
                <div class="data-section">
                    <h3><i class="fas fa-user-plus"></i> Recent Registrations (Last 30 Days)</h3>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> Name</th>
                                    <th><i class="fas fa-envelope"></i> Email</th>
                                    <th><i class="fas fa-calendar"></i> Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach (array_slice($userStats['recent_registrations'], 0, 10) as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo $user['date']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($userStats['top_active_users'])): ?>
                <div class="data-section">
                    <h3><i class="fas fa-trophy"></i> Most Active Users</h3>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-user"></i> Name</th>
                                    <th><i class="fas fa-envelope"></i> Email</th>
                                    <th><i class="fas fa-clock"></i> Last Access</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($userStats['top_active_users'] as $user): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($user['name']); ?></td>
                                    <td><?php echo htmlspecialchars($user['email']); ?></td>
                                    <td><?php echo $user['last_access']; ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Course Analytics Section -->
    <div id="courses" class="dashboard-section">
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-graduation-cap section-icon"></i>
                    Course Analytics
                </h2>
                <div class="section-actions">
                    <button class="btn-secondary" onclick="toggleSection('course-details')">
                        <i class="fas fa-eye"></i>
                        Toggle Details
                    </button>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-check-circle stat-icon"></i>
                        <div class="stat-label">Courses with Enrollments</div>
                    </div>
                    <div class="stat-value"><?php echo number_format($courseAnalytics['courses_with_enrollments']); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-chart-line stat-icon"></i>
                        <div class="stat-label">Average Enrollments</div>
                    </div>
                    <div class="stat-value"><?php echo $courseAnalytics['average_enrollments']; ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-inbox stat-icon"></i>
                        <div class="stat-label">Empty Courses</div>
                    </div>
                    <div class="stat-value"><?php echo number_format($courseAnalytics['empty_courses']); ?></div>
                </div>
            </div>

            <div id="course-details" class="collapsible-content">
                <div class="data-section">
                    <h3><i class="fas fa-star"></i> Most Popular Courses</h3>
                    
                    <?php if (empty($courseAnalytics['popular_courses'])): ?>
                    <div class="info" style="margin: 1rem 0;">
                        <p><strong>No course data available.</strong> This could be due to:</p>
                        <ul>
                            <li>No courses with enrollments</li>
                            <li>Limited API access to course enrollment data</li>
                            <li>All courses are empty or hidden</li>
                        </ul>
                    </div>
                    <?php else: ?>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-book"></i> Course Name</th>
                                    <th><i class="fas fa-tag"></i> Short Name</th>
                                    <th><i class="fas fa-users"></i> Enrollments</th>
                                    <th><i class="fas fa-folder"></i> Category</th>
                                    <th><i class="fas fa-eye"></i> Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courseAnalytics['popular_courses'] as $course): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($course['name']); ?></td>
                                    <td><code><?php echo htmlspecialchars($course['shortname']); ?></code></td>
                                    <td><span class="badge"><?php echo number_format($course['enrollments']); ?></span></td>
                                    <td>
                                        <span class="category-tag">
                                            <i class="fas fa-folder"></i>
                                            <?php echo htmlspecialchars($course['category'] ?? 'Uncategorized'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if ($course['visible']): ?>
                                            <span class="status-badge online">
                                                <i class="fas fa-eye"></i> Visible
                                            </span>
                                        <?php else: ?>
                                            <span class="status-badge offline">
                                                <i class="fas fa-eye-slash"></i> Hidden
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>

                <?php if (!empty($courseAnalytics['course_categories'])): ?>
                <div class="data-section">
                    <h3><i class="fas fa-sitemap"></i> Courses by Category</h3>
                    <div class="category-grid">
                        <?php foreach ($courseAnalytics['course_categories'] as $category => $data): ?>
                        <div class="category-card">
                            <div class="category-header">
                                <h4><i class="fas fa-folder-open"></i> <?php echo htmlspecialchars($category); ?></h4>
                            </div>
                            <div class="category-stats">
                                <div class="category-stat">
                                    <div class="stat-label">Courses</div>
                                    <div class="stat-number"><?php echo $data['count']; ?></div>
                                </div>
                                <div class="category-stat">
                                    <div class="stat-label">Enrollments</div>
                                    <div class="stat-number"><?php echo $data['enrollments']; ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Short Courses (PDC) Analytics Section -->
    <div id="short-courses" class="dashboard-section">
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-certificate section-icon"></i>
                    Short Courses (PDC) Analytics
                </h2>
                <div class="section-actions">
                    <button class="btn-secondary" onclick="toggleSection('pdc-details')">
                        <i class="fas fa-eye"></i>
                        Toggle Details
                    </button>
                </div>
            </div>
            
            <div class="stats-grid">
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-certificate stat-icon"></i>
                        <div class="stat-label">Total PDC Courses</div>
                    </div>
                    <div class="stat-value"><?php echo number_format($pdcAnalytics['total_pdc_courses']); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-users stat-icon"></i>
                        <div class="stat-label">PDC Enrollments</div>
                    </div>
                    <div class="stat-value"><?php echo number_format($pdcAnalytics['total_pdc_enrollments']); ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-chart-line stat-icon"></i>
                        <div class="stat-label">Avg PDC Enrollments</div>
                    </div>
                    <div class="stat-value"><?php echo $pdcAnalytics['average_pdc_enrollments']; ?></div>
                </div>
                <div class="stat-item">
                    <div class="stat-header">
                        <i class="fas fa-inbox stat-icon"></i>
                        <div class="stat-label">Empty PDC Courses</div>
                    </div>
                    <div class="stat-value"><?php echo number_format($pdcAnalytics['empty_pdc_courses']); ?></div>
                </div>
            </div>

            <div id="pdc-details" class="collapsible-content">
                <?php if ($pdcAnalytics['total_pdc_courses'] == 0): ?>
                <div class="info" style="margin: 2rem;">
                    <h4><i class="fas fa-info-circle"></i> No PDC Courses Found</h4>
                    <p>No courses found with shortnames starting with "PDC-". This could mean:</p>
                    <ul>
                        <li>No short courses have been created yet</li>
                        <li>Short courses use a different naming convention</li>
                        <li>Limited API access to course data</li>
                    </ul>
                </div>
                <?php else: ?>
                
                <div class="data-section">
                    <h3><i class="fas fa-list"></i> All PDC Courses</h3>
                    
                    <?php if (empty($pdcAnalytics['all_pdc_courses'])): ?>
                    <div class="info" style="margin: 1rem 0;">
                        <p><strong>No PDC course data available.</strong></p>
                    </div>
                    <?php else: ?>
                    
                    <!-- Pagination Controls -->
                    <div class="pagination-controls">
                        <div class="pagination-info">
                            <span>Showing <strong id="pdc-start">1</strong> to <strong id="pdc-end">10</strong> of <strong><?php echo count($pdcAnalytics['all_pdc_courses']); ?></strong> PDC courses</span>
                        </div>
                        <div class="pagination-buttons">
                            <button id="pdc-prev" class="pagination-btn" onclick="changePDCPage(-1)" disabled>
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <span class="pagination-current">
                                Page <strong id="pdc-current-page">1</strong> of <strong id="pdc-total-pages"><?php echo ceil(count($pdcAnalytics['all_pdc_courses']) / 10); ?></strong>
                            </span>
                            <button id="pdc-next" class="pagination-btn" onclick="changePDCPage(1)">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-certificate"></i> Course Name</th>
                                    <th><i class="fas fa-tag"></i> Short Name</th>
                                    <th><i class="fas fa-users"></i> Enrollments</th>
                                    <th><i class="fas fa-eye"></i> Status</th>
                                    <th><i class="fas fa-calendar"></i> Created</th>
                                    <th><i class="fas fa-external-link-alt"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody id="pdc-courses-table">
                                <!-- Populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Bottom Pagination -->
                    <div class="pagination-controls">
                        <div class="pagination-summary">
                            <span>Total PDC Courses: <strong><?php echo count($pdcAnalytics['all_pdc_courses']); ?></strong></span>
                        </div>
                        <div class="pagination-buttons">
                            <button class="pagination-btn" onclick="changePDCPage(-1)" id="pdc-prev-bottom" disabled>
                                <i class="fas fa-chevron-left"></i> Previous
                            </button>
                            <button class="pagination-btn" onclick="changePDCPage(1)" id="pdc-next-bottom">
                                Next <i class="fas fa-chevron-right"></i>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Hidden data for JavaScript -->
                    <script type="application/json" id="pdc-courses-data">
                        <?php echo json_encode($pdcAnalytics['all_pdc_courses']); ?>
                    </script>
                    
                    <?php endif; ?>
                </div>

                <?php if (!empty($pdcAnalytics['pdc_completion_rates'])): ?>
                <div class="data-section">
                    <h3><i class="fas fa-trophy"></i> PDC Course Completion Rates</h3>
                    <div class="table-container">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th><i class="fas fa-certificate"></i> Course</th>
                                    <th><i class="fas fa-tag"></i> Short Name</th>
                                    <th><i class="fas fa-users"></i> Enrollments</th>
                                    <th><i class="fas fa-check-circle"></i> Completions</th>
                                    <th><i class="fas fa-percentage"></i> Completion Rate</th>
                                    <th><i class="fas fa-external-link-alt"></i> Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pdcAnalytics['pdc_completion_rates'] as $completion): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($completion['course']); ?></td>
                                    <td><code><?php echo htmlspecialchars($completion['shortname']); ?></code></td>
                                    <td><?php echo number_format($completion['enrollments']); ?></td>
                                    <td><?php echo is_numeric($completion['completions']) ? number_format($completion['completions']) : 'N/A'; ?></td>
                                    <td>
                                        <?php if (is_numeric($completion['completion_rate'])): ?>
                                            <span class="completion-rate" data-rate="<?php echo $completion['completion_rate']; ?>">
                                                <?php echo $completion['completion_rate']; ?>%
                                            </span>
                                        <?php else: ?>
                                            <span class="completion-rate-na">N/A</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="<?php echo MOODLE_URL; ?>/course/view.php?id=<?php echo $completion['course_id'] ?? '#'; ?>" 
                                           target="_blank" 
                                           class="action-btn view-btn" 
                                           title="View Course">
                                            <i class="fas fa-external-link-alt"></i> View
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty($pdcAnalytics['empty_pdc_courses_list'])): ?>
<div class="data-section">
    <h3><i class="fas fa-exclamation-triangle"></i> PDC Courses with Zero Enrollments</h3>
    <div class="warning" style="margin: 1rem 0;">
        <p><strong>⚠️ Attention Required:</strong> The following PDC courses have no student enrollments and may need review.</p>
    </div>
    
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th><i class="fas fa-certificate"></i> Course Name</th>
                    <th><i class="fas fa-tag"></i> Short Name</th>
                    <th><i class="fas fa-eye"></i> Status</th>
                    <th><i class="fas fa-calendar"></i> Created</th>
                    <th><i class="fas fa-external-link-alt"></i> Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pdcAnalytics['empty_pdc_courses_list'] as $course): ?>
                <tr class="empty-course-row">
                    <td><?php echo htmlspecialchars($course['name']); ?></td>
                    <td><code><?php echo htmlspecialchars($course['shortname']); ?></code></td>
                    <td>
                        <?php if ($course['visible']): ?>
                            <span class="status-badge online">
                                <i class="fas fa-eye"></i> Visible
                            </span>
                        <?php else: ?>
                            <span class="status-badge offline">
                                <i class="fas fa-eye-slash"></i> Hidden
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php 
                        if ($course['created'] > 0) {
                            echo date('Y-m-d', $course['created']);
                        } else {
                            echo 'Unknown';
                        }
                        ?>
                    </td>
                    <td>
                        <a href="<?php echo MOODLE_URL; ?>/course/view.php?id=<?php echo $course['id']; ?>" 
                           target="_blank" 
                           class="action-btn view-btn" 
                           title="View Course">
                            <i class="fas fa-external-link-alt"></i> View
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    
    <div class="empty-courses-summary">
        <div class="summary-cards">
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fas fa-eye-slash"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">Hidden Courses</div>
                    <div class="summary-value">
                        <?php 
                        $hiddenCount = 0;
                        foreach ($pdcAnalytics['empty_pdc_courses_list'] as $course) {
                            if (!$course['visible']) $hiddenCount++;
                        }
                        echo $hiddenCount;
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fas fa-eye"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">Visible but Empty</div>
                    <div class="summary-value">
                        <?php 
                        $visibleEmptyCount = 0;
                        foreach ($pdcAnalytics['empty_pdc_courses_list'] as $course) {
                            if ($course['visible']) $visibleEmptyCount++;
                        }
                        echo $visibleEmptyCount;
                        ?>
                    </div>
                </div>
            </div>
            
            <div class="summary-card">
                <div class="summary-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="summary-content">
                    <div class="summary-label">Recently Created</div>
                    <div class="summary-value">
                        <?php 
                        $recentCount = 0;
                        $thirtyDaysAgo = time() - (30 * 24 * 60 * 60);
                        foreach ($pdcAnalytics['empty_pdc_courses_list'] as $course) {
                            if ($course['created'] > $thirtyDaysAgo) $recentCount++;
                        }
                        echo $recentCount;
                        ?>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="recommendations">
            <h4><i class="fas fa-lightbulb"></i> Recommendations</h4>
            <ul>
                <li><strong>Hidden Courses:</strong> Review if these should be published or archived</li>
                <li><strong>Visible Empty Courses:</strong> Consider marketing campaigns or content review</li>
                <li><strong>Recently Created:</strong> May need time to gain enrollments - monitor progress</li>
                <li><strong>Old Empty Courses:</strong> Consider archiving or significant content updates</li>
            </ul>
        </div>
    </div>
</div>
<?php endif; ?>

                <?php if (!empty($pdcAnalytics['pdc_course_categories'])): ?>
                <div class="data-section">
                    <h3><i class="fas fa-sitemap"></i> PDC Courses by Category</h3>
                    <div class="category-grid">
                        <?php foreach ($pdcAnalytics['pdc_course_categories'] as $category => $data): ?>
                        <div class="category-card pdc-category">
                            <div class="category-header">
                                <h4><i class="fas fa-certificate"></i> <?php echo htmlspecialchars($category); ?></h4>
                            </div>
                            <div class="category-stats">
                                <div class="category-stat">
                                    <div class="stat-label">PDC Courses</div>
                                    <div class="stat-number"><?php echo $data['count']; ?></div>
                                </div>
                                <div class="category-stat">
                                    <div class="stat-label">Enrollments</div>
                                    <div class="stat-number"><?php echo $data['enrollments']; ?></div>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- System Information -->
    <div id="system" class="dashboard-section">
        <?php if (isset($systemHealth)): ?>
        <div class="section">
            <div class="section-header">
                <h2 class="section-title">
                    <i class="fas fa-server section-icon"></i>
                    System Information
                </h2>
            </div>
            <div class="system-info-grid">
                <div class="info-item">
                    <label><i class="fas fa-globe"></i> Site Name</label>
                    <span><?php echo htmlspecialchars($overview['site_name']); ?></span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-code-branch"></i> Moodle Version</label>
                    <span><?php echo htmlspecialchars($systemHealth['moodle_version']); ?></span>
                </div>
                <div class="info-item">
                    <label><i class="fab fa-php"></i> PHP Version</label>
                    <span><?php echo htmlspecialchars($systemHealth['php_version']); ?></span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-database"></i> Database</label>
                    <span><?php echo htmlspecialchars($systemHealth['database_type']); ?></span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-link"></i> Site URL</label>
                    <span>
                        <a href="<?php echo htmlspecialchars($systemHealth['site_url']); ?>" target="_blank">
                            <?php echo htmlspecialchars($systemHealth['site_url']); ?>
                            <i class="fas fa-external-link-alt"></i>
                        </a>
                    </span>
                </div>
                <div class="info-item">
                    <label><i class="fas fa-clock"></i> Last Check</label>
                    <span><?php echo $systemHealth['last_check']; ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Set MOODLE_URL for JavaScript -->
<script>
    window.MOODLE_URL = "<?php echo MOODLE_URL; ?>";
</script>
