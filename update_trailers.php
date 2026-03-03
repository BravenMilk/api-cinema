<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';

use App\Models\Movie;
use Illuminate\Support\Facades\DB;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$trailers = [
    'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
    'https://www.youtube.com/watch?v=M7lc1UVf-VE',
    'https://www.youtube.com/watch?v=J---aiyznGQ',
];

$movies = Movie::all();
foreach ($movies as $index => $movie) {
    $url = $trailers[$index % count($trailers)];
    $movie->update(['trailer_url' => $url]);
    echo "Updated Movie: {$movie->title} with URL: {$url}\n";
}

echo "Database update complete.\n";
