<?php
include __DIR__ . '/database.php';

$outputFile = 'data/movies.csv';
$output = fopen($outputFile, 'w');

fputcsv($output, ['movie_id', 'title']);

$stmt = $pdo->query('SELECT id AS movie_id, title FROM movies');
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    fputcsv($output, $row);
}

fclose($output);
echo "Archivo movies.csv creado exitosamente.";