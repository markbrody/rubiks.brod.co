<?php

namespace App\Http\Controllers;

use App\ColorMosaic;
use App\GrayscaleMosaic;
use Illuminate\Http\Request;

class MosaicController extends Controller
{
    public function store(Request $request) {
        $options = (object) [
            "image" => $request->file("image"),
            "output" => "/tmp/" . uniqid(),
            "width" => (int) $request->input("width"),
            "brightness" => (int) $request->input("brightness"),
            "contrast" => (int) $request->input("contrast"),
            "dither" => (float) $request->input("dither"),
        ];
        if ((bool) $request->input("grayscale"))
            $mosaic = new GrayscaleMosaic($options);
        else
            $mosaic = new ColorMosaic($options);
        return $mosaic->convert_to_html();
    }
}
