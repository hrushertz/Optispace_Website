# Solutions OptiSpace Website - Setup Guide

## Quick Start

This is a static PHP website that requires a web server with PHP support. No database is needed.

## Project Structure

```
/project
├── .htaccess                          # Apache configuration
├── README.md                          # Project documentation
├── SETUP.md                           # This file
│
├── index.php                          # Home page
├── philosophy.php                     # Philosophy page
├── process.php                        # Process page
├── portfolio.php                      # Portfolio page
├── about.php                          # About page
├── contact.php                        # Contact page
│
├── services/                          # Services directory
│   ├── greenfield.php                 # Greenfield services
│   ├── brownfield.php                 # Brownfield services
│   └── post-commissioning.php         # Post-commissioning services
│
├── includes/                          # Shared components
│   ├── header.php                     # Site header
│   └── footer.php                     # Site footer
│
└── assets/                            # Static assets
    ├── css/
    │   └── style.css                  # Main stylesheet
    └── js/
        └── main.js                    # JavaScript interactions
```

## Installation Options

### Option 1: Local PHP Development Server

1. Open terminal in the project directory
2. Run: `php -S localhost:8000`
3. Visit: http://localhost:8000

### Option 2: Apache/XAMPP/WAMP

1. Copy project files to your web server directory (e.g., `htdocs` or `www`)
2. Ensure Apache's `mod_rewrite` is enabled
3. Visit: http://localhost/project-folder-name

### Option 3: Production Server

1. Upload all files to your web server via FTP/SFTP
2. Ensure .htaccess file is uploaded
3. Verify Apache `mod_rewrite` is enabled
4. Visit your domain

## Configuration

### 1. Update Contact Information

Edit the following files:

**includes/footer.php** - Update footer contact details:
- Find: `[Address]`, `[Email]`, `[Mobile]`
- Replace with actual office information

**contact.php** - Update office locations:
- Find: `[Complete Address Line 1]`, etc.
- Replace with actual addresses
- Update email addresses and phone numbers

### 2. Customize Branding (Optional)

**assets/css/style.css** - Update CSS variables (lines 1-20):
```css
--primary-color: #0066cc;     /* Main brand color */
--secondary-color: #00a86b;   /* Secondary brand color */
--accent-color: #ff6b35;      /* Accent color */
```

### 3. Enable Form Submissions

Currently, forms display alert messages. To enable real submissions:

1. Create a form processing script (e.g., `process-form.php`)
2. Update form action in contact.php:
   ```php
   <form action="process-form.php" method="post">
   ```
3. Implement email or database logic

Example form processor:
```php
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);

    // Send email
    mail('your@email.com', 'Contact Form', "From: $name ($email)");

    // Redirect
    header('Location: contact.php?success=1');
    exit;
}
?>
```

## Features

✅ Fully responsive design
✅ Mobile-friendly navigation
✅ Smooth scrolling
✅ Form validation
✅ Clean URLs (.php extension hidden)
✅ SEO optimized
✅ Fast loading with caching
✅ Cross-browser compatible

## Testing Checklist

- [ ] Home page loads correctly
- [ ] All navigation links work
- [ ] Mobile menu functions properly
- [ ] Forms display validation messages
- [ ] Contact information is updated
- [ ] All pages are responsive on mobile
- [ ] Clean URLs work (no .php extensions)
- [ ] Images load properly
- [ ] CSS and JS files load correctly

## Troubleshooting

### Problem: Clean URLs not working (404 errors)

**Solution:** Enable Apache mod_rewrite
```bash
# On Ubuntu/Debian
sudo a2enmod rewrite
sudo service apache2 restart

# Then ensure .htaccess is allowed in Apache config
# Add to your VirtualHost or Directory config:
AllowOverride All
```

### Problem: Styles not loading

**Solution:** Check file paths
- Ensure `/assets/css/style.css` exists
- Check browser console for 404 errors
- Verify Apache can read the files

### Problem: Forms not submitting

**Solution:** Check form action
- Forms currently use JavaScript preventDefault
- Remove `e.preventDefault()` in main.js or update form action

### Problem: PHP errors displayed

**Solution:** Check PHP version
- Requires PHP 7.4 or higher
- Check error_reporting in php.ini

## Next Steps

1. **Add Real Content:**
   - Replace placeholder addresses with real locations
   - Add actual client testimonials
   - Upload portfolio images

2. **Implement Forms:**
   - Set up email delivery
   - Add spam protection (reCAPTCHA)
   - Create confirmation pages

3. **Performance:**
   - Optimize images
   - Add CDN for assets
   - Enable GZIP compression

4. **SEO:**
   - Submit sitemap to search engines
   - Set up Google Analytics
   - Add structured data markup

5. **Security:**
   - Install SSL certificate (HTTPS)
   - Add security headers
   - Implement CSRF protection on forms

## Support

For technical issues or questions:
- Check README.md for detailed documentation
- Review PHP error logs
- Contact the development team

## Deployment Checklist

Before going live:

- [ ] All placeholder text replaced with real content
- [ ] Contact forms tested and working
- [ ] All contact information updated
- [ ] SSL certificate installed
- [ ] Google Analytics added
- [ ] 404 page customized
- [ ] Site tested on multiple browsers
- [ ] Mobile responsiveness verified
- [ ] All images optimized
- [ ] Performance tested
- [ ] Backup system configured

---

**Version:** 1.0
**Last Updated:** December 2024
**Built for:** Solutions OptiSpace
