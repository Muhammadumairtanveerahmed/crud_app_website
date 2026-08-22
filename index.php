<?php
require_once 'config/db.php';

$result = $conn->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $result->fetch_all(MYSQLI_ASSOC);

$totalProducts = count($products);
$totalValue = 0;
$lowStock = 0;
foreach ($products as $p) {
    $totalValue += $p['price'] * $p['quantity'];
    if ($p['quantity'] < 10) $lowStock++;
}

$pageTitle = 'Dashboard';
$pageSubtitle = 'Overview of your product inventory';
$topbarAction = '<a href="create.php" class="btn btn-primary">+ Add Product</a>';
include 'includes/header.php';
?>

<?php if (isset($_GET['msg'])): ?>
    <div class="alert"><?php echo htmlspecialchars($_GET['msg']); ?></div>
<?php endif; ?>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-label">Total Products</div>
        <div class="stat-value"><?php echo $totalProducts; ?></div>
    </div>
    <div class="stat-card">
        <div class="stat-label">Inventory Value</div>
        <div class="stat-value">$<?php echo number_format($totalValue, 2); ?></div>
    </div>
    <div class="stat-card <?php echo $lowStock > 0 ? 'stat-warning' : ''; ?>">
        <div class="stat-label">Low Stock Items</div>
        <div class="stat-value"><?php echo $lowStock; ?></div>
    </div>
</div>

<div class="panel">
    <div class="panel-header">
        <h2>Product Catalog</h2>
    </div>
    <table class="product-table">
        <thead>
            <tr>
                <th>SKU</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price</th>
                <th>Stock</th>
                <th>Status</th>
                <th>Added</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $row): ?>
                    <?php
                        $sku = 'PRD-' . str_pad($row['id'], 4, '0', STR_PAD_LEFT);
                        if ($row['quantity'] == 0) {
                            $statusClass = 'status-out'; $statusLabel = 'Out of stock';
                        } elseif ($row['quantity'] < 10) {
                            $statusClass = 'status-low'; $statusLabel = 'Low stock';
                        } else {
                            $statusClass = 'status-ok'; $statusLabel = 'In stock';
                        }
                    ?>
                    <tr>
                        <td class="mono"><?php echo $sku; ?></td>
                        <td class="cell-strong"><?php echo htmlspecialchars($row['name']); ?></td>
                        <td class="cell-muted"><?php echo htmlspecialchars($row['description']); ?></td>
                        <td class="mono">$<?php echo number_format($row['price'], 2); ?></td>
                        <td class="mono"><?php echo $row['quantity']; ?></td>
                        <td><span class="status-badge <?php echo $statusClass; ?>"><?php echo $statusLabel; ?></span></td>
                        <td class="cell-muted mono"><?php echo date('M j, Y', strtotime($row['created_at'])); ?></td>
                        <td class="actions">
                            <a href="edit.php?id=<?php echo $row['id']; ?>" class="icon-btn" title="Edit">Edit</a>
                            <a href="delete.php?id=<?php echo $row['id']; ?>"
                               class="icon-btn icon-btn-danger"
                               title="Delete"
                               onclick="return confirm('Delete this product?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr><td colspan="8" class="empty">No products yet. Click "Add Product" to create your first one.</td></tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include 'includes/footer.php'; ?>
