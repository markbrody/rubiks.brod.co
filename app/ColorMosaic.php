<?php

namespace App;

use App\Mosaic;

class ColorMosaic extends Mosaic
{
    const PALETTE = [
        "white" => [255, 255, 255],
        "red" => [217, 19, 27],
        "blue" => [0, 70, 204],
        "orange" => [255, 111, 5],
        "green" => [11, 184, 95],
        "yellow" => [251, 252, 25],
    ];

    public function __construct(\stdClass $options) {
        $options->palette = self::PALETTE;
        parent::__construct($options);
    }

    public function convert(): void {
        $this->resize($this->image, $this->output, $this->width);
        $resource = imagecreatefrompng($this->output);
        $image = $this->converter->convertToIndexedColor(
            $resource,
            $this->palette,
            $this->dither
        );
        imagepng($image, $this->output, 0);
        $this->resize($this->output, $this->output, $this->width);
    }

}

