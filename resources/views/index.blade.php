<!doctype html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config("app.name") }}</title>
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
        <script src="{{ asset('js/jquery-3.4.1.min.js' ) }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container">
                <span class="text-white">
                    <img class="logo mr-2" src="{{ asset('images/cube.png') }}" alt="Rubik's Mosaics">
                    Rubik's Mosaics
                </span> 
            </div>
        </nav>
        <div class="container">
            <div class="row">
                <div class="col-12 mt-4">
                    <div class="card shadow mb-5">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 col-lg-6">
                                    <div class="form-row">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="hidden-file-input">Image</label>
                                                <div class="card border-bottom-0">
                                                    <div class="card-body p-0">
                                                        <div id="upload-container">
                                                            <input type="file" name="image" id="hidden-file-input" class="hidden-file-input">
                                                            <div id="thumbnail-container" class="thumbnail-container w-100">
                                                            </div>
                                                        </div>
                                                        <div id="mosaic-container" style="display:none"></div>
                                                    </div>
                                                </div>
                                                <a id="image-upload-label" class="image-upload-label bg-green text-white border border-top-0" href="javascript:;">Upload image</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6">
                                    <div class="form-row mt-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="width-input">Width</label>
                                                <input id="width-input" type="number" class="form-control" pattern="[0-9]*" min="1" max="100" value="15">
                                                <small class="text-muted">Number of cubes</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="col-12">
                                            <div class="form-group">
                                                <label for="grayscale-select">Match</label>
                                                <select id="grayscale-select" class="form-control">
                                                    <option value="0">Nearest Color</option>
                                                    <option value="1" selected>Tone</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label for="brightness-input">Brightness</label>
                                                <input id="brightness-input" type="range" class="custom-range" min="-40" max="40" step="10" value="0">
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-between">
                                            <small class="text-muted">-40</small>
                                            <small class="text-muted">0</small>
                                            <small class="text-muted">40</small>
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label for="contrast-input">Contrast</label>
                                                <input id="contrast-input" type="range" class="custom-range" min="-40" max="40" step="10" value="0">
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-between">
                                            <small class="text-muted">-40</small>
                                            <small class="text-muted">0</small>
                                            <small class="text-muted">40</small>
                                        </div>
                                    </div>
                                    <div class="form-row mt-3">
                                        <div class="col-12">
                                            <div class="form-group mb-0">
                                                <label for="dither-input">Dither</label>
                                                <input id="dither-input" type="range" class="custom-range" min="0" max="1" step="0.1" value="0.4">
                                            </div>
                                        </div>
                                        <div class="col-12 d-flex justify-content-between">
                                            <small class="text-muted">0</small>
                                            <small class="text-muted">0.5</small>
                                            <small class="text-muted">1</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="form-row mt-5">
                                        <div class="col-12 text-center">
                                            <button id="render-button" type="button" class="btn btn-block btn-primary">Render</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="mosaic-modal" class="modal fade" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="row">
                        <div class="col-12">
                            <div id="preview"></div>
                        </div>
                    </div>
                </div>
          </div>
        </div>
        <script>
        var show = function() {
            var form_data = new FormData;
            form_data.append("image", $("#hidden-file-input")[0].files[0]);
            form_data.append("width", $("#width-input").val());
            form_data.append("brightness", $("#brightness-input").val());
            form_data.append("contrast", $("#contrast-input").val());
            form_data.append("dither", $("#dither-input").val());
            form_data.append("grayscale", $("#grayscale-select").val());
            $.ajax({
                url: "/mosaic",
                type: "POST",
                data: form_data,
                processData: false,
                contentType: false,
                beforeSend: function(xhr) {
                    xhr.setRequestHeader("X-CSRF-TOKEN", $("meta[name=csrf-token]").attr("content"));
                },
                success: function(response) {
                    $("#mosaic-container").empty();
                    for (var y=0; y<response.length; y++) {
                        var row = response[y];
                        var html = $('<div class="d-flex"></div>');
                        for (var x=0; x<row.length; x++) {
                            var cube = row[x];
                            var html_cube = $('<div class="cube flex-fill"></div>');
                            for (var i=0; i<cube.length; i++) {
                                var html_pixel = $('<div class="pixel bg-' + cube[i] + '"></div>');
                                html_cube.append(html_pixel);
                            }
                            html.append(html_cube);
                        }
                        $("#mosaic-container").append(html);
                    }
                    $("#upload-container").hide();
                    $("#mosaic-container").show();
                    $(document).scrollTop(0);
                },
        
            });
        };

        $("#render-button").on("click", function() {
            $("#preview").empty();
            show();
        });

        $(document).on("click", ".cube", function() {
            if ($(this).hasClass("completed-cube")) {
                $(this).removeClass("completed-cube");
            }
            else {
                if ($(this).hasClass("active-cube")) {
                    $(this).addClass("completed-cube");
                }
                else {
                    $(".cube").removeClass("active-cube");
                    $(this).addClass("active-cube");
                    $("#preview").html($(this).html());
                    $("#mosaic-modal").modal();
                }
            }
        });

        $("#image-upload-label").on("click", function(){
            $("#hidden-file-input").click();
        });

        $("#hidden-file-input").on("change", function() {
            var file = this.files[0];
            var reader = new FileReader();
            reader.onloadend = function () {
               $("#thumbnail-container").css("background-image", "url('" + reader.result + "')");
            }
            if (file) {
                reader.readAsDataURL(file);
            }
            $("#mosaic-container").hide();
            $("#upload-container").show();
        });

        </script>
    </body>
</html>

