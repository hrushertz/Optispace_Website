<?php
/**
 * Check and Create Upload Directories
 */

require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

$pageTitle = "Check Permissions";
include __DIR__ . '/includes/header.php';

$directories = [
    '/assets/img/gallery/' => 'Gallery Images',
    '/assets/img/featured/' => 'Featured Project Images',
    '/downloads/' => 'Download Files'
];

$results = [];

foreach ($directories as $dir => $label) {
    $fullPath = __DIR__ . '/..' . $dir;
    $result = [
        'label' => $label,
        'path' => $dir,
        'exists' => false,
        'writable' => false,
        'created' => false,
        'error' => null
    ];
    
    // Check if directory exists
    if (file_exists($fullPath)) {
        $result['exists'] = true;
        $result['writable'] = is_writable($fullPath);
    } else {
        // Try to create it
        if (mkdir($fullPath, 0755, true)) {
            $result['exists'] = true;
            $result['created'] = true;
            $result['writable'] = is_writable($fullPath);
        } else {
            $result['error'] = "Failed to create directory";
        }
    }
    
    $results[] = $result;
}
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Upload Directories - Permissions Check</h1>
        <p class="page-subtitle">Verify that all required directories exist and are writable</p>
    </div>
</div>

<div class="form-card">
    <div class="form-card-header">
        <h2>Directory Status</h2>
    </div>
    <div class="form-card-body">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Directory</th>
                    <th>Path</th>
                    <th>Status</th>
                    <th>Writable</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($results as $result): ?>
                <tr>
                    <td><strong><?php echo htmlspecialchars($result['label']); ?></strong></td>
                    <td><code><?php echo htmlspecialchars($result['path']); ?></code></td>
                    <td>
                        <?php if ($result['exists']): ?>
                            <span class="badge badge-success">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <polyline points="20 6 9 17 4 12"/>
                                </svg>
                                Exists
                            </span>
                            <?php if ($result['created']): ?>
                            <span class="badge badge-info">Just Created</span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span class="badge badge-danger">
                                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="10"/>
                                    <line x1="15" y1="9" x2="9" y2="15"/>
                                    <line x1="9" y1="9" x2="15" y2="15"/>
                                </svg>
                                Missing
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($result['writable']): ?>
                            <span class="badge badge-success">Yes</span>
                        <?php else: ?>
                            <span class="badge badge-warning">No</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($result['error']): ?>
                            <span style="color: var(--admin-danger);"><?php echo htmlspecialchars($result['error']); ?></span>
                        <?php elseif (!$result['writable']): ?>
                            <code>chmod 755 <?php echo htmlspecialchars($result['path']); ?></code>
                        <?php else: ?>
                            <span style="color: var(--admin-success);">✓ OK</span>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        
        <div style="margin-top: 2rem; padding: 1rem; background: var(--admin-gray-50); border-radius: 8px;">
            <h4 style="margin-top: 0; color: var(--admin-dark);">💡 Tips:</h4>
            <ul style="margin-bottom: 0;">
                <li>All directories should exist and be writable for uploads to work</li>
                <li>If a directory is not writable, run the chmod command shown above in terminal</li>
                <li>On XAMPP, you may need to run: <code>chmod -R 755 /Applications/XAMPP/xamppfiles/htdocs/Optispace_Website/assets/img</code></li>
                <li>After making changes, refresh this page to verify</li>
            </ul>
        </div>
    </div>
</div>

<style>
.badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    padding: 0.25rem 0.5rem;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 4px;
    margin-right: 0.5rem;
}

.badge-success {
    background: #D1FAE5;
    color: #065F46;
}

.badge-danger {
    background: #FEE2E2;
    color: #991B1B;
}

.badge-warning {
    background: #FEF3C7;
    color: #92400E;
}

.badge-info {
    background: #DBEAFE;
    color: #1E40AF;
}

.data-table {
    width: 100%;
    border-collapse: collapse;
}

.data-table th,
.data-table td {
    padding: 0.75rem;
    text-align: left;
    border-bottom: 1px solid var(--admin-gray-200);
}

.data-table th {
    background: var(--admin-gray-50);
    font-weight: 600;
    color: var(--admin-gray-700);
    font-size: 0.875rem;
}

.data-table tbody tr:hover {
    background: var(--admin-gray-50);
}

code {
    background: var(--admin-gray-100);
    padding: 0.15rem 0.4rem;
    border-radius: 3px;
    font-family: 'Monaco', 'Menlo', monospace;
    font-size: 0.85em;
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
