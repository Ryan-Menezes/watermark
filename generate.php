<?php

error_reporting(0);
ini_set('display_errors', 0);
ini_set('memory_limit', '100G');
ini_set('post_max_size', '100G');
ini_set('upload_max_filesize', '100G');
ini_set('max_file_uploads', '1000');

define('PATH_UPLOADS', __DIR__ . '/uploads');
define('PATH_ZIPS', __DIR__ . '/zips');
define('TYPES_FORMAT', [
    'addWatermarkOnTopLeftCornerOfImage',
    'addWatermarkInTheCenterOfTheImage',
    'addWatermarkOnTopRightCornerOfImage',
    'addWatermarkInBottomLeftCornerOfImage',
    'addWatermarkInRowsAndColumns',
    'addWatermarkInBottomRightCornerOfImage',
    'addStripedWatermark',
]);
define('IMAGE_CREATE_FROM', [
    'image/gd'      => 'imagecreatefromgd',
    'image/ga'      => 'imagecreatefromtga',
    'image/gd2'     => 'imagecreatefromgd2',
    'image/xbm'     => 'imagecreatefromxbm',
    'image/xpm'     => 'imagecreatefromxpm',
    'image/png'     => 'imagecreatefrompng',
    'image/bmp'     => 'imagecreatefrombmp',
    'image/gif'     => 'imagecreatefromgif',
    'image/avif'    => 'imagecreatefromavif',
    'image/webp'    => 'imagecreatefromwebp',
    'image/jpeg'    => 'imagecreatefromjpeg',
]);

main();

function main()
{
    clearDir(PATH_ZIPS);

    if (!file_exists(PATH_UPLOADS)) {
        mkdir(PATH_UPLOADS);
    }

    if (!file_exists(PATH_ZIPS)) {
        mkdir(PATH_ZIPS);
    }

    $typeFormat = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_NUMBER_INT) ?? 0;
    $files = $_FILES['images'];
    $sizeFiles = count($files['name']);

    $watermark = $_FILES['watermark'];
    $watermark = imageCreateFrom((object) [
        'name'      => $watermark['name'],
        'type'      => $watermark['type'],
        'tmpName'   => $watermark['tmp_name'],
        'error'     => $watermark['error'],
    ]);

    for ($i = 0; $i < $sizeFiles; $i++) {
        $image = imageCreateFrom((object) [
            'name'      => $files['name'][$i],
            'type'      => $files['type'][$i],
            'tmpName'   => $files['tmp_name'][$i],
            'error'     => $files['error'][$i],
        ]);

        if (
            $image->file->error === 0 &&
            preg_match('/image\/*/i', $image->file->type)
        ) {
            addWatermark($typeFormat, $image, $watermark);
        }
    }

    $path = generateZip(PATH_UPLOADS);
    clearDir(PATH_UPLOADS);
    download($path, 'application/zip');
}

function addWatermark(int $typeFormat, object $image, object $watermark)
{
    $image = generateImage($image);

    $call = TYPES_FORMAT[$typeFormat];
    $call($image, $watermark);

    imagejpeg($image->stream, PATH_UPLOADS . '/' . $image->file->name);
}

function imageCreateFrom(object $file): object
{
    list($width, $height) = getimagesize($file->tmpName);

    $imagecreatefrom = IMAGE_CREATE_FROM[$file->type];
    $stream = @$imagecreatefrom($file->tmpName);

    return (object)[
        'stream'    => $stream,
        'x'         => imagesx($stream),
        'y'         => imagesy($stream),
        'width'     => $width,
        'height'    => $height,
        'file'      => $file,
    ];
}

function generateImage(object $image): object
{
    $new = imagecreatetruecolor($image->width, $image->height);

    imagecopyresampled(
        $new,
        $image->stream,
        0,
        0,
        0,
        0,
        $image->width,
        $image->height,
        $image->x,
        $image->y
    );

    $image->stream = $new;
    $image = verifyAndFormatRotation($image);

    return $image;
}

function verifyAndFormatRotation(object $image): object
{
    $exif = @exif_read_data($image->file->tmpName);

    if (empty($exif['Orientation'])) {
        return $image;
    }

    switch ($exif['Orientation']) {
        case 1: // nothing
            break;
        case 2: // horizontal flip
            imageflip($image->stream, IMG_FLIP_HORIZONTAL);
            break;
        case 3: // 180 rotate left
            $image->stream = imagerotate($image->stream, 180, 0);
            break;
        case 4: // vertical flip
            imageflip($image->stream, IMG_FLIP_VERTICAL);
            break;
        case 5: // vertical flip + 90 rotate right
            imageflip($image->stream, IMG_FLIP_VERTICAL);
            $image->stream = imagerotate($image->stream, -90, 0);
            break;
        case 6: // 90 rotate right
            $image->stream = imagerotate($image->stream, -90, 0);
            break;
        case 7: // horizontal flip + 90 rotate right
            imageflip($image->stream, IMG_FLIP_HORIZONTAL);
            $image = imagerotate($image->stream, -90, 0);
            break;
        case 8:    // 90 rotate left
            $image->stream = imagerotate($image->stream, 90, 0);
            break;
    }

    // Invertando os tamanhos da imagem
    if ($exif['Orientation'] != 1) {
        $c = $image->width;
        $image->width = $image->height;
        $image->height = $c;

        // $image->stream = imagerotate($image->stream, 270, 0);
    }

    return $image;
}

function addWatermarkInBottomRightCornerOfImage(object $image, object $watermark): void
{
    $margin = 20;
    $watermark->width = $image->width / 4;
    $watermark->height = ($watermark->width * $watermark->y) / $watermark->x;

    $posX = $image->width - $watermark->width - $margin;
    $posY = $image->height - $watermark->height - $margin;

    imagecopyresampled(
        $image->stream,
        $watermark->stream,
        $posX,
        $posY,
        0,
        0,
        $watermark->width,
        $watermark->height,
        $watermark->x,
        $watermark->y
    );
}

function addWatermarkInBottomLeftCornerOfImage(object $image, object $watermark): void
{
    $margin = 20;
    $watermark->width = $image->width / 4;
    $watermark->height = ($watermark->width * $watermark->y) / $watermark->x;

    $posX = $margin;
    $posY = $image->height - $watermark->height - $margin;

    imagecopyresampled(
        $image->stream,
        $watermark->stream,
        $posX,
        $posY,
        0,
        0,
        $watermark->width,
        $watermark->height,
        $watermark->x,
        $watermark->y
    );
}

function addWatermarkOnTopLeftCornerOfImage(object $image, object $watermark): void
{
    $margin = 20;
    $watermark->width = $image->width / 4;
    $watermark->height = ($watermark->width * $watermark->y) / $watermark->x;

    $posX = $posY = $margin;

    imagecopyresampled(
        $image->stream,
        $watermark->stream,
        $posX,
        $posY,
        0,
        0,
        $watermark->width,
        $watermark->height,
        $watermark->x,
        $watermark->y
    );
}

function addWatermarkOnTopRightCornerOfImage(object $image, object $watermark): void
{
    $margin = 20;
    $watermark->width = $image->width / 4;
    $watermark->height = ($watermark->width * $watermark->y) / $watermark->x;

    $posX = $image->width - $watermark->width - $margin;
    $posY = $margin;

    imagecopyresampled(
        $image->stream,
        $watermark->stream,
        $posX,
        $posY,
        0,
        0,
        $watermark->width,
        $watermark->height,
        $watermark->x,
        $watermark->y
    );
}

function addWatermarkInTheCenterOfTheImage(object $image, object $watermark): void
{
    if (isVertical($image)) {
        $watermark->width = $image->width;
        $watermark->height = ($watermark->width * $watermark->y) / $watermark->x;
    } else {
        $watermark->height = $image->height;
        $watermark->width = ($watermark->height * $watermark->x) / $watermark->y;
    }

    $posX = $image->width / 2 - $watermark->width / 2;
    $posY = $image->height / 2 - $watermark->height / 2;

    imagecopyresampled(
        $image->stream,
        $watermark->stream,
        $posX,
        $posY,
        0,
        0,
        $watermark->width,
        $watermark->height,
        $watermark->x,
        $watermark->y
    );
}

function addStripedWatermark(object $image, object $watermark): void
{
    $margin = 0;
    $watermark->width = $image->width / 4 - $margin;
    $watermark->height = (($watermark->width * $watermark->y) / $watermark->x) - $margin;

    $limitX = ceil($image->width / $watermark->width);
    $limitY = ceil($image->height / $watermark->height);

    for ($j = 0; $j < $limitY; $j++) {
        $evenOrOdd = $j % 2;

        for ($i = 0; $i < $limitX + $evenOrOdd; $i++) {
            $posX = ($watermark->width + $margin) * $i;
            $posY = ($watermark->height + $margin) * $j;

            if ($evenOrOdd === 1) {
                $posX = $posX - $watermark->width / 2;
            }

            imagecopyresampled(
                $image->stream,
                $watermark->stream,
                $posX,
                $posY,
                0,
                0,
                $watermark->width,
                $watermark->height,
                $watermark->x,
                $watermark->y
            );
        }
    }
}

function addWatermarkInRowsAndColumns(object $image, object $watermark): void
{
    $margin = 0;
    $watermark->width = $image->width / 4 - $margin;
    $watermark->height = (($watermark->width * $watermark->y) / $watermark->x) - $margin;

    $limitX = ceil($image->width / $watermark->width);
    $limitY = ceil($image->height / $watermark->height);

    for ($j = 0; $j < $limitY; $j++) {
        for ($i = 0; $i < $limitX; $i++) {
            $posX = ($watermark->width + $margin) * $i;
            $posY = ($watermark->height + $margin) * $j;

            imagecopyresampled(
                $image->stream,
                $watermark->stream,
                $posX,
                $posY,
                0,
                0,
                $watermark->width,
                $watermark->height,
                $watermark->x,
                $watermark->y
            );
        }
    }
}

function isVertical(object $image)
{
    return $image->height > $image->width;
}

function generateZip(string $dir)
{
    $files = scandir($dir);

    $filename = md5(uniqid() . time() . rand(0, 99999)) . '.zip';
    $path = PATH_ZIPS . '/' . $filename;

    $zip = new ZipArchive();
    $zip->open($path, ZipArchive::CREATE);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $zip->addFile(
                "{$dir}/{$file}",
                $file,
            );
        }
    }

    $zip->close();

    return $path;
}

function clearDir(string $path)
{
    $files = scandir($path);

    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            unlink($path . '/' . $file);
        }
    }
}

function download(string $path, string $type)
{
    header("Content-Type: {$type}");
    header('Content-Length: ' . filesize($path));
    header('Content-Disposition: attachment; filename=' . basename($path));

    readfile($path);

    die();
}
