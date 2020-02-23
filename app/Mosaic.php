<?php

namespace App;

use App\IndexedColorConverter;
use stdClass;

class Mosaic
{
    const DEFAULT_OUTPUT = "/home/mark/Desktop/output.png";

    private $image;
    private $output;
    private $width;
    public $palette;
    private $converter;
    private $thumbnail;

    public function __construct(stdClass $options) {
        $this->converter = new IndexedColorConverter;
        $this->image = $options->image;
        $this->output = $options->output ?? self::DEFAULT_OUTPUT;
        $this->width = $options->width * 3;
        $this->dither = $options->dither;
        $this->palette = [
            "white" => [255, 255, 255],
            "red" => [217, 19, 27],
            "blue" => [0, 70, 204],
            "orange" => [255, 111, 5],
            "green" => [11, 184, 95],
            "yellow" => [251, 252, 25],
        ];
    }

    public function convert(): void {
        $this->resize($this->image, $this->output, $this->width);
        $image = $this->converter->convertToIndexedColor(
            imagecreatefrompng($this->output),
            $this->palette,
            $this->dither
        );
        imagepng($image, $this->output, 0);
        $this->resize($this->output, $this->output, $this->width);
    }

    public function convert_to_html() {
        $this->convert();
        $cubes = [];
        $image = imagecreatefrompng($this->output);
        for ($h=0; $h<imagesy($image) - imagesy($image) % 3; $h+=3) {
            $cubes[$h/3] = [];
            for ($w=0; $w<imagesx($image); $w+=3) {
                $cubes[$h/3][$w/3] = [];
                for ($i=0; $i<3; $i++) {
                    for ($j=0; $j<3; $j++) {
                        $rgb = $this->index_to_rgb(imagecolorat($image, $w + $j, $h + $i));
                        $cubes[$h/3][$w/3][] = array_search($rgb, $this->palette);
                    }
                }
            }
        }
        return $cubes;
    }

    public function convert_to_rubiks() {
        $this->set_rubiks_palette();
        $this->convert();
    }

    private function index_to_rgb(int $index): array {
        $red = ($index >> 16) & 0xff;
        $green = ($index >> 8) & 0xff;
        $blue = $index & 0xff;
        return [$red, $green, $blue];
    }

    private function resize(string $source, string $output, int $width): void {
        if (preg_match("/\.png/i", $source))
	        $im = imagecreatefrompng($source);
        else
	        $im = imagecreatefromjpeg($source);
	    $image_x = imagesx($im);
	    $image_y = imagesy($im);
	    $height = floor($image_y * ($width / $image_x));
	    $image = imagecreatetruecolor($width, $height);
	    imagecopyresampled($image, $im, 0, 0, 0, 0, $width, $height,
                           $image_x, $image_y);
	    imagepng($image, $output, 0);
    }

}

