<?php

namespace Ivebe\Lffmpeg\Libs;

use Imagine\Image\Box;
use Imagine\Image\ImageInterface;
use Imagine\Image\Point;
use Ivebe\Lffmpeg\Libs\Contracts\IImageLib;
use Psr\Log\LoggerInterface;

class ImageLib implements IImageLib
{
    private $log;

    public function __construct(LoggerInterface $log)
    {
        $this->log = $log;
    }

    public function thumb($file, $w, $h)
    {
        $imagine = new \Imagine\Gd\Imagine;
        $desiredBox = new Box($w, $h);

        $image = $imagine->open($file);

        //original size
        $srcBox = $image->getSize();

        if ($srcBox->getWidth() > $srcBox->getHeight()) {
            $width = $desiredBox->getWidth();
            $height =  $srcBox->getHeight()*($desiredBox->getWidth()/$srcBox->getWidth());
            $cropPoint = new Point((max($width - $desiredBox->getWidth(), 0))/2, 0);
        } else {
            $height = $desiredBox->getHeight();
            $width  = $srcBox->getWidth()*($desiredBox->getHeight()/$srcBox->getHeight());
            $cropPoint = new Point(0, (max($height - $desiredBox->getHeight(),0))/2);
        }

        $box = new Box($width, $height);
        //we scale the image to make the smaller dimension fit our resize box
        $image = $image->thumbnail($box, ImageInterface::THUMBNAIL_OUTBOUND);

        //and crop exactly to the box
        $image->crop($cropPoint, $desiredBox)->save($file);
    }
}