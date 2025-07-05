</div>
    </main>
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h4>SOMAS Analytics Dashboard</h4>
                    <p>Real-time learning analytics for SOMAS educational platform</p>
                </div>
                <div class="footer-section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="<?php echo MOODLE_URL; ?>" target="_blank">Moodle Site</a></li>
                        <li><a href="setup/verify_connection.php">Test Connection</a></li>
                        <li><a href="?refresh=1">Refresh Data</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h4>System Info</h4>
                    <p>Timezone: <?php echo TIMEZONE; ?></p>
                    <p>Cache Duration: <?php echo CACHE_DURATION; ?>s</p>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> SOMAS Learning Analytics. All rights reserved.</p>
            </div>
        </div>
    </footer>
    
    <script src="assets/js/dashboard.js"></script>
    <script>
        // Initialize dashboard
        document.addEventListener('DOMContentLoaded', function() {
            // Update time every second
            setInterval(updateTime, 1000);
            
            // Initialize tooltips and animations
            initializeAnimations();
            
            // Show overview section by default
            showSection('overview');
        });
        
        function updateTime() {
            const now = new Date();
            const timeString = now.toISOString().slice(0, 19).replace('T', ' ');
            const timeElement = document.getElementById('current-time');
            if (timeElement) {
                timeElement.textContent = timeString;
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
    </script>
    
    <style>
        /* Add entrance animations */
        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .animate-in {
            animation: slideInUp 0.6s ease-out forwards;
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
