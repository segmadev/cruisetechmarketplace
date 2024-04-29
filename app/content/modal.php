<div id="bs-example-modal-md" class="modal fade" tabindex="-1" aria-labelledby="bs-example-modal-md" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h6 class="modal-title" id="myModalLabel">
                    Medium Modal
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <form action="" id="foo"> -->
            <!-- <div id="custommessage"></div> -->
            <div class="modal-body col-12" id="modal-body"></div>
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

<div id="bs-image-viwer-modal-md" class="modal fade" tabindex="-1" aria-labelledby="bs-image-viwer-modal-md" aria-hidden="true">
    <div class="modal-dialog  modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex align-items-center">
                <h6 class="modal-title" id="myModalLabel">
                    Image Viewer
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <!-- <form action="" id="foo"> -->
            <!-- <div id="custommessage"></div> -->
            <div class="modal-body col-12"><img src="" style="object-fit: contain; width: 100%;" id="imageviewer" alt="loading..."></div>
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


<script>
    function modalcontent(id) {
        var value = document.getElementById(id);
        document.getElementById("modal-body").innerHTML = "Getting data...";
        title = value.dataset.title;
        link = value.dataset.url;
        title += ' | <span class="btn btn-tool fs-3 text-primary" id="' + id + '" data-url="' + link + '" data-id="' + id + '" data-title="' + title + '" onclick="modalcontent(\'' + id + '\')"> <li class="nav-icon fas fa-sync"></li> Reload</span>';
        id = value.dataset.id;
        modaltitle = document.getElementById('myModalLabel').innerHTML = title;
        $.ajax({
            type: 'GET',
            url: link,
            data: {},
            success: function(response) {
                // confirm(response);
                var body = document.getElementById("modal-body");
                body.innerHTML = response;

                const elements = body.querySelectorAll('#foo');
                $i = 0;
                elements.forEach(element => {
                    element.addEventListener("submit", event => {
                        // Prevent default posting of form - put here to work in case of errors
                        event.preventDefault();

                        // Abort any pending request
                        if (request) {
                            request.abort();
                        }
                        // setup some local variables
                        var $form = $(event.target);
                        var fd = new FormData(element);
                        var action = 'passer';
                        if (window.location.href != $form[0].action) {
                            action = $form[0].action;
                        }
                        // Let's select and cache all the fields
                        var $inputs = $form.find("input, select, button, textarea");

                        // Serialize the data in the form
                        var serializedData = $form.serialize();

                        // Let's disable the inputs for the duration of the Ajax request.
                        // Note: we disable elements AFTER the form data has been serialized.
                        // Disabled form elements will not be serialized.
                        $inputs.prop("disabled", true);
                        const params = new URLSearchParams(serializedData);

                        // Fire off the request to /form.php


                        if (params.has("confirm")) {
                            swal({
                                title: "Attention!",
                                text: params.get("confirm"),
                                icon: "warning",

                                buttons: true,
                                dangerMode: true,
                            }).then((willDelete) => {
                                if (willDelete) {
                                    runjax(request, event, $inputs, fd, action);
                                } else {
                                    //   close form
                                }
                            });
                        } else {
                            runjax(request, event, $inputs, fd, action);
                        }

                        // Callback handler that will be called on success

                        // request.always(function () {
                        //     // Reenable the inputs
                        //     $inputs.prop("disabled", false);
                        // });

                    });
                    $i++;
                });

            }
        });
        // document.getElementById("modal-body").innerHTML='<object type="text/html" data="'+link+'" style="width: 100%!important; overflow: auto!important; height: 60vh;"></object>';

    }
</script>