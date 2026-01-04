# Testing Guide: Separate Login URLs

## Test Environment Setup

Before testing, ensure:
- XAMPP is running (Apache and MySQL)
- Database schema has been applied
- You have users with different roles in the database

## Test Users Setup

If needed, create test users with different roles:

```sql
-- Test Super Admin (if not exists)
INSERT INTO admin_users (username, email, password, full_name, role, is_active) VALUES
('superadmin', 'superadmin@optispace.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Super Administrator', 'super_admin', 1);

-- Test Admin
INSERT INTO admin_users (username, email, password, full_name, role, is_active) VALUES
('testadmin', 'testadmin@optispace.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Administrator', 'admin', 1);

-- Test Editor
INSERT INTO admin_users (username, email, password, full_name, role, is_active) VALUES
('testeditor', 'editor@optispace.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Test Editor', 'editor', 1);
```

Default password for all test users: `admin123`

---

## Test Cases

### ✅ TEST 1: Super Admin Login via Admin Panel

**Steps:**
1. Open browser and navigate to: `http://localhost/Optispace_Website/admin/login.php`
2. Enter credentials:
   - Username: `admin` or `superadmin`
   - Password: `admin123`
3. Click "Sign In"

**Expected Result:**
- ✓ Login successful
- ✓ Redirected to `/admin/dashboard.php`
- ✓ Can see full admin dashboard
- ✓ Can access all admin features

---

### ✅ TEST 2: Admin Login via Admin Panel

**Steps:**
1. Navigate to: `http://localhost/Optispace_Website/admin/login.php`
2. Enter credentials:
   - Username: `testadmin`
   - Password: `admin123`
3. Click "Sign In"

**Expected Result:**
- ✓ Login successful
- ✓ Redirected to `/admin/dashboard.php`
- ✓ Can see admin dashboard
- ✓ Can access admin features

---

### ❌ TEST 3: Editor Login via Admin Panel (Should FAIL)

**Steps:**
1. Navigate to: `http://localhost/Optispace_Website/admin/login.php`
2. Enter credentials:
   - Username: `testeditor`
   - Password: `admin123`
3. Click "Sign In"

**Expected Result:**
- ✗ Login blocked
- ✓ Error message displayed: "Editors should use the **Blogger Panel** to log in."
- ✓ Error message contains clickable link to blogger panel
- ✓ No session created
- ✓ Remains on admin login page

---

### ✅ TEST 4: Editor Login via Blogger Panel

**Steps:**
1. Navigate to: `http://localhost/Optispace_Website/blogger/login.php`
2. Enter credentials:
   - Username: `testeditor`
   - Password: `admin123`
3. Click "Sign In"

**Expected Result:**
- ✓ Login successful
- ✓ Redirected to `/blogger/dashboard.php`
- ✓ Can see blogger dashboard
- ✓ Can manage blogs

---

### ❌ TEST 5: Admin Login via Blogger Panel (Should FAIL)

**Steps:**
1. Navigate to: `http://localhost/Optispace_Website/blogger/login.php`
2. Enter credentials:
   - Username: `admin` or `testadmin`
   - Password: `admin123`
3. Click "Sign In"

**Expected Result:**
- ✗ Login blocked
- ✓ Error message displayed: "Invalid credentials or you do not have editor access. Admins should use the **Admin Panel**."
- ✓ Error message contains clickable link to admin panel
- ✓ No session created
- ✓ Remains on blogger login page

---

### ❌ TEST 6: Editor Direct Access to Admin Dashboard (Should FAIL)

**Steps:**
1. First, log in as editor via blogger panel
2. Verify you're logged in to blogger dashboard
3. Manually navigate to: `http://localhost/Optispace_Website/admin/dashboard.php`

**Expected Result:**
- ✗ Access denied
- ✓ Editor session is logged out
- ✓ Automatically redirected to `/blogger/login.php?msg=admin_redirect`
- ✓ Info message displayed: "This is the Editor/Blogger panel. Admins should use the **Admin Panel**."
- ✓ Must log in again to access blogger panel

---

### ✅ TEST 7: Link Functionality in Error Messages

**Steps:**
1. Try to log in as editor at admin panel (TEST 3)
2. Click the "Blogger Panel" link in the error message

**Expected Result:**
- ✓ Link is clickable
- ✓ Redirects to `/blogger/login.php`
- ✓ Blogger login page loads correctly

---

### ✅ TEST 8: Visual Guidance Notes

**Steps:**
1. Visit admin login page: `http://localhost/Optispace_Website/admin/login.php`
2. Scroll to bottom of login form

**Expected Result:**
- ✓ See note: "Are you a blogger/editor? Please use the **Blogger Panel** to log in."
- ✓ Link is styled in orange (#E99431)
- ✓ Link is clickable and works

**Steps:**
1. Visit blogger login page: `http://localhost/Optispace_Website/blogger/login.php`
2. Scroll to bottom of login form

**Expected Result:**
- ✓ See note: "Are you an administrator? Please use the **Admin Panel** to log in."
- ✓ Link is styled in blue (#3B82F6)
- ✓ Link is clickable and works

---

### ✅ TEST 9: Session Isolation

**Steps:**
1. Open two different browsers (e.g., Chrome and Firefox)
2. In Browser 1: Log in as admin at `/admin/login.php`
3. In Browser 2: Log in as editor at `/blogger/login.php`
4. Both users should be logged in simultaneously

**Expected Result:**
- ✓ Admin can access admin panel in Browser 1
- ✓ Editor can access blogger panel in Browser 2
- ✓ Sessions don't interfere with each other
- ✓ Each user sees their respective dashboard

---

### ✅ TEST 10: Logout and Re-Login

**Steps:**
1. Log in as admin at admin panel
2. Click logout
3. Try to log in as editor at admin panel

**Expected Result:**
- ✓ Admin session is cleared
- ✓ Editor login at admin panel is blocked with error
- ✓ Can successfully log in as editor at blogger panel

---

## Test Results Checklist

Mark each test as completed:

- [ ] TEST 1: Super Admin Login via Admin Panel ✅
- [ ] TEST 2: Admin Login via Admin Panel ✅
- [ ] TEST 3: Editor Login via Admin Panel ❌ (Should fail)
- [ ] TEST 4: Editor Login via Blogger Panel ✅
- [ ] TEST 5: Admin Login via Blogger Panel ❌ (Should fail)
- [ ] TEST 6: Editor Direct Access to Admin Dashboard ❌ (Should fail)
- [ ] TEST 7: Link Functionality in Error Messages ✅
- [ ] TEST 8: Visual Guidance Notes ✅
- [ ] TEST 9: Session Isolation ✅
- [ ] TEST 10: Logout and Re-Login ✅

---

## Common Issues and Solutions

### Issue: "Connection failed" error
**Solution:** Ensure MySQL is running in XAMPP

### Issue: "Table doesn't exist" error
**Solution:** Run the schema.sql file to create tables

### Issue: Can't login with any credentials
**Solution:** 
- Check if default admin user exists in database
- Verify password hash is correct
- Try resetting password in database

### Issue: Redirects not working
**Solution:**
- Clear browser cache and cookies
- Check PHP session configuration
- Verify no output before header() calls

### Issue: Links in error messages not clickable
**Solution:**
- Verify HTML is not being escaped (should use `echo $error;` not `htmlspecialchars($error)`)

---

## Security Verification

After all tests pass, verify:

✅ Editors cannot access admin panel (any page)
✅ Admins cannot use blogger panel  
✅ Sessions are separate and don't interfere
✅ Direct URL access is protected
✅ Error messages are helpful but not revealing sensitive info
✅ All logins are logged in activity table
✅ Passwords are never shown in plain text

---

## Browser Testing Matrix

Test in multiple browsers:

- [ ] Chrome/Chromium
- [ ] Firefox
- [ ] Safari (macOS)
- [ ] Edge

---

## Performance Check

- [ ] Login redirects happen quickly (< 1 second)
- [ ] Error messages appear immediately
- [ ] No console errors in browser developer tools
- [ ] Session handling is efficient

---

## Final Verification

Once all tests pass:

1. ✅ Admin login enforces admin/super_admin roles only
2. ✅ Blogger login enforces editor role only
3. ✅ Cross-role login attempts are properly blocked
4. ✅ Error messages guide users to correct login
5. ✅ Visual notes help prevent wrong login attempts
6. ✅ Direct page access is protected
7. ✅ Sessions are properly isolated
8. ✅ All links work correctly

---

**Testing Completed By:** ________________
**Date:** ________________
**Status:** ☐ All Tests Passed  ☐ Issues Found (document below)

**Issues Found:**
```
[List any issues discovered during testing]
```

---

For questions or issues, refer to:
- LOGIN_GUIDE.md - Comprehensive user guide
- LOGIN_ARCHITECTURE.md - System architecture details
- LOGIN_SEPARATION_SUMMARY.md - Implementation details
