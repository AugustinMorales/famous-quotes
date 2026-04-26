<?php
require_once 'includes/auth.php';
requireLogin();
require_once 'includes/db.php';

$db = getDB();
$authors = $db->query("SELECT id, name FROM authors ORDER BY name ASC")->fetchAll();
$dbCategories = $db->query("SELECT DISTINCT category FROM quotes ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
$predefined = ['Business','Education','Friendship','History','Humor','Inspiration','Leadership','Life','Love','Motivation','Nature','Philosophy','Politics','Science','Success','Wisdom'];
$allCategories = array_unique(array_merge($dbCategories, $predefined));
sort($allCategories);

$errors = [];
$values = ['quote_text'=>'','author_id'=>'','category'=>'','custom_category'=>'','year_said'=>'','source'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['quote_text']      = trim($_POST['quote_text'] ?? '');
    $values['author_id']       = (int)($_POST['author_id'] ?? 0);
    $values['category']        = trim($_POST['category'] ?? '');
    $values['custom_category'] = trim($_POST['custom_category'] ?? '');
    $values['year_said']       = trim($_POST['year_said'] ?? '');
    $values['source']          = trim($_POST['source'] ?? '');
    $finalCat = ($values['category'] === '__custom__') ? $values['custom_category'] : $values['category'];

    if (!$values['quote_text']) $errors[] = 'Quote text is required.';
    if (!$values['author_id'])  $errors[] = 'Please select an author.';
    if (!$finalCat)             $errors[] = 'Please select or enter a category.';

    if (empty($errors)) {
        $stmt = $db->prepare("INSERT INTO quotes (quote_text, author_id, category, year_said, source) VALUES (?,?,?,?,?)");
        $stmt->execute([$values['quote_text'], $values['author_id'], $finalCat, $values['year_said']?:null, $values['source']?:null]);
        header('Location: quotes.php?msg=added'); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Quote - Famous Quotes Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">Add New Quote</h1>
        <a href="quotes.php" class="btn btn-secondary btn-sm">Back to Quotes</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <?php if (empty($authors)): ?>
        <div class="alert alert-warning">You need to <a href="add_author.php">add an author</a> before adding quotes.</div>
    <?php endif; ?>

    <div class="card" style="max-width: 650px;">
        <div class="card-header">Quote Details</div>
        <div class="card-body">
            <form method="POST" action="add_quote.php">
                <div class="mb-3">
                    <label class="form-label" for="quote_text">Quote Text <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="quote_text" name="quote_text" rows="4" required><?= htmlspecialchars($values['quote_text']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="author_id">Author <span class="text-danger">*</span></label>
                    <select class="form-select" id="author_id" name="author_id" required>
                        <option value="">-- Select an author --</option>
                        <?php foreach ($authors as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= $values['author_id'] == $a['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($a['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="category">Category <span class="text-danger">*</span></label>
                    <select class="form-select" id="category" name="category" required onchange="toggleCustom(this.value)">
                        <option value="">-- Select a category --</option>
                        <?php foreach ($allCategories as $cat): ?>
                            <option value="<?= htmlspecialchars($cat) ?>" <?= $values['category'] === $cat ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="__custom__" <?= $values['category'] === '__custom__' ? 'selected' : '' ?>>Other...</option>
                    </select>
                </div>
                <div class="mb-3" id="customCategoryWrap" style="display:none;">
                    <label class="form-label" for="custom_category">Custom Category</label>
                    <input type="text" class="form-control" id="custom_category" name="custom_category"
                           value="<?= htmlspecialchars($values['custom_category']) ?>">
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="year_said">Year <small class="text-muted">(optional)</small></label>
                        <input type="number" class="form-control" id="year_said" name="year_said"
                               value="<?= htmlspecialchars($values['year_said']) ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="source">Source <small class="text-muted">(optional)</small></label>
                        <input type="text" class="form-control" id="source" name="source"
                               value="<?= htmlspecialchars($values['source']) ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-dark me-2">Save Quote</button>
                <a href="quotes.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
function toggleCustom(val) {
    document.getElementById('customCategoryWrap').style.display = val === '__custom__' ? 'block' : 'none';
}
window.addEventListener('DOMContentLoaded', function() {
    toggleCustom(document.getElementById('category').value);
});
</script>
</body>
</html>
