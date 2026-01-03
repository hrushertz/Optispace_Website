<?php
/**
 * Admin Downloads List
 */

$pageTitle = "Downloads";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM downloads WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'downloads', $deleteId, 'Deleted download');
        $successMessage = "Download deleted successfully.";
    } else {
        $errorMessage = "Failed to delete download.";
    }
    $stmt->close();
}

// Handle toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int)$_GET['toggle'];
    $stmt = $conn->prepare("UPDATE downloads SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $toggleId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_status', 'downloads', $toggleId, 'Toggled download status');
        $successMessage = "Download status updated.";
    }
    $stmt->close();
}

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 10;
$offset = ($page - 1) * $perPage;

// Filter by category
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$whereClause = "1=1";
$params = [];
$types = "";

if ($categoryFilter > 0) {
    $whereClause .= " AND d.category_id = ?";
    $params[] = $categoryFilter;
    $types .= "i";
}

if ($searchQuery) {
    $whereClause .= " AND (d.title LIKE ? OR d.description LIKE ?)";
    $searchParam = "%{$searchQuery}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "ss";
}

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM downloads d WHERE {$whereClause}";
$countStmt = $conn->prepare($countQuery);
if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$totalRecords = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $perPage);
$countStmt->close();

// Get downloads
$query = "
    SELECT d.*, c.name as category_name, c.color as category_color
    FROM downloads d 
    LEFT JOIN download_categories c ON d.category_id = c.id 
    WHERE {$whereClause}
    ORDER BY d.created_at DESC 
    LIMIT ?, ?
";
$params[] = $offset;
$params[] = $perPage;
$types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$downloads = $stmt->get_result();
$stmt->close();

// Get categories for filter
$categories = $conn->query("SELECT * FROM download_categories ORDER BY sort_order");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Downloads</h1>
        <p class="page-subtitle">Manage downloadable resources</p>
    </div>
    <div class="page-actions">
        <a href="download-add.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add New Download
        </a>
    </div>
</div>

<?php if (isset($successMessage)): ?>
    <div class="alert alert-success" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
            <polyline points="22 4 12 14.01 9 11.01"/>
        </svg>
        <span><?php echo htmlspecialchars($successMessage); ?></span>
    </div>
<?php endif; ?>

<?php if (isset($errorMessage)): ?>
    <div class="alert alert-danger" data-auto-hide>
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <line x1="12" y1="8" x2="12" y2="12"/>
            <line x1="12" y1="16" x2="12.01" y2="16"/>
        </svg>
        <span><?php echo htmlspecialchars($errorMessage); ?></span>
    </div>
<?php endif; ?>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body" style="padding: 1rem 1.5rem;">
        <form method="GET" class="filters-form" style="display: flex; gap: 1rem; align-items: center; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 200px;">
                <input type="text" name="search" class="form-control" placeholder="Search downloads..." 
                       value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>
            <div style="min-width: 180px;">
                <select name="category" class="form-control form-select">
                    <option value="">All Categories</option>
                    <?php while ($cat = $categories->fetch_assoc()): ?>
                        <option value="<?php echo $cat['id']; ?>" <?php echo $categoryFilter == $cat['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cat['name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-secondary">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                Filter
            </button>
            <?php if ($searchQuery || $categoryFilter): ?>
                <a href="downloads.php" class="btn btn-secondary">Clear</a>
            <?php endif; ?>
        </form>
    </div>
</div>

<!-- Downloads Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Downloads (<?php echo $totalRecords; ?>)</h3>
    </div>
    <div class="table-container">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Resource</th>
                    <th>Category</th>
                    <th>File Info</th>
                    <th>Downloads</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($downloads->num_rows > 0): ?>
                    <?php while ($download = $downloads->fetch_assoc()): ?>
                        <tr>
                            <td>
                                <div class="table-item">
                                    <div class="table-item-icon">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                            <polyline points="14 2 14 8 20 8"/>
                                        </svg>
                                    </div>
                                    <div class="table-item-info">
                                        <h4><?php echo htmlspecialchars($download['title']); ?></h4>
                                        <p><?php echo htmlspecialchars(substr($download['description'], 0, 60)); ?>...</p>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: <?php echo htmlspecialchars($download['category_color']); ?>15; color: <?php echo htmlspecialchars($download['category_color']); ?>;">
                                    <?php echo htmlspecialchars($download['category_name']); ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-size: 0.875rem; color: var(--admin-gray-600);">
                                    <?php echo htmlspecialchars($download['file_type']); ?> • <?php echo htmlspecialchars($download['file_size']); ?>
                                </span>
                            </td>
                            <td>
                                <span style="font-weight: 600;"><?php echo number_format($download['download_count']); ?></span>
                            </td>
                            <td>
                                <?php if ($download['is_active']): ?>
                                    <span class="badge badge-success">Active</span>
                                <?php else: ?>
                                    <span class="badge badge-gray">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="download-edit.php?id=<?php echo $download['id']; ?>" 
                                       class="btn btn-secondary btn-sm btn-icon" title="Edit">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                        </svg>
                                    </a>
                                    <a href="?toggle=<?php echo $download['id']; ?>" 
                                       class="btn btn-secondary btn-sm btn-icon" 
                                       title="<?php echo $download['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                                        <?php if ($download['is_active']): ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                                                <circle cx="12" cy="12" r="3"/>
                                            </svg>
                                        <?php else: ?>
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                                                <line x1="1" y1="1" x2="23" y2="23"/>
                                            </svg>
                                        <?php endif; ?>
                                    </a>
                                    <a href="?delete=<?php echo $download['id']; ?>" 
                                       class="btn btn-danger btn-sm btn-icon" 
                                       title="Delete"
                                       onclick="return confirm('Are you sure you want to delete this download?');">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <polyline points="3 6 5 6 21 6"/>
                                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                                        </svg>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
                                        <polyline points="14 2 14 8 20 8"/>
                                    </svg>
                                </div>
                                <h3>No Downloads Found</h3>
                                <p>Get started by adding your first downloadable resource.</p>
                                <a href="download-add.php" class="btn btn-primary">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <line x1="12" y1="5" x2="12" y2="19"/>
                                        <line x1="5" y1="12" x2="19" y2="12"/>
                                    </svg>
                                    Add Download
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if ($totalPages > 1): ?>
        <div class="card-footer">
            <div class="pagination">
                <a href="?page=<?php echo max(1, $page - 1); ?><?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $searchQuery ? '&search='.urlencode($searchQuery) : ''; ?>" 
                   class="pagination-btn" <?php echo $page <= 1 ? 'disabled' : ''; ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="15 18 9 12 15 6"/>
                    </svg>
                </a>
                
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <?php if ($i == 1 || $i == $totalPages || ($i >= $page - 2 && $i <= $page + 2)): ?>
                        <a href="?page=<?php echo $i; ?><?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $searchQuery ? '&search='.urlencode($searchQuery) : ''; ?>" 
                           class="pagination-btn <?php echo $i == $page ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php elseif ($i == $page - 3 || $i == $page + 3): ?>
                        <span class="pagination-btn" style="border: none;">...</span>
                    <?php endif; ?>
                <?php endfor; ?>
                
                <a href="?page=<?php echo min($totalPages, $page + 1); ?><?php echo $categoryFilter ? '&category='.$categoryFilter : ''; ?><?php echo $searchQuery ? '&search='.urlencode($searchQuery) : ''; ?>" 
                   class="pagination-btn" <?php echo $page >= $totalPages ? 'disabled' : ''; ?>>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="9 18 15 12 9 6"/>
                    </svg>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
