<?php

$src = __DIR__ . '/public/images/logo.jpg';
$outDir = __DIR__ . '/public/images/icons';

if (!is_dir($outDir)) {
    mkdir($outDir, 0755, true);
}

$sizes = [72, 96, 128, 144, 152, 192, 384, 512];

$srcImage = imagecreatefromjpeg($src);
$srcW = imagesx($srcImage);
$srcH = imagesy($srcImage);

foreach ($sizes as $size) {
    // Create square canvas with white background
    $dst = imagecreatetruecolor($size, $size);
    $white = imagecolorallocate($dst, 255, 255, 255);
    imagefill($dst, 0, 0, $white);

    // Scale logo to fit inside with padding (80% of icon size)
    $logoSize = (int)($size * 0.8);
    $offset = (int)(($size - $logoSize) / 2);

    // Maintain aspect ratio
    $srcRatio = $srcW / $srcH;
    if ($srcRatio > 1) {
        $drawW = $logoSize;
        $drawH = (int)($logoSize / $srcRatio);
        $drawX = $offset;
        $drawY = $offset + (int)(($logoSize - $drawH) / 2);
    } else {
        $drawH = $logoSize;
        $drawW = (int)($logoSize * $srcRatio);
        $drawY = $offset;
        $drawX = $offset + (int)(($logoSize - $drawW) / 2);
    }

    imagecopyresampled($dst, $srcImage, $drawX, $drawY, 0, 0, $drawW, $drawH, $srcW, $srcH);

    $outFile = $outDir . "/icon-{$size}x{$size}.png";
    imagepng($dst, $outFile);
    imagedestroy($dst);

    echo "Created: icon-{$size}x{$size}.png\n";
}

imagedestroy($srcImage);
echo "All icons generated successfully!\n";
