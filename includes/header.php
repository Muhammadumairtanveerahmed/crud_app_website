<?php
$current = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' · InvenTrack' : 'InvenTrack'; ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="css/style.css">
</head>
<body>

<div class="app">
    <aside class="sidebar">
        <div class="sidebar-brand">
            <span class="brand-mark">IT</span>
            <span class="brand-name">InvenTrack</span>
        </div>
        <nav class="sidebar-nav">
            <a href="index.php" class="nav-item <?php echo in_array($current, ['index.php']) ? 'active' : ''; ?>">
                <span class="nav-dot"></span> Dashboard
            </a>
            <a href="create.php" class="nav-item <?php echo in_array($current, ['create.php','edit.php']) ? 'active' : ''; ?>">
                <span class="nav-dot"></span> Add Product
            </a>
        </nav>
        <div class="sidebar-footer">
            <div class="footer-label">Signed in as</div>
            <div class="footer-user">Administrator</div>
        </div>
    </aside>

    <div class="main">
        <header class="topbar">
            <div class="topbar-title">
                <h1><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) : 'Dashboard'; ?></h1>
                <?php if (isset($pageSubtitle)): ?>
                    <p><?php echo htmlspecialchars($pageSubtitle); ?></p>
                <?php endif; ?>
            </div>
            <?php if (isset($topbarAction)): ?>
                <div class="topbar-action"><?php echo $topbarAction; ?></div>
            <?php endif; ?>
        </header>

        <main class="content">
