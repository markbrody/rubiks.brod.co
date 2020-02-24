<?php

namespace App\Http\Controllers;

use App\ColorMosaic;
use App\GrayscaleMosaic;
use Illuminate\Http\Request;

class MosaicController extends Controller
{
    public function store(Request $request) {
        $args = (object) [
            "image" => "/home/mark/archive/Desktop/mosaic/IMG_0000.jpg",
            "image" => "/home/mark/archive/Desktop/mosaic/monalisa.jpg",
            "width" => (int) $request->input("width"),
            "dither" => (float) $request->input("dither"),
        ];
        if ((bool) $request->input("grayscale"))
            $mosaic = new GrayscaleMosaic($args);
        else
            $mosaic = new ColorMosaic($args);
        return $mosaic->convert_to_html();
    }
}
