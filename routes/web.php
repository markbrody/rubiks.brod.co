<?php

Route::get("/", "IndexController@index");
Route::post("mosaic", "MosaicController@store");
