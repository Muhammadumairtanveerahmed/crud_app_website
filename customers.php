<?php
include 'config/db.php';

$errors = [];
$message = '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST['name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $status = $_POST['status'] ?? 'active';

    if ($name === '') {
        $errors[] = "Customer name is required.";
    }

    if (!in_array($status, ['active', 'inactive'], true)) {
        $errors[] = "Invalid status selected.";
    }

    if (empty($errors)) {

        $stmt = $conn->prepare(
            "INSERT INTO customers (name, phone, email, address, status)
             VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "sssss",
            $name,
            $phone,
            $email,
            $address,
            $status
        );

        if ($stmt->execute()) {
            $message = "Customer added successfully!";
        } else {
            $errors[] = "Database error: " . $conn->error;
        }

        $stmt->close();
    }
}


/* Get all customers */
$customers = [];

$result = $conn->query(
    "SELECT id, name, phone, email, address, status
     FROM customers
     ORDER BY id DESC"
);

if ($result) {
    while ($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}


$pageTitle = 'Customers';
$pageSubtitle = 'Create and manage customers';

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


<!-- Add Customer -->

<div class="panel">

    <div class="panel-header">
        <h2>Add Customer</h2>
    </div>

    <div class="form-panel">

        <form method="POST" class="product-form">

            <div class="form-row">

                <label for="name">
                    Customer Name
                </label>

                <input
                    type="text"
                    id="name"
                    name="name"
                    placeholder="Enter customer name"
                    required
                >

            </div>


            <div class="form-row">

                <label for="phone">
                    Phone Number
                </label>

                <input
                    type="text"
                    id="phone"
                    name="phone"
                    placeholder="Enter phone number"
                >

            </div>


            <div class="form-row">

                <label for="email">
                    Email
                </label>

                <input
                    type="email"
                    id="email"
                    name="email"
                    placeholder="Enter email address"
                >

            </div>


            <div class="form-row">

                <label for="address">
                    Address
                </label>

                <textarea
                    id="address"
                    name="address"
                    rows="4"
                    placeholder="Enter customer address"
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
                    Save Customer
                </button>

            </div>

        </form>

    </div>

</div>


<!-- Customer List -->

<div class="panel" style="margin-top: 24px;">

    <div class="panel-header">
        <h2>Customers</h2>
    </div>


    <table class="product-table">

        <thead>

            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Address</th>
                <th>Status</th>
            </tr>

        </thead>


        <tbody>

            <?php if (empty($customers)): ?>

                <tr>

                    <td colspan="6" class="empty">
                        No customers found.
                    </td>

                </tr>

            <?php else: ?>

                <?php foreach ($customers as $customer): ?>

                    <tr>

                        <td class="mono">
                            <?php echo htmlspecialchars($customer['id']); ?>
                        </td>

                        <td class="cell-strong">
                            <?php echo htmlspecialchars($customer['name']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($customer['phone']); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($customer['email']); ?>
                        </td>

                        <td class="cell-muted">
                            <?php echo htmlspecialchars($customer['address']); ?>
                        </td>

                        <td>

                            <?php if ($customer['status'] === 'active'): ?>

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