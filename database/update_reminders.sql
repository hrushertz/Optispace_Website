-- Database Update: Add Call Reschedules and Reminders
-- Date: 2026-02-04

-- Update for pulse_check_submissions
ALTER TABLE pulse_check_submissions 
ADD COLUMN reschedule_date DATETIME NULL AFTER status,
ADD COLUMN reminder_date DATETIME NULL AFTER reschedule_date,
ADD COLUMN reminder_sent TINYINT(1) DEFAULT 0 AFTER reminder_date;

-- Update for inquiry_submissions
ALTER TABLE inquiry_submissions 
ADD COLUMN reschedule_date DATETIME NULL AFTER status,
ADD COLUMN reminder_date DATETIME NULL AFTER reschedule_date,
ADD COLUMN reminder_sent TINYINT(1) DEFAULT 0 AFTER reminder_date;
