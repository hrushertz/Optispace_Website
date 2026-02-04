-- Fix team_members table id field to use AUTO_INCREMENT
-- This script fixes the "Field 'id' doesn't have a default value" error

-- First, check if the table exists and has data
SELECT 'Checking team_members table...' AS status;

-- Modify the id column to be AUTO_INCREMENT PRIMARY KEY
ALTER TABLE team_members MODIFY COLUMN id INT AUTO_INCREMENT PRIMARY KEY;

-- Verify the change
SHOW CREATE TABLE team_members;

SELECT 'team_members table id field successfully updated to AUTO_INCREMENT' AS status;
