<?php
$root = __DIR__ . '/../../resources/views';

$map = [
    '👨‍🏫' => 'user', '👩‍🏫' => 'user', '🧑‍🎓' => 'user', '👧' => 'user',
    '⏱️' => 'clock', '⏱' => 'clock', '⌛' => 'clock',
    '🥇' => 'award', '🥈' => 'award', '🥉' => 'award',
    '✏️' => 'pencil', '✏' => 'pencil', '✎' => 'pencil',
    '🛡️' => 'shield-check', '🛡' => 'shield-check',
    '🏷️' => 'tag', '🏷' => 'tag',
    '➡️' => 'arrow-right', '⬅️' => 'arrow-left',
    '⚠️' => 'help-circle', '⚠' => 'help-circle',
    '🟢' => '<x-icon name="dot" class="text-emerald-400" />',
    '🟡' => '<x-icon name="dot" class="text-amber-400" />',
    '🔴' => '<x-icon name="dot" class="text-rose-400" />',
    '📚' => 'book-open', '📖' => 'book-open',
    '📝' => 'file-text', '📄' => 'file-text',
    '📋' => 'clipboard-list',
    '📊' => 'bar-chart',
    '🏫' => 'school',
    '✅' => 'check-circle', '✓' => 'check',
    '❌' => 'x-circle', '✕' => 'x',
    '🚀' => 'rocket',
    '🎓' => 'graduation-cap',
    '⚡' => 'zap',
    '🔍' => 'search', '🔎' => 'search',
    '✨' => 'sparkles', '🎉' => 'sparkles', '✦' => 'sparkles',
    '🏠' => 'home',
    '🏆' => 'trophy',
    '📸' => 'camera', '📷' => 'camera',
    '💡' => 'lightbulb',
    '👋' => 'user',
    '🔗' => 'link',
    '🔒' => 'lock',
    '🔑' => 'key',
    '🔄' => 'refresh', '🔁' => 'refresh',
    '📧' => 'mail',
    '💾' => 'save',
    '💪' => 'activity',
    '👍' => 'thumbs-up',
    '🎲' => 'shuffle',
    '⭐' => 'star', '🌟' => 'star',
    '🧭' => 'compass',
    '🔬' => 'flask',
    '🔔' => 'bell',
    '📭' => 'inbox',
    '📤' => 'upload',
    '📌' => 'bookmark',
    '💜' => 'heart',
    '🤔' => 'help-circle',
    '😊' => 'smile',
];

$it = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($root));
$report = [];

foreach ($it as $file) {
    if (!$file->isFile() || !str_ends_with($file->getFilename(), '.blade.php')) continue;
    $path = $file->getPathname();
    $rel = str_replace('\\', '/', substr($path, strlen(str_replace('\\', '/', $root)) + 1));
    $src = file_get_contents($path);
    $before = $src;

    // Lindungi script, blok PHP, echo Blade, dan <option> (ikon tak bisa dirender di sana)
    $parts = preg_split(
        '/(<script\b[^>]*>.*?<\/script>|@php.*?@endphp|\{\{.*?\}\}|\{!!.*?!!\}|<option\b[^>]*>.*?<\/option>)/is',
        $src, -1, PREG_SPLIT_DELIM_CAPTURE
    );

    foreach ($parts as $i => $part) {
        if ($i % 2 === 1) { // bagian terlindungi: hanya buang emoji dari dalamnya
            $parts[$i] = preg_replace('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{FE0F}\x{200D}]/u', '', $part);
            continue;
        }
        // buang emoji dari semua atribut HTML
        $part = preg_replace_callback('/="([^"]*)"/u', function ($m) {
            return '="' . preg_replace('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{FE0F}\x{200D}]/u', '', $m[1]) . '"';
        }, $part);

        // ganti emoji yang tersisa di teks dengan komponen ikon
        foreach ($map as $emoji => $icon) {
            if (!str_contains($part, $emoji)) continue;
            $replacement = $icon[0] === '<' ? $icon : '<x-icon name="' . $icon . '" />';
            $part = str_replace($emoji, $replacement . ' ', $part);
            $report[$rel][$emoji] = ($report[$rel][$emoji] ?? 0) + 1;
        }
        // emoji yang belum dipetakan dibuang
        $part = preg_replace('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}\x{2B00}-\x{2BFF}\x{200D}]/u', '', $part);
        $parts[$i] = $part;
    }

    $src = implode('', $parts);
    if ($src !== $before) {
        file_put_contents($path, $src);
    }
}

foreach ($report as $file => $counts) {
    echo $file, ': ', json_encode($counts, JSON_UNESCAPED_UNICODE), PHP_EOL;
}
echo 'files: ', count($report), PHP_EOL;
