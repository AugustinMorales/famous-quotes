<?php
require_once 'includes/auth.php';
requireLogin();
require_once 'includes/db.php';

$db = getDB();
$authorCount = $db->query("SELECT COUNT(*) FROM authors")->fetchColumn();
$quoteCount  = $db->query("SELECT COUNT(*) FROM quotes")->fetchColumn();
$catCount    = $db->query("SELECT COUNT(DISTINCT category) FROM quotes")->fetchColumn();

$recentQuotes = $db->query("
    SELECT q.quote_text, a.name AS author_name, q.category, q.created_at
    FROM quotes q JOIN authors a ON q.author_id = a.id
    ORDER BY q.created_at DESC LIMIT 5
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Famous Quotes Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">Dashboard</h1>
        <div>
            <a href="add_author.php" class="btn btn-secondary btn-sm me-2">Add Author</a>
            <a href="add_quote.php" class="btn btn-dark btn-sm">Add Quote</a>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-4">
            <div class="stat-box">
                <div class="number"><?= $authorCount ?></div>
                <div class="label">Authors</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="number"><?= $quoteCount ?></div>
                <div class="label">Quotes</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="stat-box">
                <div class="number"><?= $catCount ?></div>
                <div class="label">Categories</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">Recent Quotes</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>Quote</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Date Added</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($recentQuotes)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-3">No quotes added yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($recentQuotes as $q): ?>
                    <tr>
                        <td class="quote-text-cell"><?= htmlspecialchars($q['quote_text']) ?></td>
                        <td><?= htmlspecialchars($q['author_name']) ?></td>
                        <td><?= htmlspecialchars($q['category']) ?></td>
                        <td><?= date('M j, Y', strtotime($q['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
