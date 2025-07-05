</div>
    </main>
    
    <!-- Footer within main content area -->
    <footer class="footer">
        <div class="footer-container">
            <div class="footer-content">
                <div class="footer-section">
                    <div class="footer-logo">
                        <i class="fas fa-rocket"></i>
                        <h4>SOMAS Analytics Dashboard</h4>
                    </div>
                    <p class="footer-description">Real-time learning analytics for SOMAS educational platform. Empowering education through data-driven insights.</p>
                    <div class="footer-stats">
                        <div class="footer-stat">
                            <i class="fas fa-users"></i>
                            <span><?php echo number_format($overview['total_users'] ?? 0); ?> Users</span>
                        </div>
                        <div class="footer-stat">
                            <i class="fas fa-graduation-cap"></i>
                            <span><?php echo number_format($overview['total_courses'] ?? 0); ?> Courses</span>
                        </div>
                    </div>
                </div>
                
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul class="footer-links">
                        <li><a href="#overview" onclick="showSection('overview')">
                            <i class="fas fa-tachometer-alt"></i> Dashboard Overview
                        </a></li>
                        <li><a href="#users" onclick="showSection('users')">
                            <i class="fas fa-users"></i> User Analytics
                        </a></li>
                        <li><a href="#courses" onclick="showSection('courses')">
                            <i class="fas fa-graduation-cap"></i> Course Analytics
                        </a></li>
                        <li><a href="#system" onclick="showSection('system')">
                            <i class="fas fa-server"></i> System Information
                        </a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>External Resources</h4>
                    <ul class="footer-links">
                        <li><a href="<?php echo MOODLE_URL; ?>" target="_blank">
                            <i class="fas fa-external-link-alt"></i> SOMAS Moodle Site
                        </a></li>
                        <li><a href="setup/verify_connection.php">
                            <i class="fas fa-plug"></i> Test API Connection
                        </a></li>
                        <li><a href="?refresh=1">
                            <i class="fas fa-sync-alt"></i> Refresh Dashboard
                        </a></li>
                        <li><a href="README_SOMAS.md" target="_blank">
                            <i class="fas fa-book"></i> Documentation
                        </a></li>
                    </ul>
                </div>
                
                <div class="footer-section">
                    <h4>System Status</h4>
                    <div class="footer-system-info">
                        <div class="system-info-item">
                            <i class="fas fa-heartbeat status-icon pulse"></i>
                            <div>
                                <span class="status-label">System Status</span>
                                <span class="status-value online">Online</span>
                            </div>
                        </div>
                        <div class="system-info-item">
                            <i class="fas fa-clock"></i>
                            <div>
                                <span class="status-label">Timezone</span>
                                <span class="status-value"><?php echo TIMEZONE; ?></span>
                            </div>
                        </div>
                        <div class="system-info-item">
                            <i class="fas fa-database"></i>
                            <div>
                                <span class="status-label">Cache Duration</span>
                                <span class="status-value"><?php echo CACHE_DURATION; ?>s</span>
                            </div>
                        </div>
                        <div class="system-info-item">
                            <i class="fas fa-code-branch"></i>
                            <div>
                                <span class="status-label">Last Updated</span>
                                <span class="status-value" id="footer-last-updated"><?php echo date('H:i:s'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="footer-divider"></div>
            
            <div class="footer-bottom">
                <div class="footer-bottom-left">
                    <p>&copy; <?php echo date('Y'); ?> SOMAS Learning Analytics. All rights reserved.</p>
                    <p class="footer-version">Dashboard v2.0 | Futuristic Edition</p>
                </div>
                <div class="footer-bottom-right">
                    <div class="footer-actions">
                        <button class="footer-btn" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
                            <i class="fas fa-arrow-up"></i>
                            Back to Top
                        </button>
                        <button class="footer-btn" onclick="toggleDarkMode()">
                            <i class="fas fa-moon"></i>
                            Dark Mode
                        </button>
                        <button class="footer-btn" onclick="exportDashboardData()">
                            <i class="fas fa-download"></i>
                            Export Data
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="assets/js/dashboard.js"></script>
    <script>
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Update time every second
            setInterval(updateTime, 1000);
            setInterval(updateFooterTime, 1000);
            
            // Initialize tooltips and animations
            initializeAnimations();
            
            // Show overview section by default
            showSection('overview');
            
            // Initialize footer animations
            initializeFooterAnimations();
        });
        
        function updateTime() {
            const now = new Date();
            const timeString = now.toISOString().slice(0, 19).replace('T', ' ');
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
            }
        }
        
        function updateFooterTime() {
            const now = new Date();
            const timeString = now.toLocaleTimeString();
            const footerTimeElement = document.getElementById('footer-last-updated');
            if (footerTimeElement) {
                footerTimeElement.textContent = timeString;
            }
        }
        
        function initializeAnimations() {
            // Add entrance animations to cards
            const cards = document.querySelectorAll('.card');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
                card.classList.add('animate-in');
            });
        }
        
        function initializeFooterAnimations() {
            // Animate footer sections on scroll
            const footerSections = document.querySelectorAll('.footer-section');
            const observer = new IntersectionObserver((entries) => {
                entries.forEach((entry, index) => {
                    if (entry.isIntersecting) {
                        setTimeout(() => {
                            entry.target.classList.add('animate-in');
                        }, index * 100);
                    }
                });
            });
            
            footerSections.forEach(section => {
                observer.observe(section);
            });
        }
        
        function showSection(sectionId) {
            // Hide all sections
            const sections = document.querySelectorAll('.dashboard-section');
            sections.forEach(section => {
                section.style.display = 'none';
            });
            
            // Show selected section
            const targetSection = document.getElementById(sectionId);
            if (targetSection) {
                targetSection.style.display = 'block';
            }
            
            // Update active nav link
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.classList.remove('active');
            });
            
            const activeLink = document.querySelector(`[onclick="showSection('${sectionId}')"]`);
            if (activeLink) {
                activeLink.classList.add('active');
            }
            
            // Scroll to top
            window.scrollTo({top: 0, behavior: 'smooth'});
        }
        
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.toggle('open');
            overlay.classList.toggle('active');
        }
        
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.querySelector('.sidebar-overlay');
            
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }
        
        function toggleSidebarCollapse() {
            const sidebar = document.getElementById('sidebar');
            const icon = document.querySelector('.sidebar-toggle i');
            
            sidebar.classList.toggle('collapsed');
            
            if (sidebar.classList.contains('collapsed')) {
                icon.className = 'fas fa-chevron-right';
            } else {
                icon.className = 'fas fa-chevron-left';
            }
        }
        
        // Global function for toggling sections (called from PHP template)
        function toggleSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.classList.toggle('hidden');
                
                // Update button text
                const button = event.target;
                const icon = button.querySelector('i');
                if (section.classList.contains('hidden')) {
                    button.innerHTML = '<i class="fas fa-eye"></i> Show Details';
                } else {
                    button.innerHTML = '<i class="fas fa-eye-slash"></i> Hide Details';
                }
            }
        }
        
        // Footer specific functions
        function toggleDarkMode() {
            document.body.classList.toggle('light-mode');
            const btn = event.target.closest('.footer-btn');
            const icon = btn.querySelector('i');
            
            if (document.body.classList.contains('light-mode')) {
                icon.className = 'fas fa-sun';
                btn.innerHTML = '<i class="fas fa-sun"></i> Light Mode';
            } else {
                icon.className = 'fas fa-moon';
                btn.innerHTML = '<i class="fas fa-moon"></i> Dark Mode';
            }
        }
        
        function exportDashboardData() {
            // Create export notification
            const notification = document.createElement('div');
            notification.className = 'export-notification';
            notification.innerHTML = `
                <i class="fas fa-download"></i>
                <span>Preparing dashboard export...</span>
            `;
            document.body.appendChild(notification);
            
            // Simulate export process
            setTimeout(() => {
                notification.innerHTML = `
                    <i class="fas fa-check-circle"></i>
                    <span>Dashboard data exported successfully!</span>
                `;
                notification.classList.add('success');
                
                setTimeout(() => {
                    document.body.removeChild(notification);
                }, 3000);
            }, 2000);
        }
    </script>
    
    <style>
        /* Footer Styles - Updated to work within main content */
        .footer {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--darker-color) 100%);
            border: 1px solid var(--glass-border);
            border-radius: var(--border-radius);
            margin-top: 4rem;
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(20px);
            box-shadow: var(--shadow);
        }

        .footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--secondary-color), var(--info-color), var(--accent-color));
        }

        .footer-container {
            padding: 3rem 2rem 1rem;
        }
        
        .footer-content {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 3rem;
            margin-bottom: 2rem;
        }
        
        .footer-section {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease;
        }
        
        .footer-section.animate-in {
            opacity: 1;
            transform: translateY(0);
        }
        
        .footer-section h4 {
            font-family: 'Quicksand', sans-serif !important;
            font-weight: 700 !important;
            color: var(--secondary-color);
            margin-bottom: 1.5rem;
            font-size: 1.1rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .footer-logo i {
            font-size: 1.5rem;
            color: var(--secondary-color);
            text-shadow: var(--neon-glow);
        }
        
        .footer-logo h4 {
            margin: 0;
            font-size: 1.2rem;
        }
        
        .footer-description {
            font-family: 'Quicksand', sans-serif !important;
            color: var(--light-color);
            opacity: 0.8;
            line-height: 1.6;
            margin-bottom: 1.5rem;
            font-weight: 400;
        }
        
        .footer-stats {
            display: flex;
            gap: 1.5rem;
            flex-wrap: wrap;
        }
        
        .footer-stat {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(0, 212, 255, 0.1);
            padding: 0.5rem 1rem;
            border-radius: var(--border-radius);
            border: 1px solid rgba(0, 212, 255, 0.2);
        }
        
        .footer-stat i {
            color: var(--secondary-color);
            font-size: 0.9rem;
        }
        
        .footer-stat span {
            font-family: 'Quicksand', sans-serif !important;
            font-weight: 600;
            color: var(--light-color);
            font-size: 0.9rem;
        }
        
        .footer-links {
            list-style: none;
            padding: 0;
        }
        
        .footer-links li {
            margin-bottom: 0.8rem;
        }
        
        .footer-links a {
            font-family: 'Quicksand', sans-serif !important;
            color: var(--light-color);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.5rem 0;
            transition: var(--transition);
            font-weight: 500;
            opacity: 0.8;
        }
        
        .footer-links a:hover {
            color: var(--secondary-color);
            opacity: 1;
            transform: translateX(5px);
        }
        
        .footer-links a i {
            font-size: 0.9rem;
            width: 16px;
            text-align: center;
        }
        
        .footer-system-info {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        
        .system-info-item {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.8rem;
            background: rgba(0, 0, 0, 0.3);
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius);
            transition: var(--transition);
        }
        
        .system-info-item:hover {
            border-color: var(--secondary-color);
            background: rgba(0, 212, 255, 0.1);
        }
        
        .system-info-item i {
            color: var(--secondary-color);
            font-size: 1rem;
            width: 20px;
            text-align: center;
        }
        
        .system-info-item div {
            display: flex;
            flex-direction: column;
        }
        
        .system-info-item .status-label {
            font-family: 'Quicksand', sans-serif !important;
            font-size: 0.8rem;
            color: var(--light-color);
            opacity: 0.7;
            font-weight: 500;
        }
        
        .system-info-item .status-value {
            font-family: 'Quicksand', sans-serif !important;
            font-size: 0.9rem;
            color: var(--secondary-color);
            font-weight: 600;
        }
        
        .system-info-item .status-value.online {
            color: var(--success-color);
        }
        
        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--glass-border), transparent);
            margin: 2rem 0;
        }
        
        .footer-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 2rem;
            padding-top: 1rem;
        }
        
        .footer-bottom-left p {
            font-family: 'Quicksand', sans-serif !important;
            color: var(--light-color);
            margin: 0;
            opacity: 0.7;
            font-weight: 400;
        }
        
        .footer-version {
            font-size: 0.8rem !important;
            color: var(--secondary-color) !important;
            opacity: 0.8 !important;
            margin-top: 0.3rem !important;
            font-weight: 500 !important;
        }
        
        .footer-actions {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .footer-btn {
            background: rgba(255, 255, 255, 0.1);
            color: var(--light-color);
            border: 1px solid var(--glass-border);
            padding: 0.6rem 1.2rem;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-family: 'Quicksand', sans-serif !important;
            font-weight: 500;
            font-size: 0.9rem;
            transition: var(--transition);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .footer-btn:hover {
            background: rgba(0, 212, 255, 0.2);
            border-color: var(--secondary-color);
            color: var(--secondary-color);
            transform: translateY(-2px);
        }
        
        .footer-btn i {
            font-size: 0.8rem;
        }
        
        /* Export notification */
        .export-notification {
            position: fixed;
            top: 2rem;
            right: 2rem;
            background: linear-gradient(135deg, var(--info-color), var(--secondary-color));
            color: white;
            padding: 1rem 1.5rem;
            border-radius: var(--border-radius);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            z-index: 2000;
            box-shadow: var(--shadow-lg);
            animation: slideInRight 0.3s ease;
            font-family: 'Quicksand', sans-serif !important;
            font-weight: 600;
        }
        
        .export-notification.success {
            background: linear-gradient(135deg, var(--success-color), #00cc7a);
        }
        
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        
        /* Responsive footer */
        @media (max-width: 768px) {
            .footer-container {
                padding: 2rem 1rem 1rem;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            
            .footer-bottom {
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }
            
            .footer-actions {
                justify-content: center;
            }
            
            .footer-stats {
                justify-content: center;
            }
        }
        
        /* Hide sections by default */
        .dashboard-section {
            display: none;
        }
        
        .dashboard-section:first-child {
            display: block;
        }
    </style>
</body>
</html>
