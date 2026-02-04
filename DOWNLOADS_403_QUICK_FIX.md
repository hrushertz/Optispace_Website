# QUICK FIX FOR DOWNLOADS 403 ERROR

## The Problem
The .htaccess file has a rewrite rule that redirects GET requests from .php to non-.php URLs:
```apache
RewriteCond %{REQUEST_METHOD} ^GET$
RewriteCond %{THE_REQUEST} ^[A-Z]{3,}\s([^.]+)\.php [NC]
RewriteRule ^ %1 [R=301,L]
```

This works for most pages, but can cause 403 errors if:
1. The redirect creates a conflict
2. The non-.php URL is being blocked
3. There's a caching issue with the redirect

## Solutions

### Solution 1: Access via Non-.php URL (Fastest)
Simply access the page without the .php extension:
```
https://solutionsoptispace.com/downloads
```
Instead of:
```
https://solutionsoptispace.com/downloads.php
```

### Solution 2: Check Apache Error Logs
SSH into the server and check recent errors:
```bash
tail -f /home/solutionsoptispa/logs/error_log
# OR
tail -f /var/log/apache2/error.log
```

Then access downloads.php and see what error appears.

### Solution 3: Temporarily Disable Redirect for Downloads
Add this exception to .htaccess BEFORE the existing redirect rule (after line 8):

```apache
RewriteEngine On

RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteCond %{REQUEST_FILENAME}.php -f
RewriteRule ^(.+)$ $1.php [L,QSA]

# EXCEPTION: Don't redirect downloads.php
RewriteCond %{REQUEST_URI} ^/downloads\.php$ [NC]
RewriteRule ^ - [L]

# Only redirect GET requests to remove .php extension, not POST
RewriteCond %{REQUEST_METHOD} ^GET$
RewriteCond %{THE_REQUEST} ^[A-Z]{3,}\s([^.]+)\.php [NC]
RewriteRule ^ %1 [R=301,L]
```

### Solution 4: Clear Browser/Server Cache
If you previously accessed downloads.php, your browser or Cloudflare might have cached the 403:

```bash
# Clear browser cache (Ctrl+Shift+Delete)
# OR use incognito mode

# If using Cloudflare, purge cache for that URL
```

### Solution 5: Check File Ownership
Even though permissions are 0644, ownership might be wrong:

```bash
# SSH to server
cd /home/solutionsoptispa/public_html
ls -la downloads.php

# Should show:
# -rw-r--r-- 1 solutionsoptispa solutionsoptispa

# If ownership is wrong, fix it:
chown solutionsoptispa:solutionsoptispa downloads.php
```

## Testing Steps

1. **First, try the non-.php URL:**
   - Visit: https://solutionsoptispace.com/downloads
   - This should work immediately

2. **If that works, the .php redirect is the issue:**
   - Add the exception to .htaccess (Solution 3)
   - Or just use the non-.php URL going forward

3. **If neither works:**
   - Check Apache error logs (Solution 2)
   - Check file ownership (Solution 5)
   - Report back the exact error from logs

## Quick Test Commands

Upload test_apache_access.php and visit:
```
https://solutionsoptispace.com/test_apache_access.php
```

If this works, the issue is specific to downloads.php or its URL pattern.

## Most Likely Solution
Based on the diagnostic showing everything works internally, the issue is almost certainly:
1. Accessing via `/downloads.php` which triggers redirect
2. Redirect causes 403 (possibly due to mod_security or cached response)
3. **Solution: Access via `/downloads` instead**
