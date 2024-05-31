<?php
include __DIR__ . '/database.php';

$outputFile = 'data/ratings.csv';
$output = fopen($outputFile, 'w');

fputcsv($output, ['user_id', 'movie_id', 'rating']);

$stmt = $pdo->query('SELECT user_id, movie_id, rating FROM ratings');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
echo "Archivo ratings.csv creado exitosamente.";