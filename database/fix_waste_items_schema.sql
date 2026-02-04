-- Fix waste_items table schema
-- This script re-creates the table to ensure 'id' is AUTO_INCREMENT PRIMARY KEY
-- and regenerates IDs to resolve any duplicates.

-- 1. Create temporary table based on existing structure
CREATE TABLE IF NOT EXISTS waste_items_temp LIKE waste_items;

-- 2. Ensure the ID column in temp table is AUTO_INCREMENT PRIMARY KEY
ALTER TABLE waste_items_temp MODIFY id INT AUTO_INCREMENT PRIMARY KEY;

-- 3. Copy data from original table to temp table
-- We explicitly exclude 'id' to let MySQL regenerate unique, sequential IDs
INSERT INTO waste_items_temp (
    title, 
    icon_svg, 
    problem_text, 
    solution_text, 
    impact_text, 
    sort_order, 
    is_active, 
    created_by, 
    created_at, 
    updated_at
)
SELECT 
    title, 
    icon_svg, 
    problem_text, 
    solution_text, 
    impact_text, 
    sort_order, 
    is_active, 
    created_by, 
    created_at, 
    updated_at
FROM waste_items;

-- 4. Swap tables safely
RENAME TABLE waste_items TO waste_items_old, waste_items_temp TO waste_items;

-- 5. Drop the old table
DROP TABLE waste_items_old;
