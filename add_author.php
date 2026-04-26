<?php
require_once 'includes/auth.php';
requireLogin();
require_once 'includes/db.php';

$errors = [];
$values = ['name'=>'','birth_year'=>'','death_year'=>'','nationality'=>'','bio'=>''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values = [
        'name'        => trim($_POST['name'] ?? ''),
        'birth_year'  => trim($_POST['birth_year'] ?? ''),
        'death_year'  => trim($_POST['death_year'] ?? ''),
        'nationality' => trim($_POST['nationality'] ?? ''),
        'bio'         => trim($_POST['bio'] ?? ''),
    ];
    if (!$values['name'])        $errors[] = 'Name is required.';
    if (!$values['nationality']) $errors[] = 'Nationality is required.';
    if (!$values['bio'])         $errors[] = 'Biography is required.';
    if (!$values['birth_year'])  $errors[] = 'Birth year is required.';

    if (empty($errors)) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO authors (name, birth_year, death_year, nationality, bio) VALUES (?,?,?,?,?)");
        $stmt->execute([$values['name'], $values['birth_year']?:null, $values['death_year']?:null, $values['nationality'], $values['bio']]);
        header('Location: authors.php?msg=added'); exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Author - Famous Quotes Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<?php include 'includes/nav.php'; ?>

<div class="page-wrapper">
    <div class="page-header">
        <h1 class="page-title">Add New Author</h1>
        <a href="authors.php" class="btn btn-secondary btn-sm">Back to Authors</a>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="card" style="max-width: 650px;">
        <div class="card-header">Author Information</div>
        <div class="card-body">
            <form method="POST" action="add_author.php">
                <div class="mb-3">
                    <label class="form-label" for="name">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="name" name="name"
                           value="<?= htmlspecialchars($values['name']) ?>" required>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label" for="birth_year">Birth Year <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="birth_year" name="birth_year"
                               value="<?= htmlspecialchars($values['birth_year']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label" for="death_year">Death Year <small class="text-muted">(leave blank if alive)</small></label>
                        <input type="number" class="form-control" id="death_year" name="death_year"
                               value="<?= htmlspecialchars($values['death_year']) ?>">
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="nationality">Nationality <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" id="nationality" name="nationality"
                           value="<?= htmlspecialchars($values['nationality']) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label" for="bio">Biography <span class="text-danger">*</span></label>
                    <textarea class="form-control" id="bio" name="bio" rows="4" required><?= htmlspecialchars($values['bio']) ?></textarea>
                </div>
                <button type="submit" class="btn btn-dark me-2">Save Author</button>
                <a href="authors.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
