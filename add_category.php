<?php
include 'config/db.php';

$errors = [];
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if ($name === '') {
        $errors[] = "Category name is required.";
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = "Invalid status selected.";
    }

    if (empty($errors)) {

        $stmt = $conn->prepare(
            "INSERT INTO categories (name, description, status)
             VALUES (?, ?, ?)"
        );

        $stmt->bind_param("sss", $name, $description, $status);

        if ($stmt->execute()) {
            $message = "Category added successfully!";
        } else {
            $errors[] = "Database error: " . $conn->error;
        }

        $stmt->close();
    }
}


/* Get all categories */
$categories = [];

$result = $conn->query(
    "SELECT id, name, description, status
     FROM categories
     ORDER BY id DESC"
);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = $row;
    }
}


$pageTitle = 'Add Category';
$pageSubtitle = 'Create and manage product categories';

$topbarAction =
    '<a href="index.php" class="btn btn-secondary">&larr; Back to Dashboard</a>';

include 'includes/header.php';
?>


<?php if (!empty($message)): ?>

    <div class="alert">
        <?php echo htmlspecialchars($message); ?>
    </div>

<?php endif; ?>


<?php if (!empty($errors)): ?>

    <div class="alert alert-error">

        <ul>

            <?php foreach ($errors as $error): ?>

                <li>
                    <?php echo htmlspecialchars($error); ?>
                </li>

            <?php endforeach; ?>

        </ul>

    </div>

<?php endif; ?>


<!-- Add Category Form -->

<div class="panel">

    <div class="panel-header">
        <h2>Add Category</h2>
    </div>

    <div class="form-panel">

        <form method="POST" class="product-form">

            <div class="form-row">

                <label for="name">
                    Category Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter category name"
                    required
                >

            </div>


            <div class="form-row">

                <label for="description">
                    Description
                </label>

                <textarea
                    id="description"
                    name="description"
                    rows="5"
                    placeholder="Enter category description"
                ></textarea>

            </div>


            <div class="form-row">

                <label for="status">
                    Status
                </label>

                <select
                    id="status"
                    name="status"
                    style="padding: 10px 12px; border: 1px solid var(--border); border-radius: 7px; font-size: 13.5px; font-family: inherit; background: var(--surface);"
                >

                    <option value="active">
                        Active
                    </option>

                    <option value="inactive">
                        Inactive
                    </option>

                </select>

            </div>


            <div class="form-actions">

                <button
                    type="submit"
                    class="btn btn-primary"
                >
                    Save Category
                </button>

            </div>

        </form>

    </div>

</div>


<!-- Categories List -->

<div class="panel" style="margin-top: 24px;">

    <div class="panel-header">
        <h2>Categories</h2>
    </div>


    <table class="product-table">

        <thead>

            <tr>

                <th>ID</th>

                <th>Category Name</th>

                <th>Description</th>

                <th>Status</th>

            </tr>

        </thead>


        <tbody>

            <?php if (empty($categories)): ?>

                <tr>

                    <td
                        colspan="4"
                        class="empty"
                    >
                        No categories found.
                    </td>

                </tr>

            <?php else: ?>

                <?php foreach ($categories as $category): ?>

                    <tr>

                        <td class="mono">
                            <?php echo htmlspecialchars($category['id']); ?>
                        </td>


                        <td class="cell-strong">
                            <?php echo htmlspecialchars($category['name']); ?>
                        </td>


                        <td class="cell-muted">
                            <?php echo htmlspecialchars($category['description']); ?>
                        </td>


                        <td>

                            <?php if ($category['status'] === 'active'): ?>

                                <span class="status-badge status-ok">
                                    Active
                                </span>

                            <?php else: ?>

                                <span class="status-badge status-out">
                                    Inactive
                                </span>

                            <?php endif; ?>

                        </td>

                    </tr>

                <?php endforeach; ?>

            <?php endif; ?>

        </tbody>

    </table>

</div>


<?php include 'includes/footer.php'; ?>