<?php

namespace App\Http\Controllers;

use App\Mosaic;
use Illuminate\Http\Request;

class MosaicController extends Controller
{
    public function store(Request $request) {
        $args = (object) [
            "image" => "/home/mark/archive/Desktop/mosaic/IMG_2666.jpg",
            "width" => $request->input("width"),
            "dither" => $request->input("dither"),
        ];
        $mosaic = new Mosaic($args);
        return $mosaic->convert_to_html();
    }
}
