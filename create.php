<?php
require_once 'config/db.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if ($name === '') $errors[] = "Name is required.";
    if (!is_numeric($price) || $price < 0) $errors[] = "Price must be a valid positive number.";
    if (!ctype_digit(strval($quantity))) $errors[] = "Quantity must be a whole number.";

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO products (name, description, price, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssdi", $name, $description, $price, $quantity);
        if ($stmt->execute()) {
            header("Location: index.php?msg=Product added successfully");
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
}

$pageTitle = 'Add Product';
$pageSubtitle = 'Create a new catalog entry';
$topbarAction = '<a href="index.php" class="btn btn-secondary">&larr; Back to Dashboard</a>';
include 'includes/header.php';
?>

<?php if (!empty($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach ($errors as $e) echo "<li>" . htmlspecialchars($e) . "</li>"; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="panel form-panel">
    <form method="POST" class="product-form">
        <div class="form-row">
            <label>Product Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($_POST['name'] ?? ''); ?>" placeholder="e.g. Wireless Mouse" required>
        </div>

        <div class="form-row">
            <label>Description</label>
            <textarea name="description" rows="4" placeholder="Short product description"><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
        </div>

        <div class="form-grid">
            <div class="form-row">
                <label>Price (USD)</label>
                <input type="number" step="0.01" min="0" name="price" value="<?php echo htmlspecialchars($_POST['price'] ?? ''); ?>" placeholder="0.00" required>
            </div>
            <div class="form-row">
                <label>Quantity in Stock</label>
                <input type="number" min="0" name="quantity" value="<?php echo htmlspecialchars($_POST['quantity'] ?? '0'); ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Product</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
