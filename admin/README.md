# OptiSpace Admin Panel

## Installation Guide

### Step 1: Database Setup
1. Open your browser and navigate to: `http://localhost/Optispace_Website/admin/install.php`
2. The installation script will automatically:
   - Create the database
   - Create all required tables
   - Add the default admin user

### Step 2: First Login
1. After installation, go to: `http://localhost/Optispace_Website/admin/login.php`
2. Use these credentials:
   - **Username:** admin
   - **Password:** admin123
3. **Important:** Change the password after first login!

### Step 3: Security
For security reasons, delete or rename the `install.php` file after installation:
```bash
rm admin/install.php
```

## Features

### 1. **Blog Editor**
- Rich text editor (TinyMCE)
- Featured images
- Draft/Published status
- SEO-friendly slugs
- View all blog posts in a table

### 2. **Resources Management**
- Upload documents (PDF, DOC, DOCX, XLS, XLSX, ZIP, PPT, PPTX)
- Categorize resources
- Track download counts
- File size management

### 3. **Gallery Management**
- Upload images (JPG, PNG, GIF, WEBP)
- Automatic thumbnail generation
- Category organization
- Image preview
- Grid view of all gallery images

## File Structure

```
/admin
├── login.php              # Admin login page
├── dashboard.php          # Main dashboard
├── add-blog.php           # Blog editor
├── add-resources.php      # Resources management
├── add-gallery.php        # Gallery management
├── install.php            # Installation script
├── logout.php             # Logout handler
├── auth_check.php         # Authentication middleware
├── delete-blog.php        # Delete blog posts
├── delete-resource.php    # Delete resources
├── delete-gallery.php     # Delete gallery images
├── /assets
│   └── /css
│       └── admin.css      # Admin panel styles
└── /includes
    ├── sidebar.php        # Sidebar navigation
    └── topbar.php         # Top bar

/database
├── db_config.php          # Database connection
└── schema.sql             # Database schema

/uploads
├── /blog                  # Blog images
├── /resources             # Resource files
└── /gallery               # Gallery images
    └── /thumbs            # Image thumbnails
```

## Database Tables

1. **admin_users** - Admin user accounts
2. **blogs** - Blog posts
3. **resources** - Downloadable resources
4. **gallery** - Gallery images
5. **admin_logs** - Activity logging

## Default Credentials

- **Username:** admin
- **Password:** admin123

⚠️ **Change immediately after first login!**

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- XAMPP/LAMP/MAMP server
- GD or Imagick extension (for image processing)

## Troubleshooting

### Database Connection Error
Check `/database/db_config.php` and ensure:
- MySQL server is running
- Database credentials are correct
- Database user has proper permissions

### File Upload Issues
Check:
- PHP `upload_max_filesize` setting
- PHP `post_max_size` setting
- `/uploads` directory permissions (should be writable)

### Permission Denied
```bash
chmod -R 755 uploads/
```

## Support

For issues or questions, contact the development team.
