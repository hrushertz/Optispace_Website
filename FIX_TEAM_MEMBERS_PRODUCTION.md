# Fix Team Members ID Field on Production

## Problem
Error when adding team members: **"Field 'id' doesn't have a default value"**

This occurs because the `team_members` table's `id` field is missing the `AUTO_INCREMENT` attribute on the production database.

## Solution

### Method 1: Run SQL Migration (Recommended)

1. **Access your production database** (via phpMyAdmin, MySQL Workbench, or command line)

2. **Run the migration script:**
   ```bash
   mysql -u your_username -p your_database_name < database/fix-team-members-id.sql
   ```
   
   Or manually execute this SQL:
   ```sql
   ALTER TABLE team_members MODIFY COLUMN id INT AUTO_INCREMENT;
   ```

3. **Verify the fix:**
   ```sql
   SHOW CREATE TABLE team_members;
   ```
   
   You should see `id INT AUTO_INCREMENT PRIMARY KEY` in the output.

### Method 2: Manual Fix via phpMyAdmin

1. Log into phpMyAdmin on your production server
2. Select your database
3. Click on the `team_members` table
4. Go to the "Structure" tab
5. Find the `id` field and click "Change"
6. Check the "A_I" (Auto Increment) checkbox
7. Click "Save"

### Method 3: Quick SQL Command

If you have direct database access:

```sql
ALTER TABLE team_members MODIFY COLUMN id INT AUTO_INCREMENT;
```

## Verification

After applying the fix, try adding a team member through the admin panel. The error should be resolved.

## Prevention

This issue typically occurs when:
- Tables are created manually without following the schema.sql file
- Database migrations are not properly applied
- The table structure was altered incorrectly

Always use the official `database/schema.sql` file when setting up new databases to avoid such issues.

## Related Files
- Migration Script: [database/fix-team-members-id.sql](database/fix-team-members-id.sql)
- Schema Reference: [database/schema.sql](database/schema.sql)
- Admin Interface: [admin/team-member-add.php](admin/team-member-add.php)
