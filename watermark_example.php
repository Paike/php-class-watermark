<?php
include("watermark.class.php");

$Watermark = new Watermark;

$Watermark->setOriginal('original.jpg');
$Watermark->setWatermark('watermark.png');

/*
$Watermark->setOpacity();
$Watermark->setHeight();
$Watermark->setWidth();
$Watermark->setAlignX();
$Watermark->setAlignY();
$Watermark->setOffsetX();
$Watermark->setOffsetY();
$Watermark->setOutputFormat();
$Watermark->setQuality();
*/
$Watermark->setDebug();
$Watermark->show();