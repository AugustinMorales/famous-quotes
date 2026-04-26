<?php
require_once __DIR__ . '/auth.php';
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="index.php">Famous Quotes Admin</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['authors.php','add_author.php','edit_author.php']) ? 'active' : '' ?>"
                       href="#" data-bs-toggle="dropdown">Authors</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="authors.php">View All Authors</a></li>
                        <li><a class="dropdown-item" href="add_author.php">Add New Author</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle <?= in_array($currentPage, ['quotes.php','add_quote.php','edit_quote.php']) ? 'active' : '' ?>"
                       href="#" data-bs-toggle="dropdown">Quotes</a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="quotes.php">View All Quotes</a></li>
                        <li><a class="dropdown-item" href="add_quote.php">Add New Quote</a></li>
                    </ul>
                </li>
            </ul>
            <?php if (isLoggedIn()): ?>
            <div class="d-flex align-items-center">
                <span class="nav-username">Logged in as <?= htmlspecialchars($_SESSION['admin_username'] ?? 'admin') ?></span>
                <a class="btn-logout" href="logout.php">Logout</a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</nav>
