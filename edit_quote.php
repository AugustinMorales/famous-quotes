<?php
require_once 'includes/auth.php';
requireLogin();
require_once 'includes/db.php';

$db = getDB();
$id = (int)($_GET['id'] ?? 0);
$stmt = $db->prepare("SELECT * FROM quotes WHERE id = ?");
$stmt->execute([$id]);
$quote = $stmt->fetch();
if (!$quote) { header('Location: quotes.php'); exit; }

$authors = $db->query("SELECT id, name FROM authors ORDER BY name ASC")->fetchAll();
$dbCategories = $db->query("SELECT DISTINCT category FROM quotes ORDER BY category ASC")->fetchAll(PDO::FETCH_COLUMN);
$predefined = ['Business','Education','Friendship','History','Humor','Inspiration','Leadership','Life','Love','Motivation','Nature','Philosophy','Politics','Science','Success','Wisdom'];
$allCategories = array_unique(array_merge($dbCategories, $predefined));
sort($allCategories);
$categoryInList = in_array($quote['category'], $allCategories);

$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $quote_text = trim($_POST['quote_text'] ?? '');
    $author_id  = (int)($_POST['author_id'] ?? 0);
    $category   = trim($_POST['category'] ?? '');
    $custom_cat = trim($_POST['custom_category'] ?? '');
    $year_said  = trim($_POST['year_said'] ?? '');
    $source     = trim($_POST['source'] ?? '');
    $finalCat   = ($category === '__custom__') ? $custom_cat : $category;

    if (!$quote_text) $errors[] = 'Quote text is required.';
    if (!$author_id)  $errors[] = 'Please select an author.';
    if (!$finalCat)   $errors[] = 'Please select or enter a category.';

    if (empty($errors)) {
        $stmt = $db->prepare("UPDATE quotes SET quote_text=?, author_id=?, category=?, year_said=?, source=? WHERE id=?");
        $stmt->execute([$quote_text, $author_id, $finalCat, $year_said?:null, $source?:null, $id]);
        header('Location: quotes.php?msg=updated'); exit;
    }
    $quote = array_merge($quote, compact('quote_text','author_id','year_said','source'));
    $quote['category'] = $category;
    $categoryInList = in_array($category, $allCategories);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Quote - Famous Quotes Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">Edit Quote</h1>
        <a href="quotes.php" class="btn btn-secondary btn-sm">Back to Quotes</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0"><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <div class="card" style="max-width: 650px;">
        <div class="card-header">Edit Quote</div>
        <div class="card-body">
            <form method="POST" action="edit_quote.php?id=<?= $id ?>">
                <div class="mb-3">
                    <label class="form-label" for="quote_text">Quote Text <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="quote_text" name="quote_text" rows="4" required><?= htmlspecialchars($quote['quote_text']) ?></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="author_id">Author <span class="text-danger">*</span></label>
                    <select class="form-select" id="author_id" name="author_id" required>
                        <option value="">-- Select an author --</option>
                        <?php foreach ($authors as $a): ?>
                            <option value="<?= $a['id'] ?>" <?= $quote['author_id'] == $a['id'] ? 'selected' : '' ?>>
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
                            <option value="<?= htmlspecialchars($cat) ?>"
                                <?= ($quote['category'] === $cat && $categoryInList) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($cat) ?>
                            </option>
                        <?php endforeach; ?>
                        <option value="__custom__" <?= !$categoryInList ? 'selected' : '' ?>>Other...</option>
                    </select>
                </div>
                <div class="mb-3" id="customCategoryWrap" style="display:none;">
                    <label class="form-label" for="custom_category">Custom Category</label>
                    <input type="text" class="form-control" id="custom_category" name="custom_category"
                           value="<?= !$categoryInList ? htmlspecialchars($quote['category']) : '' ?>">
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="year_said">Year <small class="text-muted">(optional)</small></label>
                        <input type="number" class="form-control" id="year_said" name="year_said"
                               value="<?= htmlspecialchars($quote['year_said'] ?? '') ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="source">Source <small class="text-muted">(optional)</small></label>
                        <input type="text" class="form-control" id="source" name="source"
                               value="<?= htmlspecialchars($quote['source'] ?? '') ?>">
                    </div>
                </div>
                <button type="submit" class="btn btn-dark me-2">Update Quote</button>
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
