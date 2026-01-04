# Login System Architecture

```
┌─────────────────────────────────────────────────────────────────────┐
│                     OPTISPACE LOGIN SYSTEM                          │
│                     Separate URLs by Role                           │
└─────────────────────────────────────────────────────────────────────┘


                           ┌──────────────┐
                           │  admin_users │
                           │    TABLE     │
                           └───────┬──────┘
                                   │
                    ┌──────────────┼──────────────┐
                    │              │              │
              ┌─────▼─────┐  ┌────▼────┐  ┌─────▼─────┐
              │super_admin│  │  admin  │  │  editor   │
              └─────┬─────┘  └────┬────┘  └─────┬─────┘
                    │             │             │
                    └──────┬──────┘             │
                           │                    │
                           │                    │
            ┌──────────────▼──────────┐  ┌─────▼──────────────┐
            │   ADMIN PANEL LOGIN     │  │  BLOGGER LOGIN     │
            │  /admin/login.php       │  │ /blogger/login.php │
            │                         │  │                    │
            │  ✓ super_admin access   │  │  ✓ editor access   │
            │  ✓ admin access         │  │  ✗ admin blocked   │
            │  ✗ editor blocked       │  │  ✗ super_admin     │
            └──────────┬──────────────┘  └──────────┬─────────┘
                       │                            │
                       │                            │
            ┌──────────▼──────────────┐  ┌─────────▼──────────┐
            │  Admin Session          │  │  Blogger Session   │
            │  $_SESSION['admin_*']   │  │  $_SESSION['blog*']│
            └──────────┬──────────────┘  └──────────┬─────────┘
                       │                            │
                       │                            │
            ┌──────────▼──────────────┐  ┌─────────▼──────────┐
            │   ADMIN DASHBOARD       │  │  BLOGGER DASHBOARD │
            │  /admin/dashboard.php   │  │ /blogger/dashboard │
            │                         │  │                    │
            │  • User Management      │  │  • Blog Writing    │
            │  • Downloads            │  │  • Blog Editing    │
            │  • Gallery              │  │  • Delete Requests │
            │  • Featured Projects    │  │  • Content Mgmt    │
            │  • Blog Categories      │  │                    │
            │  • Full System Access   │  │                    │
            └─────────────────────────┘  └────────────────────┘


ROLE SEPARATION ENFORCEMENT
═══════════════════════════

┌─────────────────────────────────────────────────────────────────┐
│ Layer 1: Login Page                                             │
├─────────────────────────────────────────────────────────────────┤
│ • Checks user role after authentication                         │
│ • Rejects wrong role with helpful error message                 │
│ • Provides link to correct login panel                          │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ Layer 2: Header Include (Every Page)                            │
├─────────────────────────────────────────────────────────────────┤
│ • /admin/includes/header.php checks for editor role             │
│ • Logs out and redirects editors to blogger panel               │
│ • Prevents unauthorized access to any admin page                │
└─────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────┐
│ Layer 3: Dashboard Verification                                 │
├─────────────────────────────────────────────────────────────────┤
│ • Additional role check at dashboard level                      │
│ • Double verification for critical entry point                  │
│ • Ensures no bypass possible                                    │
└─────────────────────────────────────────────────────────────────┘


USER FLOW EXAMPLES
══════════════════

SCENARIO 1: Admin User Login
─────────────────────────────
1. Navigate to /admin/login.php
2. Enter admin credentials
3. Role check: admin ✓
4. Create admin session
5. Redirect to /admin/dashboard.php
6. Full access granted


SCENARIO 2: Editor User Login
──────────────────────────────
1. Navigate to /blogger/login.php
2. Enter editor credentials
3. Role check: editor ✓
4. Create blogger session
5. Redirect to /blogger/dashboard.php
6. Blog management access granted


SCENARIO 3: Editor Tries Admin Login
─────────────────────────────────────
1. Navigate to /admin/login.php
2. Enter editor credentials
3. Role check: editor ✗
4. Show error: "Editors should use Blogger Panel"
5. Display link to /blogger/login.php
6. No session created


SCENARIO 4: Admin Tries Blogger Login
──────────────────────────────────────
1. Navigate to /blogger/login.php
2. Enter admin credentials
3. Role check: not editor ✗
4. Show error: "Use Admin Panel"
5. Display link to /admin/login.php
6. No session created


SCENARIO 5: Editor Accesses Admin Page Directly
────────────────────────────────────────────────
1. Editor somehow gets to /admin/dashboard.php
2. Header checks role: editor ✗
3. Logout editor session
4. Redirect to /blogger/login.php?msg=admin_redirect
5. Show message about correct panel
6. Access denied


SECURITY MATRIX
═══════════════

┌─────────────┬──────────────┬──────────────┬───────────────────┐
│    Role     │ Admin Login  │ Blogger Login│  Admin Dashboard  │
├─────────────┼──────────────┼──────────────┼───────────────────┤
│ super_admin │      ✓       │      ✗       │        ✓          │
│    admin    │      ✓       │      ✗       │        ✓          │
│   editor    │      ✗       │      ✓       │        ✗          │
└─────────────┴──────────────┴──────────────┴───────────────────┘


ERROR MESSAGES
══════════════

Location: /admin/login.php (when editor tries to login)
Message:  "Editors should use the [Blogger Panel] to log in."

Location: /blogger/login.php (when admin tries to login)
Message:  "Invalid credentials or you do not have editor access.
           Admins should use the [Admin Panel]."

Location: Any admin page (when editor accesses directly)
Action:   Silent logout and redirect to /blogger/login.php?msg=admin_redirect
```

---

**Key Benefits:**
- ✅ Clear separation of concerns
- ✅ Role-based access control
- ✅ Multiple layers of security
- ✅ User-friendly error messages
- ✅ Automatic redirects to correct panel
- ✅ Session isolation
- ✅ Activity logging
