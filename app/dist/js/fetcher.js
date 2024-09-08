// var loaddata = document.querySelectorAll("[data-load]");
// for (var i in loaddata) {
//   if (loaddata.hasOwnProperty(i)) {
//     what = loaddata[i].getAttribute("data-load");
//     displayId = loaddata[i].getAttribute("data-displayId");
//     start = loaddata[i].getAttribute("data-start") || 0;
//     limit = loaddata[i].getAttribute("data-limit") || 1;
//     fetchData(what, displayId, limit, start);
//   }
// }

  let searchM = document.querySelector("#searchMarket");
  let searchIsSave = true;
if(searchM) {
  searchM.addEventListener("keydown", (e) => {
    searchMarket();
  });
}


function searchMarket() {
  if (!searchIsSave) return false;
    searchIsSave = false;
    var fetchID = searchM.getAttribute("data-id");
    fetchDiv = document.querySelector(fetchID);
    var platform = "";
    var category = "all";
    if (document.querySelector("#searchMarketPlaform")) {
      platform = document.querySelector("#searchMarketPlaform").value;
    }
    if (document.querySelector("#searchMarketcategory")) {
      category = document.querySelector("#searchMarketcategory").value;
    }
    fetchDiv.setAttribute(
      "data-path",
      "passer?a=account&s=" + searchM.value + "&platform=" + platform +"&category=" + category
    );
    if (loadFetchData(fetchDiv)) searchIsSave = true;
}

function addPlatfrom(value, name = "") {
  document.querySelector("#PlaformName").innerHTML = name;
  document.querySelector("#searchMarketPlaform").value = value;
  searchMarket();
}

document.querySelectorAll("[data-load]").forEach(loaddata => {
  loadFetchData(loaddata);
});

function loadFetchData (loaddata) {
  const what = loaddata.getAttribute("data-load");
  const displayId = loaddata.getAttribute("data-displayId");
  const start = loaddata.getAttribute("data-start") ?? 0;
  const limit = loaddata.getAttribute("data-limit") ?? 1;
  const path = loaddata.getAttribute("data-path") ?? "passer";
  const isReplace = loaddata.getAttribute("data-isreplace") ?? 'false';
  const interval = loaddata.getAttribute("data-interval") ?? 3000;
  document.querySelector("#" + displayId).innerHTML = "";
  fetchData(what, displayId, limit, start, path, isReplace, interval);
  return true;
}

// function fetchData(what, displayId, limit = 1, start = 0, path="passer") {
//   data = { page: what, what: what, start: start };
//   request = $.ajax({
//     type: "POST",
//     url: path+gets(),
//     data: data,
//   });

// }


// document.querySelectorAll("[data-ajax-data]").forEach(loaddata => {
//   const what = loaddata.getAttribute("data-ajax-data");
//   const displayId = loaddata.getAttribute("data-displayId");
//   const start = loaddata.getAttribute("data-start") || 0;
//   const limit = loaddata.getAttribute("data-limit") || 1;
//   loadAjaxData(what, displayId, limit, start);
// });

// function loadAjaxData(what, displayId, limit = 1, start = 0) {
//   request = $.ajax({
//     type: "POST",
//     url: "fetcher"+gets()+"&start="+start+"&limit="+limit,
//     data: {what: what},
//   });

//   request.done(function (response) {
//     if (response == null || response == "null" || response == "" || response == "No data found") {
//         start = 0;
//         return null;
//     }
//     if(document.getElementById(displayId).innerHTML == response) {
//      return false;
//     } 

//     document.getElementById(displayId).innerHTML += response;
//     start = parseInt(start) + parseInt(limit);
//     loadAjaxData(what, displayId, limit, start);
    
//   });

// }

function fetchData(what, displayId, limit = 1, start = 0, path = "passer", isReplace="false", interval = 3000) {
  var loading = "<p class='h4'><b>Loading Data</b></p>";
  // var displayHere = document.getElementById(displayId);
  // if(displayHere.innerHTML == "") displayHere = loading;
  data = { page: what, what: what, start: start };
  request = $.ajax({
    type: "POST",
    url: path == "passer" ? path+gets() : path,
    data: data,
  });
  request.done(function (response) {
    if (response == null || response == "null" || response == "  " || response.replace(/ /g, '') == "", response.replace(/ /g, '') == " ") {
      start = 0;
        return null;
    }
    if (checkJSON(response)) {
      obj = JSON.parse(response);
      if(obj['status'] != "ok") return null;
      response = obj['data'];
    }
    // if (displayHere.innerHTML == loading) {
    //   displayHere.innerHTML = response;
    // } else {
    //   displayHere.innerHTML += response;
    // }
    try {
      (isReplace == "false") ? document.getElementById(displayId).innerHTML += response : document.getElementById(displayId).innerHTML = response;
    } catch (error) {
      
    }

    start =  parseInt(start) + parseInt(limit);
    if(document.getElementById(displayId)){
      console.log("True gt here");
      setTimeout(function() {
        fetchData(what, displayId, limit, start, path, isReplace);
    }, parseInt(interval)); 
    }
    
  });
}

function checkJSON(text) {
  if (typeof text !== "string") {
      return false;
  }
  try {
      JSON.parse(text);
      return true;
  } catch (error) {
      return false;
  }
}

function get_user_info(userID) {
    if(!document.getElementById(userID)) { return null; }
    data = document.getElementById(userID);
    if(data.innerHTML != "" || data.innerHTML != "loading...") { return null;}
    request = $.ajax({
        type: "POST",
        url: "passer",
        data: {userdetails: userID},
      });
      request.done(function (response) {
        data.innerHTML = response;
      });
}

function display_content(data) {
    document.querySelectorAll('.chat-list').forEach(function(el) {
        el.style.visibility = 'hidden';
        el.style.display = 'none';
     });
   var id = $(data).data('user-id');
   if(!document.getElementById("content"+id)) {
    fetchUserData("displayDetails", id);
}else{
    document.getElementById("content"+id).style.visibility = "visible";
    document.getElementById("content"+id).style.display = "block";
}


}

function fetchUserData(displayId, id) {
    request = $.ajax({
        type: "POST",
        url: "passer",
        data: { page: "userdetails", what: "userdetails", ID: id, start: 0 },
      });
      request.done(function (response) {
        if (response == null || response == "null" || response == "") {
            start = 0;
            return null;
        }
        document.getElementById(displayId).innerHTML += response;
        document.getElementById("content"+id).style.visibility = "visible";
        document.getElementById("content"+id).style.display = "block";
      });
}

function getwalletinfo(id, displayId='display-wallet-info') {
  document.getElementById(displayId).innerHTML = "<b class='text-warning'>getting data...</b>";

  request = $.ajax({
    type: "POST",
    url: "passer",
    data: { page: "wallets", what: "wallet", ID: id},
  });
  request.done(function (response) {
    document.getElementById(displayId).innerHTML = response;
    qr = document.getElementById("genqr");
    get_qr_code(qr);
  });
}


function gets() {
  const urlParams = new URLSearchParams(window.location.search);
  var params = "?o=0";

  for (const [key, value] of urlParams) {
      params += "&"+key+"="+value;
  }
  return params;
}


