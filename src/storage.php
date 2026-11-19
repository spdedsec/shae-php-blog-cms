<?php
declare(strict_types=1);

/**
 * Blog CMS — Apple HIG Storage Layer (JSON-based)
 */

function get_storage_path(): string
{
    // Production container path or local fallback
    if (is_dir('/var/www/data') && is_writable('/var/www/data')) {
        return '/var/www/data/posts.json';
    }
    $localDir = __DIR__ . '/../data';
    if (!is_dir($localDir)) {
        @mkdir($localDir, 0775, true);
    }
    if (is_writable($localDir)) {
        return $localDir . '/posts.json';
    }
    // Fallback to system temp directory
    return sys_get_temp_dir() . '/shae_posts.json';
}

function ensure_storage(): void
{
    $file = get_storage_path();
    $dir = dirname($file);
    if (!is_dir($dir)) {
        @mkdir($dir, 0775, true);
    }
    if (!file_exists($file)) {
        $initial = [
            [
                'id' => 1,
                'title' => 'Designing for Apple Platforms: The HIG Philosophy',
                'content' => "Apple Human Interface Guidelines (HIG) provide essential guidance for creating intuitive, engaging, and consistent experiences.\n\nKey Principles:\n• Clarity: Text is legible at every size, icons are precise and lucid, and adornments are subtle.\n• Deference: Fluid motion and a crisp interface help people understand and interact with content without competing with it.\n• Depth: Distinct visual layers and realistic motion impart hierarchy, imparting vitality and facilitating understanding.",
                'created_at' => (new DateTimeImmutable('-2 hours'))->format(DateTimeInterface::ATOM),
                'updated_at' => (new DateTimeImmutable('-2 hours'))->format(DateTimeInterface::ATOM),
            ],
            [
                'id' => 2,
                'title' => 'Dynamic Light and Dark Mode in Modern CSS',
                'content' => "Supporting dark mode natively with color-scheme: light dark and CSS custom properties provides a seamless experience for users.\n\nBy leveraging Apple System Colors like System Blue (#007AFF), System Green (#34C759), and adaptive backgrounds (UIColor.systemBackground), the web app feels right at home on macOS and iOS.",
                'created_at' => (new DateTimeImmutable('-1 hour'))->format(DateTimeInterface::ATOM),
                'updated_at' => (new DateTimeImmutable('-1 hour'))->format(DateTimeInterface::ATOM),
            ]
        ];
        file_put_contents($file, json_encode($initial, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
    }
}

function read_posts(): array
{
    ensure_storage();
    $file = get_storage_path();
    $raw = @file_get_contents($file);
    if ($raw === false) {
        return [];
    }
    $data = json_decode($raw, true);
    if (!is_array($data)) {
        return [];
    }
    usort($data, static function (array $a, array $b): int {
        return strcmp((string)($b['created_at'] ?? ''), (string)($a['created_at'] ?? ''));
    });
    return array_values($data);
}

function find_post(int $id): ?array
{
    foreach (read_posts() as $post) {
        if ((int)($post['id'] ?? 0) === $id) {
            return $post;
        }
    }
    return null;
}

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

function write_posts(array $posts): void
{
    ensure_storage();
    $file = get_storage_path();
    $json = json_encode(
        $posts,
        JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
    );
    $tmp = $file . '.tmp';
    file_put_contents($tmp, $json);
    rename($tmp, $file);
}

function e(string $s): string
{
    return htmlspecialchars((string)$s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
