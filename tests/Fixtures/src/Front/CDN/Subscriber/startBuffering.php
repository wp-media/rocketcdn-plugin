<?php

$html = <<<HTML
<html>
<head>
    <link href="https://example.org/wp-content/test">
</head>
<body>
</body>
</html>
HTML;

$output_html = <<<HTML
<html>
<head>
    <link href="https://cdn.cdn/wp-content/test">
</head>
<body>
</body>
</html>
HTML;

$admin_html = <<<HTML
<html>
<head>
    <link href="https://example.org/wp-content/test">
    <img srcset="http://example.org/wp-admin/admin.php">
</head>
<body>
</body>
</html>
HTML;

$admin_output_html = <<<HTML
<html>
<head>
    <link href="https://cdn.cdn/wp-content/test">
    <img srcset="http://example.org/wp-admin/admin.php">
</head>
<body>
</body>
</html>
HTML;

return [
    'outputShouldBeRewritten' => [
        'config' => [
            'html' => $html,
            'cdn_url' => 'https://cdn.cdn'
        ],
        'expected' => [
            'html' => $output_html
        ]
    ],
    'GivenAdminInHTMlWhenRewriteThenShouldKeepOriginal' => [
        'config' => [
            'html' => $admin_html,
            'cdn_url' => 'https://cdn.cdn'
        ],
        'expected' => [
            'html' => $admin_output_html,
        ],
    ]
];