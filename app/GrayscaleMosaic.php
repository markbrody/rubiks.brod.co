<?php

namespace App;

use App\Mosaic;

class GrayscaleMosaic extends Mosaic
{
    const PALETTE = [
        "white" => [255, 255, 255],
        "yellow" => [204, 204, 204],
        "orange" => [153, 153, 153],
        "green" => [102, 102, 102],
        "red" => [51, 51, 51],
        "blue" => [0, 0, 0],
    ];

    public function __construct(\stdClass $options) {
        $options->palette = self::PALETTE;
        parent::__construct($options);
    }

    public function convert(): void {
        $this->resize($this->image, $this->output, $this->width);
        $resource = imagecreatefrompng($this->output);
        imagefilter($resource, IMG_FILTER_CONTRAST, -40);
        imagefilter($resource, IMG_FILTER_GRAYSCALE);
        $image = $this->converter->convertToIndexedColor(
            $resource,
            $this->palette,
            $this->dither
        );
        imagepng($image, $this->output, 0);
        $this->resize($this->output, $this->output, $this->width);
    }

}
