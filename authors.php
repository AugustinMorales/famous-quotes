<?php
require_once 'includes/auth.php';
requireLogin();
require_once 'includes/db.php';

$db = getDB();
$msg = $_GET['msg'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    $stmt = $db->prepare("DELETE FROM authors WHERE id = ?");
    $stmt->execute([(int)$_POST['delete_id']]);
    header('Location: authors.php?msg=deleted'); exit;
}

$authors = $db->query("
    SELECT a.*, COUNT(q.id) AS quote_count
    FROM authors a LEFT JOIN quotes q ON q.author_id = a.id
    GROUP BY a.id ORDER BY a.name ASC
")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Authors - Famous Quotes Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">Authors</h1>
        <a href="add_author.php" class="btn btn-dark btn-sm">Add New Author</a>
    </div>

    <?php if ($msg === 'added'): ?>
        <div class="alert alert-success">Author added successfully.</div>
    <?php elseif ($msg === 'updated'): ?>
        <div class="alert alert-success">Author updated successfully.</div>
    <?php elseif ($msg === 'deleted'): ?>
        <div class="alert alert-info">Author deleted.</div>
    <?php endif; ?>

    <div class="card">
        <div class="card-header">All Authors (<?= count($authors) ?>)</div>
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Nationality</th>
                        <th>Born</th>
                        <th>Died</th>
                        <th>Quotes</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($authors)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-3">No authors yet.</td></tr>
                    <?php endif; ?>
                    <?php foreach ($authors as $i => $a): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><strong><?= htmlspecialchars($a['name']) ?></strong></td>
                        <td><?= htmlspecialchars($a['nationality'] ?? '') ?></td>
                        <td><?= htmlspecialchars($a['birth_year'] ?? '') ?></td>
                        <td><?= htmlspecialchars($a['death_year'] ?? '') ?></td>
                        <td><?= $a['quote_count'] ?></td>
                        <td>
                            <a href="edit_author.php?id=<?= $a['id'] ?>" class="btn btn-outline-secondary btn-sm me-1">Edit</a>
                            <form method="POST" style="display:inline;"
                                  onsubmit="return confirm('Are you sure you want to delete this author? All their quotes will also be deleted.')">
                                <input type="hidden" name="delete_id" value="<?= $a['id'] ?>">
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
