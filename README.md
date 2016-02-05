# PHP Watermark Class
Modifies a watermark image and places it over another image

## Installation

`include('watermark.class.php')`

## Usage

Show in browser:
```php
$Watermark = new Watermark;
$Watermark->setOriginal('original.jpg');
$Watermark->setWatermark('watermark.png');
$Watermark->show();
```

Overwrite original image file:
```php
$Watermark = new Watermark;
$Watermark->setOriginal('original.jpg');
$Watermark->setWatermark('watermark.png');
$Watermark->save();
```

Save as new image file:
```php
$Watermark = new Watermark;
$Watermark->setOriginal('original.jpg');
$Watermark->setWatermark('watermark.png');
$Watermark->setDestinationPath('/var/www/images');
//$Watermark->setDestinationFilename('new_filename_without_extension');
$Watermark->save();
```

Backup the original image file:
```php
$Watermark = new Watermark;
$Watermark->setOriginal('original.jpg');
$Watermark->setWatermark('watermark.png');
$Watermark->setBackupPath('/var/www/backupfolder');
$Watermark->save();
```

## Settings

```php
// 0.1,0.2...0.9,1
$Watermark->setOpacity();

// 480 or '30%' (of original image dimension)
$Watermark->setHeight();

// 640 or '30%' (of original image dimension)
$Watermark->setWidth();

// 'left', 'center', 'right'
$Watermark->setAlignX();

// 'top', 'middle', 'bottom'
$Watermark->setAlignY();

// 480 or '30%' (of original image dimension)
$Watermark->setOffsetX();

// 480 or '30%' (of original image dimension)
$Watermark->setOffsetY();

// 'jpg' (default), 'png', 'gif'
$Watermark->setOutputFormat();

// 0,1,2...98,99,100
$Watermark->setQuality();

$Watermark->setDebug();
```
