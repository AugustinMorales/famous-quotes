<?php
require_once 'includes/auth.php';
requireLogin();
require_once 'includes/db.php';

$db = getDB();
$msg = $_GET['msg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $db->prepare("DELETE FROM quotes WHERE id = ?");
    $stmt->execute([(int)$_POST['delete_id']]);
    header('Location: quotes.php?msg=deleted'); exit;
}

$quotes = $db->query("
    SELECT q.*, a.name AS author_name FROM quotes q
    JOIN authors a ON q.author_id = a.id
    ORDER BY q.created_at DESC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quotes - Famous Quotes Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">Quotes</h1>
        <a href="add_quote.php" class="btn btn-dark btn-sm">Add New Quote</a>
    </div>

    <?php if ($msg === 'added'): ?>
        <div class="alert alert-success">Quote added successfully.</div>
    <?php elseif ($msg === 'updated'): ?>
        <div class="alert alert-success">Quote updated successfully.</div>
    <?php elseif ($msg === 'deleted'): ?>
        <div class="alert alert-info">Quote deleted.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">All Quotes (<?= count($quotes) ?>)</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Quote</th>
                        <th>Author</th>
                        <th>Category</th>
                        <th>Year</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($quotes)): ?>
                        <tr><td colspan="6" class="text-center text-muted py-3">No quotes yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($quotes as $i => $q): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td class="quote-text-cell" title="<?= htmlspecialchars($q['quote_text']) ?>">
                            <?= htmlspecialchars($q['quote_text']) ?>
                        </td>
                        <td><?= htmlspecialchars($q['author_name']) ?></td>
                        <td><?= htmlspecialchars($q['category']) ?></td>
                        <td><?= htmlspecialchars($q['year_said'] ?? '') ?></td>
                        <td>
                            <a href="edit_quote.php?id=<?= $q['id'] ?>" class="btn btn-outline-secondary btn-sm me-1">Edit</a>
                            <form method="POST" style="display:inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this quote?')">
                                <input type="hidden" name="delete_id" value="<?= $q['id'] ?>">
                                <button type="submit" class="btn btn-outline-danger btn-sm">Delete</button>
                            </form>
                        </td>
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
