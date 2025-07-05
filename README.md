# SOMAS Analytics Dashboard

A comprehensive PHP-based Learning Analytics Dashboard that integrates with SOMAS Moodle platform using REST API to provide real-time insights into learning activities, user engagement, and course performance.

## 🌟 Features

- **Real-time Data**: Live data fetching from SOMAS Moodle via REST API
- **User Analytics**: Track user registrations, activity, and engagement
- **Course Analytics**: Monitor course enrollments, popularity, and categories
- **System Health**: Monitor Moodle system status and performance
- **Responsive Design**: Works seamlessly on desktop and mobile devices
- **Caching System**: Optimized performance with intelligent caching
- **Easy Setup**: Simple configuration and deployment

## 🚀 Quick Start

### Prerequisites

- PHP 7.4 or higher
- cURL extension enabled
- Access to SOMAS Moodle instance
- Valid Moodle web service token

### Installation

1. **Download/Clone the project files**
2. **Configure your settings** in `config/config.php`:
   \`\`\`php
   define('MOODLE_URL', 'https://somas.snap.co.ke');
   define('MOODLE_TOKEN', 'your-web-service-token');
   \`\`\`
3. **Upload files** to your web server
4. **Test connection**: Visit `setup/verify_connection.php`
5. **Access dashboard**: Visit `index.php`

## 📁 Project Structure

\`\`\`
somas-analytics-dashboard/
├── config/
│   └── config.php              # Configuration settings
├── classes/
│   ├── MoodleApiClient.php     # Moodle API client
│   └── AnalyticsService.php    # Analytics processing
├── templates/
│   ├── header.php              # Page header
│   ├── footer.php              # Page footer
│   └── dashboard.php           # Main dashboard
├── assets/
│   ├── css/
│   │   └── style.css           # Stylesheet
│   ├── js/
│   │   └── dashboard.js        # JavaScript functionality
│   └── images/                 # Images and icons
├── setup/
│   └── verify_connection.php   # Connection testing tool
├── index.php                   # Main entry point
└── README.md                   # This file
\`\`\`

## ⚙️ Configuration

### Moodle Web Service Setup

1. **Enable Web Services**:
   - Go to `Site Administration → Server → Web services`
   - Check "Enable web services"

2. **Create External Service**:
   - Go to `External services`
   - Add a new service with these functions:
     - `core_webservice_get_site_info`
     - `core_user_get_users`
     - `core_course_get_courses`
     - `core_enrol_get_enrolled_users`
     - `core_completion_get_course_completion_status`

3. **Generate Token**:
   - Create a token for your service
   - Copy the token to your configuration

4. **Set Capabilities**:
   - Ensure the web service user has appropriate permissions

### Dashboard Configuration

Edit `config/config.php`:

\`\`\`php
// SOMAS Moodle Configuration
define('MOODLE_URL', 'https://somas.snap.co.ke');
define('MOODLE_TOKEN', 'your-actual-token-here');

// Dashboard Settings
define('DASHBOARD_TITLE', 'SOMAS Analytics Dashboard');
define('CACHE_DURATION', 300); // 5 minutes
define('TIMEZONE', 'Africa/Nairobi');
\`\`\`

## 📊 Dashboard Features

### Overview Cards
- Total registered users
- Active users (last 30 days)
- Total available courses
- Total course enrollments

### User Analytics
- Weekly and monthly active users
- Recent user registrations
- Users who never logged in
- Most active users list

### Course Analytics
- Courses with enrollments
- Average enrollments per course
- Most popular courses
- Course distribution by category
- Empty courses identification

### System Information
- Moodle version and system details
- PHP and database information
- Real-time system status

## 🔧 API Functions Used

| Function | Purpose |
|----------|---------|
| `core_webservice_get_site_info` | Get site information and system details |
| `core_user_get_users` | Retrieve user data and statistics |
| `core_course_get_courses` | Get course information and metadata |
| `core_enrol_get_enrolled_users` | Fetch enrollment data for courses |
| `core_completion_get_course_completion_status` | Get course completion statistics |

## 🚨 Troubleshooting

### Common Issues

1. **Connection Failed**:
   - Verify SOMAS Moodle URL is accessible
   - Check web service token validity
   - Ensure web services are enabled in Moodle

2. **Limited Data Access**:
   - Check user capabilities in Moodle
   - Verify required functions are enabled
   - Contact your Moodle administrator

3. **Performance Issues**:
   - Adjust cache duration in config
   - Check server resources
   - Monitor API response times

### Testing Tools

- **Connection Verification**: `setup/verify_connection.php`
- **Manual Refresh**: Add `?refresh=1` to URL
- **Browser Console**: Check for JavaScript errors

## 🔒 Security Considerations

- Store API tokens securely
- Use HTTPS for production deployment
- Implement proper access controls
- Regular token rotation recommended
- Monitor API usage and logs

## 📱 Mobile Responsiveness

The dashboard is fully responsive and optimized for:
- Desktop computers
- Tablets
- Mobile phones
- Various screen sizes and orientations

## 🎨 Customization

### Styling
- Edit `assets/css/style.css` for visual customization
- Modify color scheme using CSS variables
- Add custom branding and logos

### Functionality
- Extend `AnalyticsService.php` for new metrics
- Add custom API endpoints in `MoodleApiClient.php`
- Create additional dashboard sections

## 📈 Performance Optimization

- **Caching**: 5-minute cache for API responses
- **Lazy Loading**: Collapsible sections for better performance
- **Optimized Queries**: Efficient API calls and data processing
- **Responsive Images**: Optimized assets for faster loading

## 🤝 Support

For SOMAS-specific issues:
- Contact your Moodle administrator
- Check Moodle documentation
- Review system logs for errors

For dashboard issues:
- Run connection verification tool
- Check PHP error logs
- Verify file permissions

## 📄 License

This project is developed for SOMAS educational platform. Please ensure compliance with your organization's policies and Moodle's licensing terms.

## 🔄 Updates and Maintenance

- Regular token renewal
- Monitor API changes in Moodle updates
- Update dashboard for new features
- Performance monitoring and optimization

---

**SOMAS Analytics Dashboard** - Empowering education through data-driven insights.
