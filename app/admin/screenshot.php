<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link id="themeColors" rel="stylesheet" href="../dist/css/style.min.css" />
    <?php
    require_once "../consts/main.php";
    require_once "include/database.php";
    require_once "functions/screenshot.php";
    $s = new Screenshot;
    ?>
    <style>
        * {
            box-sizing: border-box;
            padding: 0;
            margin: 0;
        }

        #info {
            padding-left: 10px;
            border-radius: 10px;
            position: absolute;
            user-select: none;
            font-size: 2em;
            width: 120px;
            color: #EEEEEE;
            background-color: #FD7013;
        }

        .buttons {
            width: auto;
            margin-left: 10px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            
            /* text-align: left; */
            height: 200px;
            margin-top: 10px;
            border-radius: 20px;
        }

        .buttons>button {
            width: 100%;

        }

        .label {
            border: 1px solid gainsboro;
            margin-top: 10px;
        }

        .label:hover,
        .active {
            zoom: 110%;
            border: 1px solid lightgreen;
        }

        #placeholder {
            display: none;
        }
        #screenshot {
            overflow: hidden;
            height: 100vh;
            width:fit-content;
        }
        #screenshot img {
            object-fit: contain;
            height: 100vh;
            width: 100%;
        }
    </style>
</head>
<!-- <button id="new-fund" data-url="modal?p=investment&action=transfer" data-title="Transfer Funds" onclick="modalcontent(this.id)"  class='btn btn-success ms-1'><i class='ti ti-arrows-down-up'></i> Transfer Funds</button> -->

<body>
    <!-- <div class="card-header">
  <button class="btn btn-sm btn-primary"> <i class='ti ti-plus'></i> Add</button>
</div> -->
    <?php require_once "pages/screenshot/placeholder.php"; ?>
    <div id="info"></div>
    <div class="row d-flex">
        <div id="screenshot">
            <img src="../assets/images/screenshot/coinbase.jpg" alt="">
        </div>

        <div class="buttons bg-dark">
            <button  data-bs-toggle="modal" data-bs-target="#bs-example-modal-md" class='bg-transparent mt-2 text-light border-0'><i class='ti ti-plus'></i> Add</button>
            <button id="pickPosition" class='bg-transparent mt-2 text-light border-0'><i class='ti ti-plus'></i> Position</button>
            <button class='bg-transparent mt-2 text-light border-0'><i class='ti ti-plus'></i></button>
        </div>
        <div class="col-md-7">
            <div class="card">
                <div class="card-header">
                    <h3>Infomation</h3>
                </div>
                <div class="card-body" id="labels"></div>
            </div>
        </div>
    </div>

    <div id="bs-example-modal-md" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true">
        <div class="modal-dialog modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header d-flex align-items-center">
                    <h6 class="modal-title" id="myModalLabel">
                        Add new Label
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>


                <!-- <form action="" id="foo"> -->
                <!-- <div id="custommessage"></div> -->
                <div class="modal-body col-12" id="modal-body">
                    <form action="" id="label_form">
                        <input type="text" class="form-control" placeholder="Label name" id='labelName'> <br>
                        <div id="message"></div><br>
                        <button type="button" class="btn btn-primary" id="addInfo"><i class="ti ti-plus"></i> Add.</button>
                    </form>
                </div>
                <!-- </form> -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-light-danger text-danger font-medium waves-effect" data-bs-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>

    <script src="../dist/libs/jquery/dist/jquery.min.js"></script>
    <script src="../dist/libs/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="js/screenshot.js?n=44"></script>
</body>

</html>