# Fix for "Field 'id' doesn't have a default value" Error

## Problem Description

When trying to add a client video on **production**, you get the error:
```
Error adding video: Field 'id' doesn't have a default value
```

This works fine on **localhost** because of differences in MySQL/MariaDB server configuration.

## Root Cause

The `client_videos` table on production doesn't have the `id` column properly configured with `AUTO_INCREMENT`. This happens because:

1. **Strict SQL Mode**: Production servers often have `sql_mode` set to `STRICT_TRANS_TABLES` or `STRICT_ALL_TABLES`
2. **Table Creation Issue**: The table was created without `AUTO_INCREMENT` on the `id` column
3. **Missing Script Execution**: The `create_client_videos_table.php` script may not have been run on production

## Solutions

### Solution 1: Using the PHP Fix Script (Recommended)

1. **Via Browser** (Easiest):
   - Go to: `https://www.solutionsoptispace.com/scripts/fix_client_videos_table.php`
   - The script will automatically:
     - Check if the table exists
     - Verify if `AUTO_INCREMENT` is properly set
     - Fix the table structure if needed
     - Preserve all existing video data

2. **Via SSH/Terminal**:
   ```bash
   php /home/username/public_html/scripts/fix_client_videos_table.php
   ```

### Solution 2: Using SQL Directly (Via phpMyAdmin)

If the PHP script doesn't work, use this SQL method:

1. **Log into cPanel → phpMyAdmin**
2. **Select your OptiSpace database**
3. **Click "SQL" tab**
4. **Copy and paste this SQL:**

```sql
CREATE TABLE client_videos_temp (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    youtube_video_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL,
    INDEX idx_active (is_active),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO client_videos_temp (id, title, description, youtube_video_url, sort_order, is_active, created_by, created_at, updated_at)
SELECT id, title, description, youtube_video_url, sort_order, is_active, created_by, created_at, updated_at
FROM client_videos;

DROP TABLE client_videos;

ALTER TABLE client_videos_temp RENAME TO client_videos;
```

5. **Click "Go" to execute**

### Solution 3: If Table is Empty (Quickest)

If there are no videos in the table yet:

1. **In phpMyAdmin, select your database**
2. **Right-click on `client_videos` table → Drop**
3. **Click "SQL" tab and run this:**

```sql
CREATE TABLE IF NOT EXISTS client_videos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT DEFAULT NULL,
    youtube_video_url VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_by INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES admin_users(id) ON DELETE SET NULL,
    INDEX idx_active (is_active),
    INDEX idx_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

## Verification

To verify the fix worked:

1. **In phpMyAdmin**, select `client_videos` table
2. **Click "Structure" tab**
3. **Look for the `id` row** - it should show:
   - **Type**: `int(11)`
   - **Extra**: `auto_increment`
   - **Key**: `PRIMARY`

You should now be able to add videos successfully!

## Why This Happens

### Localhost Differences
- Local XAMPP typically has permissive MySQL settings
- The table might have been created without strict validation

### Production Differences
- **SQL Mode**: `STRICT_TRANS_TABLES` prevents implicit default values
- **Server Config**: Some hosting providers have stricter MySQL configurations
- **Table Creation**: When fields don't have DEFAULT values, INSERT statements must provide them

### The Fix
- Ensures the `id` column has `AUTO_INCREMENT PRIMARY KEY`
- This means MySQL will automatically generate the next ID for each new row
- No need to explicitly provide an id value in INSERT statements

## Related Files

- **Fix Script**: `/scripts/fix_client_videos_table.php`
- **SQL Fix File**: `/scripts/fix_client_videos_table.sql`
- **Add Video Script**: `/admin/client-video-add.php` (line 30)
- **Table Schema**: `/scripts/create_client_videos_table.php`

## Need Help?

If the error persists after running the fix:

1. **Verify the table structure** (as shown in Verification section)
2. **Check admin_users table exists** (required for FOREIGN KEY)
3. **Clear browser cache** and try again
4. **Check error logs** in cPanel → Error Logs

Contact hosting support if you continue to have issues - they can verify MySQL version and configuration.
