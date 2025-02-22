<?php

$h = fopen('xy-pts.csv', 'r');

$points = [];

// toss title row.
$row = fgetcsv($h);

while (($row = fgetcsv($h)) !== false) {
    array_push($points, $row);
}
fclose($h);

// flip Ys
$points = array_map(function($c) {
    return [$c[0], $c[1] = $c[1] * -1];
}, $points);

// translate to 0,0 for pixel coorindates
$minX = min(array_column($points, 0));
$minY = min(array_column($points, 1));
$normalize = array_map(function($c) use ($minX, $minY) {
    return [$c[0] - $minX, $c[1] - $minY];
}, $points);

$maxNX = max(array_column($normalize, 0));
$maxNY = max(array_column($normalize, 1));

$out = imagecreatetruecolor($maxNX, $maxNY);
$fill = imagecolorallocate($out, 255,255,0);

foreach ($normalize as $pt) {
    imagefilledellipse($out, $pt[0], $pt[1], 2, 2, $fill);
}

imagejpeg($out, 'scatter.jpg');