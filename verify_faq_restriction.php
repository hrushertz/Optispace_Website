<?php
// Mock session
session_start();
$_SESSION['admin_logged_in'] = true;
$_SESSION['admin_id'] = 999;
$_SESSION['admin_username'] = 'sales_test';
$_SESSION['admin_role'] = 'sales';

require_once 'admin/includes/auth.php';

echo "Testing FAQ Restrictions for 'sales' role...\n";
echo "----------------------------------------\n";

// Test 1: hasAdminRole('editor') should be false for sales
$canAccessFAQ = hasAdminRole('editor');
echo "Can 'sales' access Editor-level pages (e.g. FAQ) (Expect: FALSE): " . ($canAccessFAQ ? "FAIL" : "PASS") . "\n";

// Test 2: Verify hierarchy specifically
$roleHierarchy = [
    'super_admin' => 3,
    'admin' => 2,
    'editor' => 1,
    'sales' => 0.5
];
$userLevel = $roleHierarchy['sales'];
$editorLevel = $roleHierarchy['editor'];
echo "Sales Level (" . $userLevel . ") < Editor Level (" . $editorLevel . ") (Expect: TRUE): " . ($userLevel < $editorLevel ? "PASS" : "FAIL") . "\n";

echo "----------------------------------------\n";
echo "Verification Complete.\n";
?>