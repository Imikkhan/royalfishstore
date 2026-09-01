<?php
$dir = __DIR__ . '/public/images';
if (!file_exists($dir)) {
    mkdir($dir, 0777, true);
}

function createRiderIcon($size, $filename) {
    $img = imagecreatetruecolor($size, $size);
    
    // Background gradient red/dark
    $red = imagecolorallocate($img, 220, 38, 38);
    $dark = imagecolorallocate($img, 15, 23, 42);
    $white = imagecolorallocate($img, 255, 255, 255);
    $amber = imagecolorallocate($img, 251, 191, 36);

    imagefilledrectangle($img, 0, 0, $size, $size, $dark);

    // Circle background
    $margin = $size * 0.1;
    imagefilledellipse($img, $size/2, $size/2, $size - $margin, $size - $margin, $red);

    // Inner ring
    imagesetthickness($img, (int)($size * 0.02));
    imageellipse($img, $size/2, $size/2, $size - ($margin * 1.4), $size - ($margin * 1.4), $amber);

    // Letter 'R'
    $fontPath = __DIR__ . '/public/fonts/Roboto-Bold.ttf';
    if (file_exists($fontPath)) {
        $fontSize = $size * 0.4;
        $bbox = imagettfbbox($fontSize, 0, $fontPath, 'R');
        $x = ($size - ($bbox[2] - $bbox[0])) / 2;
        $y = ($size - ($bbox[7] - $bbox[1])) / 2;
        imagettftext($img, $fontSize, 0, (int)$x, (int)$y, $white, $fontPath, 'R');
    } else {
        // Fallback drawing text
        $string = "R";
        $font = 5;
        $px = (imagesx($img) - 8 * strlen($string)) / 2;
        $py = (imagesy($img) - 16) / 2;
        imagestring($img, $font, (int)$px, (int)$py, $string, $white);
    }

    imagepng($img, $filename);
    imagedestroy($img);
}

createRiderIcon(192, $dir . '/rider-icon-192.png');
createRiderIcon(512, $dir . '/rider-icon-512.png');

echo "PWA Rider Icons generated successfully!\n";
