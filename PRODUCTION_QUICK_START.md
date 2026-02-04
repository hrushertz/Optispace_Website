# Quick Production Setup Guide
## www.solutionsoptispace.com

### Step 1: Edit .env File on Server

After uploading files, edit the `.env` file with your production values:

```bash
# Required: Set to production
ENVIRONMENT=production

# Required: Your production URL
BASE_URL=https://www.solutionsoptispace.com

# Required: Database credentials from cPanel
DB_HOST=localhost
DB_NAME=your_cpanel_db_name      # Get from cPanel MySQL Databases
DB_USER=your_cpanel_db_user      # Get from cPanel MySQL Databases
DB_PASS=your_cpanel_db_password  # Set when creating database

# Required: Email configuration
SMTP_HOST=mail.solutionsoptispace.com
SMTP_PORT=587
SMTP_SECURE=tls
SMTP_USERNAME=noreply@solutionsoptispace.com
SMTP_PASSWORD=your_email_password
MAIL_FROM=noreply@solutionsoptispace.com
MAIL_FROM_NAME=OptiSpace Solutions

# Optional: Security settings (keep defaults)
DISPLAY_ERRORS=false
DEBUG_MODE=false
```

### Step 2: Enable HTTPS in .htaccess

Edit `.htaccess` and uncomment these lines (remove the # symbol):

```apache
# Line 11-12: Force HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Line 15-16: Force www prefix
RewriteCond %{HTTP_HOST} !^www\. [NC]
RewriteRule ^(.*)$ https://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

### Step 3: Import Database

Via phpMyAdmin:
1. Login to cPanel → phpMyAdmin
2. Select your database
3. Click Import tab
4. Upload `database/schema.sql`
5. Click Go

OR via browser:
- Visit: `www.solutionsoptispace.com/scripts/apply_schema.php`

### Step 4: Test

1. Visit: https://www.solutionsoptispace.com
2. Test admin panel: https://www.solutionsoptispace.com/admin
3. Test contact form
4. Verify no errors appear

### Files NOT to Upload
- `.env` (create fresh on server)
- `node_modules/`
- `.git/`
- `*.log` files
- Local backup files

### Quick Troubleshooting

**Can't connect to database?**
- Check DB credentials in .env
- Verify DB_HOST is usually 'localhost'
- Ensure database user has privileges

**500 Error?**
- Check error_log in cPanel
- Verify file permissions (755/644)
- Check .htaccess syntax

**Emails not sending?**
- Verify SMTP credentials
- Try port 465 with ssl instead of 587 with tls
- Check if hosting allows SMTP

**Pages look broken?**
- Verify BASE_URL in .env includes https://
- Clear browser cache
- Check if CSS/JS files uploaded

### Security Checklist
- [ ] .env file is protected (not accessible via browser)
- [ ] HTTPS is enabled and working
- [ ] Error display is OFF (DISPLAY_ERRORS=false)
- [ ] Admin panel requires login
- [ ] Sensitive files (.md, .sql) are not accessible

---
For detailed instructions, see PRODUCTION_DEPLOYMENT.md
