# Production Deployment Checklist
## OptiSpace Website - www.solutionsoptispace.com

### Pre-Deployment Preparation

#### 1. Environment Configuration ✓
- [x] `.env` file created with production settings
- [ ] Update database credentials in `.env`:
  ```
  DB_HOST=localhost
  DB_NAME=your_cpanel_database_name
  DB_USER=your_cpanel_database_user
  DB_PASS=your_cpanel_database_password
  ```
- [ ] Update SMTP email settings in `.env`:
  ```
  SMTP_HOST=mail.solutionsoptispace.com
  SMTP_USERNAME=noreply@solutionsoptispace.com
  SMTP_PASSWORD=your_email_password
  ```
- [ ] Verify `ENVIRONMENT=production` in `.env`
- [ ] Verify `BASE_URL=https://www.solutionsoptispace.com` in `.env`

#### 2. Security Settings
- [x] `.htaccess` configured with security headers
- [ ] Enable HTTPS redirect in `.htaccess` (uncomment lines 11-12)
- [ ] Enable WWW redirect in `.htaccess` (uncomment lines 15-16)
- [ ] Verify `.env` file is protected (should be denied access via browser)
- [ ] Test that sensitive files (.md, .sql, .json) are not accessible

#### 3. File Upload
- [ ] Upload all files to cPanel File Manager or via FTP
- [ ] Ensure files are in public_html or www directory
- [ ] **DO NOT upload** the following:
  - `.env` file from local (create new one on server)
  - `node_modules/` folder
  - `.git/` folder
  - Local backup files
  - Test data

#### 4. Database Setup
- [ ] Create MySQL database in cPanel
- [ ] Create MySQL user in cPanel
- [ ] Add user to database with ALL PRIVILEGES
- [ ] Update database credentials in `.env` file on server
- [ ] Import database schema:
  - Access phpMyAdmin from cPanel
  - Select your database
  - Import `database/schema.sql`
- [ ] OR run setup script via browser: `www.solutionsoptispace.com/scripts/apply_schema.php`

#### 5. File Permissions
Set the following permissions via cPanel File Manager:
- [ ] Directories: 755
- [ ] PHP files: 644
- [ ] `.env` file: 600 or 400 (read-only)
- [ ] Writable directories (if any): 755 or 775
  - `assets/img/gallery/` (for uploads)
  - `logs/` (if exists)

#### 6. SSL Certificate
- [ ] Install SSL certificate in cPanel (Let's Encrypt is free)
- [ ] Force HTTPS by uncommenting lines in `.htaccess`
- [ ] Test HTTPS access: https://www.solutionsoptispace.com

#### 7. Domain Configuration
- [ ] Ensure DNS points to your hosting server
- [ ] Wait for DNS propagation (can take up to 48 hours)
- [ ] Test both www and non-www versions
- [ ] Enable www redirect in `.htaccess` if needed

### Post-Deployment Testing

#### 8. Functionality Tests
- [ ] Homepage loads correctly
- [ ] All navigation links work
- [ ] Contact form submits and sends emails
- [ ] Inquiry form works
- [ ] Pulse check form works
- [ ] Gallery displays properly
- [ ] Blog pages load
- [ ] Download links work
- [ ] Admin panel accessible at: www.solutionsoptispace.com/admin
- [ ] Blogger panel accessible at: www.solutionsoptispace.com/blogger

#### 9. Admin Panel Tests
- [ ] Admin login works
- [ ] Dashboard displays correctly
- [ ] User management functions
- [ ] Gallery management functions
- [ ] Blog management functions
- [ ] Settings can be updated
- [ ] File uploads work

#### 10. Performance & SEO
- [ ] Test page load speed
- [ ] Check mobile responsiveness
- [ ] Verify meta tags are present
- [ ] Submit sitemap to Google Search Console
- [ ] Verify robots.txt is correct

#### 11. Security Checks
- [ ] Verify error display is OFF (should not see PHP errors)
- [ ] Test that `.env` file cannot be accessed via browser
- [ ] Test that database credentials are secure
- [ ] Verify admin panel requires authentication
- [ ] Check that sensitive endpoints are protected

#### 12. Email Configuration
- [ ] Send test email from contact form
- [ ] Verify emails are received
- [ ] Check email formatting
- [ ] Verify "from" address is correct
- [ ] Test inquiry notifications

### Common Issues & Solutions

#### Database Connection Error
```
- Check DB credentials in .env file
- Verify database user has correct privileges
- Ensure database exists in cPanel
- Check DB_HOST (usually 'localhost')
```

#### 500 Internal Server Error
```
- Check .htaccess syntax
- Review error logs in cPanel
- Verify file permissions (755/644)
- Check PHP version compatibility
```

#### Email Not Sending
```
- Verify SMTP settings in .env
- Test with different SMTP port (587 or 465)
- Check if hosting allows SMTP
- Consider using hosting's mail server
```

#### CSS/JS Not Loading
```
- Clear browser cache
- Check BASE_URL in .env
- Verify file paths are correct
- Check .htaccess rewrite rules
```

### Maintenance Tasks

#### Regular Backups
- [ ] Set up automatic database backups in cPanel
- [ ] Backup files regularly (weekly/monthly)
- [ ] Store backups in secure location off-server

#### Updates
- [ ] Keep PHP version updated
- [ ] Update Composer dependencies periodically
- [ ] Review and update security headers
- [ ] Monitor error logs regularly

### Support & Documentation

- Production URL: https://www.solutionsoptispace.com
- Admin Panel: https://www.solutionsoptispace.com/admin
- Blogger Panel: https://www.solutionsoptispace.com/blogger

### Emergency Maintenance Mode
To enable maintenance mode if something goes wrong:
1. Access phpMyAdmin
2. Find `site_settings` table
3. Update `maintenance_mode` setting to `1`
4. Site will redirect to maintenance page

---
**Deployment Date:** _____________
**Deployed By:** _____________
**Notes:** _____________________________________________
