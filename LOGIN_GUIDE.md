# Login System Guide

## Separate Login URLs for Different User Roles

The OptiSpace website has two distinct login panels with separate URLs based on user roles:

### 🔐 Admin Panel Login
**URL:** `/admin/login.php`

**Access:** Super Admins and Admins only

**Features:**
- Full administrative access to all system features
- User management
- Download management
- Gallery management
- Featured projects management
- Blog category management
- System configuration
- Activity logs

**Roles that can access:**
- `super_admin` - Full system access
- `admin` - Administrative access

**Note:** If an editor tries to log in to the admin panel, they will receive an error message directing them to the Blogger Panel.

---

### ✍️ Blogger/Editor Panel Login
**URL:** `/blogger/login.php`

**Access:** Editors/Bloggers only

**Features:**
- Blog creation and editing
- Blog management
- Delete requests for blogs
- Personal content management

**Roles that can access:**
- `editor` - Blog writing and editing access

**Note:** If an admin or super admin tries to use blogger credentials, they should use the Admin Panel instead.

---

## Role Separation

The system enforces strict role separation:

1. **Login Protection:**
   - Admin login checks user role and rejects editors
   - Blogger login only accepts editor role
   - Cross-login attempts show helpful error messages with links to correct panel

2. **Session Protection:**
   - Admin pages check for editor sessions and redirect to blogger panel
   - Separate session variables prevent cross-contamination

3. **Database Roles:**
   - All users are stored in the `admin_users` table
   - Role field determines access level: `super_admin`, `admin`, or `editor`

---

## Testing the Separation

### Test as Admin:
1. Go to `/admin/login.php`
2. Login with admin credentials
3. Should access admin dashboard successfully

### Test as Editor:
1. Go to `/blogger/login.php`
2. Login with editor credentials
3. Should access blogger dashboard successfully

### Test Cross-Access:
1. Try logging in as editor at `/admin/login.php`
   - Should see error: "Editors should use the Blogger Panel to log in"
2. Try logging in as admin at `/blogger/login.php`
   - Should see error: "Invalid credentials or you do not have editor access"

---

## User Creation

When creating new users through the admin panel (`/admin/user-add.php`):
- Select role: `super_admin`, `admin`, or `editor`
- Editors will automatically use the Blogger Panel
- Admins and super admins will use the Admin Panel

---

## Security Features

✅ Role-based authentication
✅ Separate session management
✅ Password hashing with bcrypt
✅ Login activity logging
✅ Session regeneration on login
✅ Automatic session validation
✅ Role hierarchy enforcement

---

## Quick Links

| Role | Login URL | Dashboard |
|------|-----------|-----------|
| Super Admin | `/admin/login.php` | `/admin/dashboard.php` |
| Admin | `/admin/login.php` | `/admin/dashboard.php` |
| Editor | `/blogger/login.php` | `/blogger/dashboard.php` |

---

## Default Credentials

**Admin Account:**
- Username: `admin`
- Password: `admin123`
- Role: `super_admin`

⚠️ **Important:** Change the default password immediately in production!

---

## Technical Implementation

### Admin Login Flow:
```php
/admin/login.php
  ↓
authenticateAdmin() checks credentials
  ↓
If editor role → Show error with link to blogger panel
If admin/super_admin → setAdminSession() → dashboard.php
```

### Blogger Login Flow:
```php
/blogger/login.php
  ↓
authenticateBlogger() checks credentials (editors only)
  ↓
If editor → setBloggerSession() → dashboard.php
If not editor → Show error with link to admin panel
```

### Session Variables:
- **Admin:** `$_SESSION['admin_logged_in']`, `$_SESSION['admin_role']`
- **Blogger:** `$_SESSION['blogger_logged_in']`, `$_SESSION['blogger_role']`

---

Last Updated: January 4, 2026
