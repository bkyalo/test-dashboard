<div class="dashboard">
    <!-- System Status Alert -->
    <?php if (isset($systemHealth)): ?>
    <div class="system-status">
        <div class="status-item">
            <span class="status-label">System Status:</span>
            <span class="status-value online">🟢 Online</span>
        </div>
        <div class="status-item">
            <span class="status-label">Moodle Version:</span>
            <span class="status-value"><?php echo htmlspecialchars($systemHealth['moodle_version']); ?></span>
        </div>
    </div>
    <?php endif; ?>

    <!-- Overview Cards -->
    <div class="cards-grid">
        <div class="card primary">
            <div class="card-icon">👥</div>
            <div class="card-content">
                <h3>Total Users</h3>
                <div class="metric"><?php echo number_format($overview['total_users']); ?></div>
                <div class="metric-subtitle">Registered learners</div>
            </div>
        </div>
        
        <div class="card success">
            <div class="card-icon">✅</div>
            <div class="card-content">
                <h3>Active Users</h3>
                <div class="metric"><?php echo number_format($overview['active_users']); ?></div>
                <div class="metric-subtitle">Last 30 days</div>
            </div>
        </div>
        
        <div class="card info">
            <div class="card-icon">📚</div>
            <div class="card-content">
                <h3>Total Courses</h3>
                <div class="metric"><?php echo number_format($overview['total_courses']); ?></div>
                <div class="metric-subtitle">Available courses</div>
            </div>
        </div>
        
        <div class="card warning">
            <div class="card-icon">🎓</div>
            <div class="card-content">
                <h3>Total Enrollments</h3>
                <div class="metric"><?php echo number_format($courseAnalytics['total_enrollments']); ?></div>
                <div class="metric-subtitle">Course enrollments</div>
            </div>
        </div>
    </div>

    <!-- User Activity Section -->
    <div class="section">
        <div class="section-header">
            <h2>👥 User Activity Analytics</h2>
            <div class="section-actions">
                <button class="btn-secondary" onclick="toggleSection('user-details')">Toggle Details</button>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <label>Active Last Week:</label>
                    <span class="stat-value"><?php echo number_format($userStats['active_last_week']); ?></span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">📈</div>
                <div class="stat-content">
                    <label>Active Last Month:</label>
                    <span class="stat-value"><?php echo number_format($userStats['active_last_month']); ?></span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">⚠️</div>
                <div class="stat-content">
                    <label>Never Logged In:</label>
                    <span class="stat-value"><?php echo number_format($userStats['never_logged_in']); ?></span>
                </div>
            </div>
        </div>
        
        <div id="user-details" class="collapsible-content">
            <?php if (!empty($userStats['recent_registrations'])): ?>
            <div class="data-section">
                <h3>📝 Recent Registrations (Last 30 Days)</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Registration Date</th>
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
                <h3>🏆 Most Active Users</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Last Access</th>
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
        </div>
    </div>

    <!-- Course Analytics Section -->
    <div class="section">
        <div class="section-header">
            <h2>📚 Course Analytics</h2>
            <div class="section-actions">
                <button class="btn-secondary" onclick="toggleSection('course-details')">Toggle Details</button>
            </div>
        </div>
        
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-icon">📋</div>
                <div class="stat-content">
                    <label>Courses with Enrollments:</label>
                    <span class="stat-value"><?php echo number_format($courseAnalytics['courses_with_enrollments']); ?></span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">📊</div>
                <div class="stat-content">
                    <label>Average Enrollments:</label>
                    <span class="stat-value"><?php echo $courseAnalytics['average_enrollments']; ?></span>
                </div>
            </div>
            <div class="stat-item">
                <div class="stat-icon">📭</div>
                <div class="stat-content">
                    <label>Empty Courses:</label>
                    <span class="stat-value"><?php echo number_format($courseAnalytics['empty_courses']); ?></span>
                </div>
            </div>
        </div>

        <div id="course-details" class="collapsible-content">
            <?php if (!empty($courseAnalytics['popular_courses'])): ?>
            <div class="data-section">
                <h3>🏆 Most Popular Courses</h3>
                <div class="table-container">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>Course Name</th>
                                <th>Short Name</th>
                                <th>Enrollments</th>
                                <th>Category</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courseAnalytics['popular_courses'] as $course): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($course['name']); ?></td>
                                <td><code><?php echo htmlspecialchars($course['shortname']); ?></code></td>
                                <td><span class="badge"><?php echo number_format($course['enrollments']); ?></span></td>
                                <td><?php echo htmlspecialchars($course['category']); ?></td>
                                <td>
                                    <?php if ($course['visible']): ?>
                                        <span class="status-badge online">Visible</span>
                                    <?php else: ?>
                                        <span class="status-badge offline">Hidden</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <?php endif; ?>

            <?php if (!empty($courseAnalytics['course_categories'])): ?>
            <div class="data-section">
                <h3>📂 Courses by Category</h3>
                <div class="category-grid">
                    <?php foreach ($courseAnalytics['course_categories'] as $category => $data): ?>
                    <div class="category-card">
                        <div class="category-header">
                            <h4><?php echo htmlspecialchars($category); ?></h4>
                        </div>
                        <div class="category-stats">
                            <div class="category-stat">
                                <span class="stat-label">Courses:</span>
                                <span class="stat-number"><?php echo $data['count']; ?></span>
                            </div>
                            <div class="category-stat">
                                <span class="stat-label">Enrollments:</span>
                                <span class="stat-number"><?php echo $data['enrollments']; ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- System Information -->
    <?php if (isset($systemHealth)): ?>
    <div class="section">
        <div class="section-header">
            <h2>🔧 System Information</h2>
        </div>
        <div class="system-info-grid">
            <div class="info-item">
                <label>Site Name:</label>
                <span><?php echo htmlspecialchars($overview['site_name']); ?></span>
            </div>
            <div class="info-item">
                <label>Moodle Version:</label>
                <span><?php echo htmlspecialchars($systemHealth['moodle_version']); ?></span>
            </div>
            <div class="info-item">
                <label>PHP Version:</label>
                <span><?php echo htmlspecialchars($systemHealth['php_version']); ?></span>
            </div>
            <div class="info-item">
                <label>Database:</label>
                <span><?php echo htmlspecialchars($systemHealth['database_type']); ?></span>
            </div>
            <div class="info-item">
                <label>Site URL:</label>
                <span><a href="<?php echo htmlspecialchars($systemHealth['site_url']); ?>" target="_blank"><?php echo htmlspecialchars($systemHealth['site_url']); ?></a></span>
            </div>
            <div class="info-item">
                <label>Last Check:</label>
                <span><?php echo $systemHealth['last_check']; ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
