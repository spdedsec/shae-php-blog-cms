<?php
declare(strict_types=1);

require __DIR__ . '/storage.php';

$message = '';
$messageType = '';

// Handle post creation
if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['action']) && $_POST['action'] === 'create'
) {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');
    if ($title === '' || $content === '') {
        $message = 'Title and content are required.';
        $messageType = 'error';
    } else {
        create_post($title, $content);
        $message = 'Post created.';
        $messageType = 'success';
    }
}

$posts = read_posts();
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>SHAE Blog &mdash; Posts</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<main class="container">
  <header class="view-header">
    <h1>SHAE Blog</h1>
    <p class="meta">A field-manual CMS &mdash; posts stored in JSON.</p>
  </header>

  <?php if ($message !== ''): ?>
    <div class="notice notice--<?= e($messageType) ?>" role="alert"><?= e($message) ?></div>
  <?php endif; ?>

  <section class="card">
    <h2 class="section-title">All posts</h2>
    <?php if (empty($posts)): ?>
      <p class="empty">No posts yet. Create your first post below.</p>
    <?php else: ?>
      <ul class="post-list">
        <?php foreach ($posts as $post): ?>
          <li class="post-item">
            <a href="post.php?id=<?= (int)$post['id'] ?>" class="post-link">
              <span class="post-title"><?= e($post['title']) ?></span>
              <span class="post-date"><?= e($post['created_at']) ?></span>
            </a>
            <form action="post.php" method="post" class="delete-form" style="display:inline">
              <input type="hidden" name="id" value="<?= (int)$post['id'] ?>">
              <input type="hidden" name="action" value="delete">
              <button type="submit" class="btn btn--oxide btn--small"
                      onclick="return confirm('Delete this post?')">Delete</button>
            </form>
          </li>
        <?php endforeach; ?>
      </ul>
    <?php endif; ?>
  </section>

  <section class="card">
    <h2 class="section-title">New post</h2>
    <form action="index.php" method="post" novalidate class="create-form">
      <input type="hidden" name="action" value="create">
      <label class="field-label" for="title">Title</label>
      <input type="text" name="title" id="title" class="field"
             required maxlength="200" value="<?= e($_POST['title'] ?? '') ?>">
      <label class="field-label" for="content">Content</label>
      <textarea name="content" id="content" class="field" rows="8"
                required><?= e($_POST['content'] ?? '') ?></textarea>
      <button type="submit" class="btn btn--oxide">Create post</button>
    </form>
  </section>

  <footer class="view-footer">
    <p class="meta">SHAE Blog CMS &middot; v0.1.0</p>
  </footer>
</main>
</body>
</html>
