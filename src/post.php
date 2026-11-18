<?php
declare(strict_types=1);

require __DIR__ . '/storage.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$action = $_POST['action'] ?? '';
$message = '';
$messageType = '';
$post = null;
$showEdit = isset($_GET['edit']) && $_GET['edit'] === '1';

// Mutations (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $id !== false && $id !== null) {
    if ($action === 'update') {
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        if ($title === '' || $content === '') {
            $message = 'Title and content are required.';
            $messageType = 'error';
            $post = find_post($id);
            $showEdit = true;
        } elseif (update_post($id, $title, $content)) {
            header('Location: post.php?id=' . $id . '&updated=1', true, 303);
            exit;
        } else {
            $message = 'Post not found.';
            $messageType = 'error';
        }
    } elseif ($action === 'delete') {
        if (delete_post($id)) {
            header('Location: index.php?deleted=1', true, 303);
            exit;
        } else {
            $message = 'Post not found.';
            $messageType = 'error';
        }
    }
}

// Read (GET)
if ($post === null && $id !== false && $id !== null) {
    $post = find_post($id);
    if ($post === null) {
        http_response_code(404);
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= $post ? e($post['title']) : 'Post not found' ?> &mdash; SHAE Blog</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<main class="container">
  <header class="view-header">
    <h1><?= $post ? e($post['title']) : 'Post not found' ?></h1>
    <p class="meta">
      <a href="index.php" class="meta-link">&larr; Back to posts</a>
      <?php if ($post): ?>
        <span class="meta-divider">&middot;</span>
        <span>Created <?= e($post['created_at']) ?></span>
        <span class="meta-divider">&middot;</span>
        <span>Updated <?= e($post['updated_at']) ?></span>
      <?php endif; ?>
    </p>
  </header>

  <?php if ($message !== ''): ?>
    <div class="notice notice--<?= e($messageType) ?>" role="alert"><?= e($message) ?></div>
  <?php endif; ?>

  <?php if ($post === null): ?>
    <section class="card">
      <p>No post with id <?= e((string)$id) ?> exists.</p>
    </section>
  <?php elseif ($showEdit): ?>
    <section class="card">
      <h2 class="section-title">Edit post</h2>
      <form action="post.php?id=<?= (int)$post['id'] ?>" method="post" novalidate class="edit-form">
        <input type="hidden" name="action" value="update">
        <label class="field-label" for="title">Title</label>
        <input type="text" name="title" id="title" class="field"
               required maxlength="200" value="<?= e($post['title']) ?>">
        <label class="field-label" for="content">Content</label>
        <textarea name="content" id="content" class="field" rows="10"
                  required><?= e($post['content']) ?></textarea>
        <div class="form-actions">
          <button type="submit" class="btn btn--oxide">Update post</button>
          <a href="post.php?id=<?= (int)$post['id'] ?>" class="btn btn--outline">Cancel</a>
        </div>
      </form>
    </section>
  <?php else: ?>
    <section class="card post-detail">
      <div class="post-content"><?= nl2br(e($post['content'])) ?></div>
    </section>
    <section class="card post-actions">
      <h2 class="section-title" style="display:none">Actions</h2>
      <div class="form-actions">
        <form action="post.php?id=<?= (int)$post['id'] ?>" method="post" style="display:inline">
          <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
          <input type="hidden" name="action" value="delete">
          <button type="submit" class="btn btn--oxide"
                  onclick="return confirm('Delete this post?')">Delete post</button>
        </form>
        <a href="post.php?id=<?= (int)$post['id'] ?>&amp;edit=1" class="btn btn--outline">Edit post</a>
        <a href="index.php" class="btn btn--outline">Back to posts</a>
      </div>
    </section>
  <?php endif; ?>

  <footer class="view-footer">
    <p class="meta">SHAE Blog CMS &middot; v0.1.0</p>
  </footer>
</main>
</body>
</html>
