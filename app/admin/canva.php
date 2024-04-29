<?php
session_start();
require_once "include/session.php";
// if generate exist and id does not exit
if (isset($_GET['generate']) && !isset($_GET['id'])) {
    require_once "../consts/main.php";
    require_once "include/database.php";
    $d = new database;
    $id = $d->getall("screenshot_settings", "shapes != ? order by RAND()", [""])["ID"];
    $d->loadpage("?generate=i&id=$id");
    exit();
    // var_dump($d->getall("screenshot_settings", "shapes != ? order by RAND()", [""])["ID"]);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Canvas Drag and Drop.</title>
    <link id="themeColors" rel="stylesheet" href="../dist/css/style.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.js">
    </script>
    <style>
        *,
        #canvas img {
            margin: 0;
            padding: 0;
        }

        #canvas {
            overflow: hidden;
            height: 100vh;
            width: fit-content;
            background-color: transparent;
        }

        #canvas>* {
            /* resize: both; */
            /* overflow: auto; */
            /* width: 20px;
           height: 20px; */
            /* background-color: black; */
        }

        #canvas img {
            object-fit: contain;
            height: 100vh;
            width: 100%;
        }
    </style>
</head>

<body style="margin: 0; padding: 0;">
    <div class="row d-flex">
        <div id="canvas" style="margin: 0; padding: 0;">
            <img src="" alt="">
        </div>
        <div class="col-md-7 <?php if (isset($_GET['generate'])) {
                                    echo "d-none";
                                } ?>">
            <div class="card">
                <div class="card-header">
                    <h3>Infomation</h3>
                    <div class="" id="control">
                        <div id="displayMessage"></div>
                        <div class="shadow-sm p-3 flex">
                            <button class="btn btn-primary" id="addShape"> <i class="fas fa-plus"></i> Add</button>
                            <button class="btn btn-dark ml-2" id="duplicateShape"> <i class="fas fa-copys"></i> Duplicate</button>
                            <button class="btn btn-dark ml-2" id="copyShape"> <i class="fas fa-copy"></i> Copy</button>
                            <a class="btn btn-dark ml-2" href="#" id="download"> <i class="fas fa-download"></i> Download</a>
                            <button class="btn btn-danger ml-2" id="removeShape"> <i class="fas fa-trash"></i> Remove</button>
                            <button class="btn btn-success ml-2" id="saveShape"> <i class="fas fa-save"></i> Save</button>
                        </div>

                        <div class="row p-3 shadow-sm mt-3">
                            <div class="form-group col-md-6 col-12">
                                <label for="">Text Color</label>
                                <input class="form-control" type="color" name="color" class="ml-2" id="changeColor">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="">Background Color</label>
                                <input class="form-control" type="color" name="background" class="ml-2" id="changeBackgroundColor">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="">Text Size</label>
                                <input class="form-control" type="number" name="size" class="ml-2" id="textSize" placeholder="font-size">
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label for="">Width</label>
                                <input class="form-control" type="number" placeholder="Box Width" name="width" id="changeWdith">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="">Height</label>
                                <input class="form-control" type="number" placeholder="Box Height" name="height" id="changeHeight">
                            </div>
                            <div class="form-group col-md-6 col-12">
                                <label for="">Align Text</label>
                                <select name="align" class="form-control" id="alignText">
                                    <option value="left">Align Text Left</option>
                                    <option value="right">Align Text right</option>
                                    <option value="center">Align Text center</option>
                                </select>
                            </div>

                            <div class="form-group col-md-6 col-12">
                                <label for="">Add Css</label>
                                <textarea name="style" cols="30" class="form-control bg-dark" id="addstyle" rows="10"></textarea>
                            </div>

                        </div>

                        <div class="p-3 shadow-sm mt-2">
                            <div class="form-group">
                                <label for="inputType">Input Type</label>
                                <select class="form-control" name="inputType" id="inputType">
                                    <option value="name">Name</option>
                                    <option value="bitcoin">BTC</option>
                                    <option value="number">Number</option>
                                    <option value="date">Date</option>
                                    <option value="money">Money</option>
                                    <option value="regex">Regex</option>
                                    <option value="static">static</option>
                                </select>
                            </div>
                            <div id="info" class="mt-2">
                                <div id="name"></div>
                                <div id="number" class="row d-flex">
                                    <div class="col-md-6 col-12">
                                        <input type="number" class="form-control" step=".01" id="nomin" name="nomin" placeholder="Min Number">
                                    </div>
                                    <div class="col-md-6 col-12">
                                        <input type="number" class="form-control" step=".01" id="nomax" name="nomax" placeholder="Max Number">
                                    </div>
                                </div>
                                <div id="money" class="row d-flex">
                                    <div class="col-md-4 col-12">
                                        <input type="number" class="form-control" step=".01" id="amountmin" name="amountmin" placeholder="Min Amount">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="number" class="form-control" step=".01" id="amountmax" name="amountmax" placeholder="Max Amount">
                                    </div>
                                    <div class="col-md-4 col-12">
                                        <input type="text" class="form-control" id="currency" name="currency" placeholder="Currency Symbol">
                                    </div>
                                </div>
                                <div id="regex">
                                    <input type="text" class="form-control" id="regexpattern" name="regexpattern" placeholder="Paste regex pattern">
                                    <input type="text" class="form-control" id="regexpatternlength" name="regexpatternlength" placeholder="Paste regex pattern length">
                                </div>
                                <div id="static">
                                    <input type="text" class="form-control" id="statictext" name="statictext" placeholder="Your static text here">
                                </div>
                                <div id="date">
                                    <input type="text" class="form-control" id="datepattern" name="datepattern" placeholder="date pattern">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="labels"></div>
            </div>
        </div>
    </div>
    <script src="../dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="js/canva.js?n=90996"></script>
</body>

</html>
