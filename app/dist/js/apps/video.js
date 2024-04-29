window.onload = () => {
  var list = document.getElementById("image-preview-upload");

  // (C2) INIT PLUPLOAD

  var uploader = new plupload.Uploader({
    runtimes: "html5",
    browse_button: "pick",
    url: "../chat-passer?video",
    chunk_size: "50mb",
    multi_selection: false,
    multipart_params: {
      upload_video: "true",
    },
    filters: {
      max_file_size: "200mb",
      mime_types: [
        {
          title: "Videos",
          extensions: "mp4,mov",
        },
      ],
    },
    init: {
      PostInit: (e) => (list.innerHTML = ""),
      FilesAdded: (up, files) => {
        plupload.each(files, (file) => {
          // console.log(file);
          let row = document.createElement("div");
          row.id = file.id;
          row.innerHTML = `${file.name} (${plupload.formatSize(
            file.size
          )}) <strong></strong>`;
          list.innerHTML = "";
          list.appendChild(row);
        });
        //   uploader.start();
      },
      UploadProgress: (up, file) => {
        document.querySelector(
          `#${file.id} strong`
        ).innerHTML = `${file.percent}%`;
        if (file.percent == 100) {
          document.querySelector(`#${file.id} strong`).innerHTML =
            "<br><b class='text-warning'>Processing file this will take some moments depend on how large your file is and your internet connection.</b>";
          // console.log(up);
          // console.log("perfrom action here");
        }
      },
      BeforeUpload: (up, file) => {},

      // up.settings.multipart_params = { data: up._options.form.fd };

      FileUploaded: (up, file, result) => {
        // console.log(result);
        console.log("process video");
        proceessjson(result.response);
        setCookie("isSave", true, 1);
        // console.log(up._options.form);
      },
      UploadComplete: (up, file, result) => {
        // console.log(up, result);
        up._options.form.inputs.prop("disabled", false);
        
      },
      Error: (up, err) => {
        response = JSON.parse(err.response);
        list.innerHTML = response.info;
        up.stop();
      },
      // Error: (up, err) => console.error(err),
    },
  });
  uploader.init();
  if (uploader) {
    console.log("File ini");
    const chatElelment = document.querySelector("#chatForm");
    chatElelment.addEventListener("submit", (event) => {
      // Prevent default posting of form - put here to work in case of errors
      event.preventDefault();
      // Abort any pending request
      if (request) {
        request.abort();
      }
      // setup some local variables
      var $form = $(event.target);
      var fd = new FormData(chatElelment);
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
      console.log(serializedData);
      if (uploader.files.length > 0) {
        // for(i=0; i < $inputs.length; i++) {
        //     input = $inputs[i];
        //     if(input.tagName == "INPUT"){
        //       uploader.multipart_params[input.name] = input.value;
        //     }
        // }
        // console.log(uploader.multipart_params);
        uploader.setOption("form", {
          inputs: $inputs,
        });
        var sData = serialtoobj(serializedData);
        for (const key in sData) {
          uploader.settings.multipart_params[key] = sData[key];
        }
        uploader.refresh();

        // uploader.
        setCookie("isSave", false, 1);
        holder = uploader.start();
        // console.log(holder);
      } else {
        runjax(request, event, $inputs, fd, action);
      }
    });
  }

  function serialtoobj(queryString) {
    const queryParams = queryString.split("&");
    const object = {};
    for (let i = 0; i < queryParams.length; i++) {
      const keyValue = queryParams[i].split("=");
      object[keyValue[0]] = keyValue[1];
    }
    return object;
  }
};
