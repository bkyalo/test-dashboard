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
</body>
</html>
