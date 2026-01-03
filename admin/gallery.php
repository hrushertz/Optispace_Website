<?php
/**
 * Admin Gallery Items List
 */

$pageTitle = "Gallery Items";
require_once __DIR__ . '/includes/auth.php';
requireAdminLogin();

require_once __DIR__ . '/../database/db_config.php';

$conn = getDBConnection();

// Handle delete action
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $deleteId = (int)$_GET['delete'];
    $stmt = $conn->prepare("DELETE FROM gallery_items WHERE id = ?");
    $stmt->bind_param("i", $deleteId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'delete', 'gallery_items', $deleteId, 'Deleted gallery item');
        $successMessage = "Gallery item deleted successfully.";
    } else {
        $errorMessage = "Failed to delete gallery item.";
    }
    $stmt->close();
}

// Handle toggle status
if (isset($_GET['toggle']) && is_numeric($_GET['toggle'])) {
    $toggleId = (int)$_GET['toggle'];
    $stmt = $conn->prepare("UPDATE gallery_items SET is_active = NOT is_active WHERE id = ?");
    $stmt->bind_param("i", $toggleId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_status', 'gallery_items', $toggleId, 'Toggled gallery item status');
        $successMessage = "Gallery item status updated.";
    }
    $stmt->close();
}

// Handle toggle featured
if (isset($_GET['feature']) && is_numeric($_GET['feature'])) {
    $featureId = (int)$_GET['feature'];
    $stmt = $conn->prepare("UPDATE gallery_items SET is_featured = NOT is_featured WHERE id = ?");
    $stmt->bind_param("i", $featureId);
    
    if ($stmt->execute()) {
        logAdminActivity($_SESSION['admin_id'], 'toggle_featured', 'gallery_items', $featureId, 'Toggled gallery item featured status');
        $successMessage = "Gallery item featured status updated.";
    }
    $stmt->close();
}

// Pagination
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Filter by category
$categoryFilter = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Build query
$whereClause = "1=1";
$params = [];
$types = "";

if ($categoryFilter > 0) {
    $whereClause .= " AND g.category_id = ?";
    $params[] = $categoryFilter;
    $types .= "i";
}

if ($searchQuery) {
    $whereClause .= " AND (g.title LIKE ? OR g.description LIKE ? OR g.location LIKE ?)";
    $searchParam = "%{$searchQuery}%";
    $params[] = $searchParam;
    $params[] = $searchParam;
    $params[] = $searchParam;
    $types .= "sss";
}

// Get total count
$countQuery = "SELECT COUNT(*) as total FROM gallery_items g WHERE {$whereClause}";
$countStmt = $conn->prepare($countQuery);
if (!empty($params)) {
    $countStmt->bind_param($types, ...$params);
}
$countStmt->execute();
$totalRecords = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalRecords / $perPage);
$countStmt->close();

// Get gallery items
$query = "
    SELECT g.*, c.name as category_name, c.color as category_color, c.bg_class
    FROM gallery_items g 
    LEFT JOIN gallery_categories c ON g.category_id = c.id 
    WHERE {$whereClause}
    ORDER BY g.sort_order ASC, g.created_at DESC 
    LIMIT ?, ?
";
$params[] = $offset;
$params[] = $perPage;
$types .= "ii";

$stmt = $conn->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$galleryItems = $stmt->get_result();
$stmt->close();

// Get categories for filter
$categories = $conn->query("SELECT * FROM gallery_categories WHERE is_active = 1 ORDER BY sort_order ASC");

$conn->close();

include __DIR__ . '/includes/header.php';
?>

<div class="page-header">
    <div class="page-title-section">
        <h1 class="page-title">Gallery Items</h1>
        <p class="page-subtitle">Manage your project gallery images and content</p>
    </div>
    <div class="page-actions">
        <a href="gallery-add.php" class="btn btn-primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"/>
                <line x1="5" y1="12" x2="19" y2="12"/>
            </svg>
            Add Gallery Item
        </a>
    </div>
</div>

<?php if (isset($successMessage)): ?>
<div class="alert alert-success">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/>
        <polyline points="22 4 12 14.01 9 11.01"/>
    </svg>
    <?php echo htmlspecialchars($successMessage); ?>
</div>
<?php endif; ?>

<?php if (isset($errorMessage)): ?>
<div class="alert alert-danger">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <circle cx="12" cy="12" r="10"/>
        <line x1="15" y1="9" x2="9" y2="15"/>
        <line x1="9" y1="9" x2="15" y2="15"/>
    </svg>
    <?php echo htmlspecialchars($errorMessage); ?>
</div>
<?php endif; ?>

<!-- Filters -->
<div class="filter-bar">
    <form class="filter-form" method="GET">
        <div class="filter-group">
            <div class="search-box">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"/>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" placeholder="Search gallery items..." value="<?php echo htmlspecialchars($searchQuery); ?>">
            </div>
            <select name="category" class="filter-select">
                <option value="0">All Categories</option>
                <?php while ($cat = $categories->fetch_assoc()): ?>
                <option value="<?php echo $cat['id']; ?>" <?php echo $categoryFilter == $cat['id'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($cat['name']); ?>
                </option>
                <?php endwhile; ?>
            </select>
            <button type="submit" class="btn btn-secondary">Filter</button>
            <?php if ($searchQuery || $categoryFilter): ?>
            <a href="gallery.php" class="btn btn-ghost">Clear</a>
            <?php endif; ?>
        </div>
    </form>
    <div class="filter-info">
        Showing <?php echo $totalRecords; ?> item<?php echo $totalRecords !== 1 ? 's' : ''; ?>
    </div>
</div>

<!-- Gallery Grid -->
<div class="gallery-admin-grid">
    <?php if ($galleryItems->num_rows > 0): ?>
        <?php while ($item = $galleryItems->fetch_assoc()): ?>
        <div class="gallery-admin-card <?php echo $item['is_active'] ? '' : 'inactive'; ?>">
            <div class="gallery-card-image" style="background: <?php echo $item['bg_class'] ? '' : 'linear-gradient(135deg, #FEF3E2 0%, #FDE68A 100%)'; ?>" data-bg="<?php echo htmlspecialchars($item['bg_class']); ?>">
                <?php if ($item['image_path'] && file_exists(__DIR__ . '/../' . $item['image_path'])): ?>
                    <img src="../<?php echo htmlspecialchars($item['image_path']); ?>" alt="<?php echo htmlspecialchars($item['title']); ?>">
                <?php else: ?>
                    <div class="placeholder-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                            <circle cx="8.5" cy="8.5" r="1.5"/>
                            <polyline points="21 15 16 10 5 21"/>
                        </svg>
                    </div>
                <?php endif; ?>
                
                <?php if ($item['is_featured']): ?>
                <span class="featured-badge">
                    <svg viewBox="0 0 24 24" fill="currentColor" stroke="none" width="12" height="12">
                        <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                    </svg>
                    Featured
                </span>
                <?php endif; ?>
                
                <div class="card-actions-overlay">
                    <a href="gallery-edit.php?id=<?php echo $item['id']; ?>" class="action-btn" title="Edit">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/>
                            <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"/>
                        </svg>
                    </a>
                    <a href="?feature=<?php echo $item['id']; ?>" class="action-btn" title="<?php echo $item['is_featured'] ? 'Remove from Featured' : 'Mark as Featured'; ?>">
                        <svg viewBox="0 0 24 24" fill="<?php echo $item['is_featured'] ? 'currentColor' : 'none'; ?>" stroke="currentColor" stroke-width="2">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                    </a>
                    <a href="?toggle=<?php echo $item['id']; ?>" class="action-btn" title="<?php echo $item['is_active'] ? 'Deactivate' : 'Activate'; ?>">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <?php if ($item['is_active']): ?>
                            <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/>
                            <circle cx="12" cy="12" r="3"/>
                            <?php else: ?>
                            <path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"/>
                            <line x1="1" y1="1" x2="23" y2="23"/>
                            <?php endif; ?>
                        </svg>
                    </a>
                    <a href="?delete=<?php echo $item['id']; ?>" class="action-btn danger" title="Delete" onclick="return confirm('Are you sure you want to delete this gallery item?');">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <polyline points="3 6 5 6 21 6"/>
                            <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"/>
                            <line x1="10" y1="11" x2="10" y2="17"/>
                            <line x1="14" y1="11" x2="14" y2="17"/>
                        </svg>
                    </a>
                </div>
            </div>
            <div class="gallery-card-content">
                <span class="category-tag" style="background: <?php echo $item['category_color']; ?>20; color: <?php echo $item['category_color']; ?>">
                    <?php echo htmlspecialchars($item['category_name']); ?>
                </span>
                <h3 class="card-title"><?php echo htmlspecialchars($item['title']); ?></h3>
                <p class="card-description"><?php echo htmlspecialchars(substr($item['description'], 0, 80)); ?><?php echo strlen($item['description']) > 80 ? '...' : ''; ?></p>
                <?php if ($item['location']): ?>
                <div class="card-meta">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/>
                        <circle cx="12" cy="10" r="3"/>
                    </svg>
                    <?php echo htmlspecialchars($item['location']); ?>
                </div>
                <?php endif; ?>
            </div>
            <?php if (!$item['is_active']): ?>
            <div class="inactive-overlay">
                <span>Inactive</span>
            </div>
            <?php endif; ?>
        </div>
        <?php endwhile; ?>
    <?php else: ?>
        <div class="empty-state">
            <div class="empty-icon">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="3" width="18" height="18" rx="2" ry="2"/>
                    <circle cx="8.5" cy="8.5" r="1.5"/>
                    <polyline points="21 15 16 10 5 21"/>
                </svg>
            </div>
            <h3>No gallery items found</h3>
            <p>Start by adding your first gallery item</p>
            <a href="gallery-add.php" class="btn btn-primary">Add Gallery Item</a>
        </div>
    <?php endif; ?>
</div>

<?php if ($totalPages > 1): ?>
<!-- Pagination -->
<div class="pagination">
    <?php if ($page > 1): ?>
    <a href="?page=<?php echo $page - 1; ?>&category=<?php echo $categoryFilter; ?>&search=<?php echo urlencode($searchQuery); ?>" class="pagination-btn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="15 18 9 12 15 6"/>
        </svg>
        Previous
    </a>
    <?php endif; ?>
    
    <div class="pagination-pages">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
        <a href="?page=<?php echo $i; ?>&category=<?php echo $categoryFilter; ?>&search=<?php echo urlencode($searchQuery); ?>" class="pagination-page <?php echo $i === $page ? 'active' : ''; ?>">
            <?php echo $i; ?>
        </a>
        <?php endfor; ?>
    </div>
    
    <?php if ($page < $totalPages): ?>
    <a href="?page=<?php echo $page + 1; ?>&category=<?php echo $categoryFilter; ?>&search=<?php echo urlencode($searchQuery); ?>" class="pagination-btn">
        Next
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <polyline points="9 18 15 12 9 6"/>
        </svg>
    </a>
    <?php endif; ?>
</div>
<?php endif; ?>

<style>
/* Gallery Admin Grid */
.gallery-admin-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.gallery-admin-card {
    background: var(--admin-white);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-sm);
    border: 1px solid var(--admin-gray-200);
    transition: all var(--transition-normal);
    position: relative;
}

.gallery-admin-card:hover {
    box-shadow: var(--shadow-lg);
    transform: translateY(-2px);
}

.gallery-admin-card.inactive {
    opacity: 0.7;
}

.gallery-card-image {
    position: relative;
    aspect-ratio: 4/3;
    overflow: hidden;
    display: flex;
    align-items: center;
    justify-content: center;
}

.gallery-card-image[data-bg="greenfield-bg"] { background: linear-gradient(135deg, #FEF3E2 0%, #FDE68A 100%); }
.gallery-card-image[data-bg="brownfield-bg"] { background: linear-gradient(135deg, #DBEAFE 0%, #93C5FD 100%); }
.gallery-card-image[data-bg="layout-bg"] { background: linear-gradient(135deg, #D1FAE5 0%, #6EE7B7 100%); }
.gallery-card-image[data-bg="process-bg"] { background: linear-gradient(135deg, #E2E8F0 0%, #CBD5E1 100%); }

.gallery-card-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.placeholder-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.placeholder-icon svg {
    width: 48px;
    height: 48px;
    color: var(--admin-gray-400);
    opacity: 0.5;
}

.featured-badge {
    position: absolute;
    top: 0.75rem;
    left: 0.75rem;
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    background: var(--admin-primary);
    color: white;
    padding: 0.35rem 0.65rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
}

.card-actions-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(15, 23, 42, 0.75);
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
    opacity: 0;
    transition: opacity var(--transition-normal);
}

.gallery-admin-card:hover .card-actions-overlay {
    opacity: 1;
}

.action-btn {
    width: 40px;
    height: 40px;
    background: var(--admin-white);
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--admin-gray-700);
    transition: all var(--transition-fast);
}

.action-btn:hover {
    background: var(--admin-primary);
    color: white;
}

.action-btn.danger:hover {
    background: var(--admin-danger);
}

.action-btn svg {
    width: 18px;
    height: 18px;
}

.gallery-card-content {
    padding: 1.25rem;
}

.category-tag {
    display: inline-block;
    padding: 0.25rem 0.65rem;
    border-radius: var(--radius-full);
    font-size: 0.7rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    margin-bottom: 0.75rem;
}

.card-title {
    font-size: 1rem;
    font-weight: 600;
    color: var(--admin-dark);
    margin-bottom: 0.5rem;
    line-height: 1.4;
}

.card-description {
    font-size: 0.85rem;
    color: var(--admin-gray-600);
    line-height: 1.5;
    margin-bottom: 0.75rem;
}

.card-meta {
    display: flex;
    align-items: center;
    gap: 0.35rem;
    font-size: 0.8rem;
    color: var(--admin-gray-500);
}

.card-meta svg {
    width: 14px;
    height: 14px;
}

.inactive-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
}

.inactive-overlay span {
    background: var(--admin-gray-700);
    color: white;
    padding: 0.5rem 1rem;
    border-radius: var(--radius-full);
    font-size: 0.85rem;
    font-weight: 600;
}

/* Filter Bar */
.filter-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 1rem;
    flex-wrap: wrap;
}

.filter-form {
    flex: 1;
}

.filter-group {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    flex-wrap: wrap;
}

.search-box {
    position: relative;
    flex: 1;
    min-width: 250px;
}

.search-box svg {
    position: absolute;
    left: 1rem;
    top: 50%;
    transform: translateY(-50%);
    width: 18px;
    height: 18px;
    color: var(--admin-gray-400);
}

.search-box input {
    width: 100%;
    padding: 0.75rem 1rem 0.75rem 2.75rem;
    border: 1px solid var(--admin-gray-200);
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    background: var(--admin-white);
    transition: all var(--transition-fast);
}

.search-box input:focus {
    outline: none;
    border-color: var(--admin-primary);
    box-shadow: 0 0 0 3px var(--admin-primary-light);
}

.filter-select {
    padding: 0.75rem 2rem 0.75rem 1rem;
    border: 1px solid var(--admin-gray-200);
    border-radius: var(--radius-md);
    font-size: 0.9rem;
    background: var(--admin-white);
    cursor: pointer;
    appearance: none;
    background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' viewBox='0 0 24 24' fill='none' stroke='%2364748B' stroke-width='2'%3E%3Cpolyline points='6 9 12 15 18 9'%3E%3C/polyline%3E%3C/svg%3E");
    background-repeat: no-repeat;
    background-position: right 0.75rem center;
}

.filter-select:focus {
    outline: none;
    border-color: var(--admin-primary);
}

.filter-info {
    font-size: 0.9rem;
    color: var(--admin-gray-500);
}

/* Empty State */
.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 4rem 2rem;
    background: var(--admin-white);
    border-radius: var(--radius-lg);
    border: 2px dashed var(--admin-gray-200);
}

.empty-icon {
    width: 80px;
    height: 80px;
    margin: 0 auto 1.5rem;
    background: var(--admin-gray-100);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.empty-icon svg {
    width: 40px;
    height: 40px;
    color: var(--admin-gray-400);
}

.empty-state h3 {
    font-size: 1.25rem;
    color: var(--admin-dark);
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: var(--admin-gray-500);
    margin-bottom: 1.5rem;
}

/* Responsive */
@media (max-width: 1200px) {
    .gallery-admin-grid {
        grid-template-columns: repeat(3, 1fr);
    }
}

@media (max-width: 900px) {
    .gallery-admin-grid {
        grid-template-columns: repeat(2, 1fr);
    }
}

@media (max-width: 600px) {
    .gallery-admin-grid {
        grid-template-columns: 1fr;
    }
    
    .filter-group {
        flex-direction: column;
        width: 100%;
    }
    
    .search-box {
        width: 100%;
    }
    
    .filter-select {
        width: 100%;
    }
}
</style>

<?php include __DIR__ . '/includes/footer.php'; ?>
