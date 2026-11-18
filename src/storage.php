<?php
declare(strict_types=1);

/**
 * SHAE Blog CMS — JSON storage layer.
 *
 * Posts are stored in data/posts.json relative to the project root.
 * Each post record:
 *   ['id' => int, 'title' => string, 'content' => string,
 *    'created_at' => string (ISO-8601), 'updated_at' => string (ISO-8601)]
 */

const STORAGE_FILE = __DIR__ . '/../data/posts.json';

/**
 * Return the absolute path to the JSON storage file.
 */
function storage_path(): string
{
    return STORAGE_FILE;
}

/**
 * Ensure the storage directory and JSON file exist.
 */
function ensure_storage(): void
{
    $dir = dirname(STORAGE_FILE);
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    if (!file_exists(STORAGE_FILE)) {
        file_put_contents(STORAGE_FILE, json_encode(
            [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
        ));
    }
}

/**
 * Read all posts from the JSON file, sorted newest first.
 *
 * @return list<array{id:int, title:string, content:string, created_at:string, updated_at:string}>
 */
function read_posts(): array
{
    ensure_storage();
    $raw = file_get_contents(STORAGE_FILE);
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return [];
    }
    usort($data, static function (array $a, array $b): int {
        return strcmp((string)($b['created_at'] ?? ''), (string)($a['created_at'] ?? ''));
    });
    return array_values($data);
}

/**
 * Find a post by id.
 *
 * @return array{id:int, title:string, content:string, created_at:string, updated_at:string}|null
 */
function find_post(int $id): ?array
{
    foreach (read_posts() as $post) {
        if ((int)($post['id'] ?? 0) === $id) {
            return $post;
        }
    }
    return null;
}

/**
 * Create a new post and return the stored record.
 */
function create_post(string $title, string $content): array
{
    $posts = read_posts();
    $maxId = 0;
    foreach ($posts as $post) {
        $maxId = max($maxId, (int)($post['id'] ?? 0));
    }
    $now = (new DateTimeImmutable())->format(DateTimeInterface::ATOM);
    $post = [
        'id' => $maxId + 1,
        'title' => $title,
        'content' => $content,
        'created_at' => $now,
        'updated_at' => $now,
    ];
    $posts[] = $post;
    write_posts($posts);
    return $post;
}

/**
 * Update an existing post. Returns true on success, false if not found.
 */
function update_post(int $id, string $title, string $content): bool
{
    $posts = read_posts();
    $found = false;
    foreach ($posts as &$post) {
        if ((int)($post['id'] ?? 0) === $id) {
            $post['title'] = $title;
            $post['content'] = $content;
            $post['updated_at'] = (new DateTimeImmutable())->format(DateTimeInterface::ATOM);
            $found = true;
            break;
        }
    }
    if (!$found) {
        unset($post);
        return false;
    }
    unset($post);
    write_posts($posts);
    return true;
}

/**
 * Delete a post by id. Returns true on success, false if not found.
 */
function delete_post(int $id): bool
{
    $posts = read_posts();
    $before = count($posts);
    $posts = array_values(array_filter(
        $posts,
        static function (array $post) use ($id): bool {
            return (int)($post['id'] ?? 0) !== $id;
        }
    ));
    if (count($posts) === $before) {
        return false;
    }
    write_posts($posts);
    return true;
}

/**
 * Atomically write the posts array to the JSON storage file.
 */
function write_posts(array $posts): void
{
    ensure_storage();
    $json = json_encode(
        $posts,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    );
    $tmp = STORAGE_FILE . '.tmp';
    file_put_contents($tmp, $json);
    rename($tmp, STORAGE_FILE);
}

/**
 * Escape a string for safe HTML output.
 */
function e(string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
