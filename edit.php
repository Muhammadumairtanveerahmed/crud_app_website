<?php
require_once 'config/db.php';

$id = intval($_GET['id'] ?? $_POST['id'] ?? 0);
$errors = [];

$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    header("Location: index.php?msg=Product not found");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = $_POST['price'] ?? '';
    $quantity = $_POST['quantity'] ?? '';

    if ($name === '') $errors[] = "Name is required.";
    if (!is_numeric($price) || $price < 0) $errors[] = "Price must be a valid positive number.";
    if (!ctype_digit(strval($quantity))) $errors[] = "Quantity must be a whole number.";

    if (empty($errors)) {
        $stmt = $conn->prepare("UPDATE products SET name=?, description=?, price=?, quantity=? WHERE id=?");
        $stmt->bind_param("ssdii", $name, $description, $price, $quantity, $id);
        if ($stmt->execute()) {
            header("Location: index.php?msg=Product updated successfully");
            exit;
        } else {
            $errors[] = "Database error: " . $conn->error;
        }
        $stmt->close();
    }
    $product['name'] = $name;
    $product['description'] = $description;
    $product['price'] = $price;
    $product['quantity'] = $quantity;
}

$sku = 'PRD-' . str_pad($product['id'], 4, '0', STR_PAD_LEFT);
$pageTitle = 'Edit Product';
$pageSubtitle = 'Editing ' . $sku;
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
        <input type="hidden" name="id" value="<?php echo $product['id']; ?>">

        <div class="form-row">
            <label>Product Name</label>
            <input type="text" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
        </div>

        <div class="form-row">
            <label>Description</label>
            <textarea name="description" rows="4"><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <div class="form-grid">
            <div class="form-row">
                <label>Price (USD)</label>
                <input type="number" step="0.01" min="0" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            </div>
            <div class="form-row">
                <label>Quantity in Stock</label>
                <input type="number" min="0" name="quantity" value="<?php echo htmlspecialchars($product['quantity']); ?>" required>
            </div>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Product</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include 'includes/footer.php'; ?>
