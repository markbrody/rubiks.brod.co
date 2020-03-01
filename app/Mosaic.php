<?php

namespace App;

use App\IndexedColorConverter;

class Mosaic
{
    const DEFAULT_OUTPUT = "/tmp/output.png";

    protected $image;
    protected $output;
    protected $width;
    public $palette;
    protected $converter;
    protected $thumbnail;

    public function __construct(\stdClass $options) {
        $this->converter = new IndexedColorConverter;
        $this->image = $options->image;
        $this->output = $options->output ?? self::DEFAULT_OUTPUT;
        $this->width = $options->width * 3;
        $this->brightness = $options->brightness ?? 0;
        $this->contrast = $options->contrast ?? 0;
        $this->dither = $options->dither ?? 0;
        $this->palette = $options->palette;
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

    private function index_to_rgb(int $index): array {
        $red = ($index >> 16) & 0xff;
        $green = ($index >> 8) & 0xff;
        $blue = $index & 0xff;
        return [$red, $green, $blue];
    }

    protected function resize(string $source, string $output, int $width): void {
        $exif = @exif_read_data($source);
        $orientation = $exif['Orientation'] ?? 0;
        if (preg_match("/png/i", mime_content_type($source)))
	        $im = imagecreatefrompng($source);
        else
	        $im = imagecreatefromjpeg($source);
        if ($orientation) {
            $orientations = [3 => 180, 6 => -90, 8 => 90];
            imagerotate($im, $orientations[$orientation]);
        }
	    $image_x = imagesx($im);
	    $image_y = imagesy($im);
	    $height = floor($image_y * ($width / $image_x));
	    $image = imagecreatetruecolor($width, $height);
	    imagecopyresampled($image, $im, 0, 0, 0, 0, $width, $height,
                           $image_x, $image_y);
	    imagepng($image, $output, 0);
    }

}

