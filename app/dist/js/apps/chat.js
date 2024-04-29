setCookie("isSave", true, 1);
var url = new URL(window.location.href);
var chatnew = document.getElementById("chatnew");
var chatbox = document.querySelector(".chat-box");
const loadinging = document.getElementById("loadining");

$(function () {
  var chatarea = $("#chat");

  $("#chat .message-center a").on("click", function () {
    var name = $(this).find(".mail-contnet h5").text();
    var img = $(this).find(".user-img img").attr("src");
    var id = $(this).attr("data-user-id");
    var status = $(this).find(".profile-status").attr("data-status");

    if ($(this).hasClass("active")) {
      $(this).toggleClass("active");
      $(".chat-windows #user-chat" + id).hide();
    } else {
      $(this).toggleClass("active");
      if ($(".chat-windows #user-chat" + id).length) {
        $(".chat-windows #user-chat" + id)
          .removeClass("mini-chat")
          .show();
      } else {
        var msg = msg_receive(
          "I watched the storm, so beautiful yet terrific."
        );
        msg += msg_sent("That is very deep indeed!");
        var html =
          "<div class='user-chat' id='user-chat" +
          id +
          "' data-user-id='" +
          id +
          "'>";
        html +=
          "<div class='chat-head'><img src='" +
          img +
          "' data-user-id='" +
          id +
          "'><span class='status " +
          status +
          "'></span><span class='name'>" +
          name +
          "</span><span class='opts'><i class='material-icons closeit' data-user-id='" +
          id +
          "'>clear</i><i class='material-icons mini-chat' data-user-id='" +
          id +
          "'>remove</i></span></div>";
        html +=
          "<div class='chat-body'><ul class='chat-list'>" + msg + "</ul></div>";
        html +=
          "<div class='chat-footer'><input type='text' data-user-id='" +
          id +
          "' placeholder='Type & Enter' class='form-control'></div>";
        html += "</div>";
        $(".chat-windows").append(html);
      }
    }
  });
});

// *******************************************************************
// Chat Application
// *******************************************************************

$(".search-chat").on("keyup", function () {
  var value = $(this).val().toLowerCase();
  $(".chat-users li").filter(function () {
    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
  });
});

$(".app-chat .chat-user ").on("click", function (event) {
  if ($(this).hasClass(".active")) {
    return false;
  } else {
    var findChat = $(this).attr("data-user-id");
    var personName = $(this).find(".chat-title").text();
    var personImage = $(this).find("img").attr("src");
    var hideTheNonSelectedContent = $(this)
      .parents(".chat-application")
      .find(".chat-not-selected")
      .hide()
      .siblings(".chatting-box")
      .show();
    var showChatInnerContent = $(this)
      .parents(".chat-application")
      .find(".chat-container .chat-box-inner-part")
      .show();

    if (window.innerWidth <= 767) {
      $(".chat-container .current-chat-user-name .name").html(
        personName.split(" ")[0]
      );
    } else if (window.innerWidth > 767) {
      $(".chat-container .current-chat-user-name .name").html(personName);
    }
    $(".chat-container .current-chat-user-name img").attr("src", personImage);
    $(".chat").removeClass("active-chat");
    $(".user-chat-box .chat-user").removeClass("bg-light");
    //$('.chat-container .chat-box-inner-part').css('height', '100%');
    $(this).addClass("bg-light");
    $(".chat[data-user-id = " + findChat + "]").addClass("active-chat");
  }
  if ($(this).parents(".user-chat-box").hasClass("user-list-box-show")) {
    $(this).parents(".user-chat-box").removeClass("user-list-box-show");
  }
  $(".chat-meta-user").addClass("chat-active");
  //$('.chat-container').css('height', 'calc(100vh - 158px)');
  $(".chat-send-message-footer").addClass("chat-active");
});

// Send Messages
$(".message-type-box").on("keydown", function (event) {
  if (event.key === "Enter") {
    // Start getting time
    var now = new Date();
    var hh = now.getHours();
    var min = now.getMinutes();

    var ampm = hh >= 12 ? "pm" : "am";
    hh = hh % 12;
    hh = hh ? hh : 12;
    hh = hh < 10 ? "0" + hh : hh;
    min = min < 10 ? "0" + min : min;

    var time = hh + ":" + min + " " + ampm;
    // End

    var chatInput = $(this);
    var chatMessageValue = chatInput.val();
    if (chatMessageValue === "") {
      return;
    }
    $messageHtml =
      '<div class="text-end mb-3"> <div class="p-2 bg-light-info text-dark rounded-1 d-inline-block fs-3">' +
      chatMessageValue +
      '</div> <div class="d-block fs-2">' +
      time +
      "</div>  </div>";
    var appendMessage = $(this)
      .parents(".chat-application")
      .find(".active-chat")
      .append($messageHtml);
    // var clearChatInput = chatInput.val("");
  }
});

// *******************************************************************
// Email Application
// *******************************************************************

$(document).ready(function () {
  $(".back-btn").click(function () {
    $(".app-email-chatting-box").hide();
  });
  $(".chat-user").click(function () {
    $(".app-email-chatting-box").show();
  });
});

// *******************************************************************
// chat Offcanvas
// *******************************************************************

$("body").on("click", ".chat-menu", function () {
  $(".app-chat-offcanvas").toggleClass("app-chat-right");
  $(this).toggleClass("app-chat-active");
});


setInterval(function () {
  if (document.getElementById("chat-users").innerHTML != null) {
    try {
      if (
        document.getElementById("mobile-chat-list").innerHTML !=
        document.getElementById("chat-users").innerHTML
      ) {
        document.getElementById("mobile-chat-list").innerHTML =
          document.getElementById("chat-users").innerHTML;
      }
    } catch (error) {}
  }
  // if(document.getElementById("chatnew")) {
  //   // get all messages
  //   get_message();
  //     // check user status
  //   user_status();
  // }

  // get_user_chat_list();
}, 2000); // 3000 milliseconds = 3 seconds

setInterval(function () {
  if(getCookie("isSave") && document.getElementById("chatnew")) {
    get_message();
  }
  
}, 3000);

setInterval(function () {
  user_status();
  get_user_chat_list();
}, 9000);


function get_group_users(start = 0) {
  // console.log("Statat " + start);
  if (
    document.querySelector("#receiverID") &&
    document.querySelector("#grouplist")
  ) {
    var groupID = document.querySelector("#receiverID").value;
    var limit = 100;

    $.ajax({
      type: "post",
      url: "chat-passer?get_group_users=",
      data: {
        get_group_users: groupID,
        start: start,
        limit: limit,
        page: "chat",
      },
      success: function (response) {
        if (response != "null" && response != null && response != "") {
          // console.log(response);
          document.getElementById("grouplist").innerHTML += response;
          get_group_users(start + limit);
        }
      },
    });
  }
}

function get_message() {
  const div = document.querySelector("#chatnew");
  const lastElement = div.lastElementChild;
  var lastchat = lastElement.getAttribute("data-chat-id");
  if (lastchat != null) {
    // console.log(lastchat);
    var chatID = document.querySelector("#chatID").value;
    $.ajax({
      type: "post",
      url: "chat-passer?chat=",
      data: {
        lastchat: lastchat,
        chatID: chatID,
        page: "chat",
        get_chat: 20,
      },
      success: function (response) {
        // console.log(response);
        var Resholder = response.trim();
        
        if (Resholder == "null" || Resholder == "" || Resholder == null) {
          return false;
        }
        console.log(Resholder);
        console.log(lastchat);
        if (lastchat == 0 || lastchat == "0") {
          document.getElementById("chatnew").innerHTML = response;
        } else {
          document.getElementById("chatnew").innerHTML += response;
        }
        iniFromChat();
      },
    });
  }
}
// get_old_message();
function get_old_message(toScroll = true) {
  if(!document.querySelector("#chatnew")) {
    return null;
  }
  const div = document.querySelector("#chatnew");
  const firstElement = div.firstElementChild;
  var firstchat = firstElement.getAttribute("data-chat-id");
  var currentPostion = chatbox.querySelector(".simplebar-content-wrapper").scrollTop;
  if (firstchat != null && loadinging.style.display != "block") {
    loadinging.style.display = "block";
    // console.log(firstchat);
    if(toScroll) activate_preload(true);
    var chatID = document.querySelector("#chatID").value;
    $.ajax({
      type: "post",
      url: "chat-passer?chat=old",
      data: {
        firstchat: firstchat,
        chatID: chatID,
        page: "chat",
        get_chat: 20,
      },
      success: function (response) {
        // console.log(response);
        if (response != "null" && response != "" && response != null) {
          loadrespose(response).then((onResolved)=>{
            if(toScroll){
              chatbox.querySelector(".simplebar-content-wrapper").scroll(
                {
                  top: firstElement.offsetTop - 10,
                  behavior:  "auto",
                }
              );
              activate_preload(false);
            }
          });
            // get_old_message();
            
            

          }
          loadinging.style.display = "none";
          iniFromChat();
          return true;
      },

    });
  }
}

function loadrespose(response) {
  document
            .getElementById("chatnew")
            .insertAdjacentHTML("afterbegin", response);
            return Promise.resolve("Success");
}
function scrollToChat(chatID) {
  activate_preload(true);
  if (!document.getElementById(chatID)) {
    // chatbox.querySelector(".simplebar-content-wrapper").scrollTop = 0;
    get_old_message(false);
    setTimeout(() => {
      scrollToChat(chatID);
    }, 2000);
    return;
  }

  var chat = document.getElementById(chatID);
  
  chatbox.querySelector(".simplebar-content-wrapper").scroll(
    {
      top: chat.offsetTop - 40,
      behavior: "smooth",
    }
  );
  chat.style.backgroundColor = "#37cf0983";
  chat.style.padding = "20px";
  setTimeout(() => {
    chat.style.backgroundColor = "";
    chat.style.padding = "0px";
  }, 1000);

  activate_preload(false);
}

function activate_preload(active = true) {
  if(document.getElementById("pagepreload")) {
   if(active) document.getElementById("pagepreload").classList.remove("d-none");
   else document.getElementById("pagepreload").classList.add("d-none");
  }
}
function iniFromChat() {
  const elements = document.getElementById("chatnew").querySelectorAll('#foo');
  $i = 0;
  elements.forEach(element => {
      console.log("form ini");
      iniForm(element);
      $i++;
  });
}
function reply_to(id, message) {
  if (
    document.getElementById("reply_div") &&
    document.getElementById("reply_message") &&
    document.getElementById("reply_to")
  ) {
    
    document.getElementById("reply_to").value = id;
    document.getElementById("reply_message").innerHTML = message;
    document.getElementById("reply_div").style.display = "block";
    if(document.getElementById("moremessage")) {
      var moremessage = document.getElementById("moremessage");
      moremessage.setAttribute('data-url', removeAfterSubstring(moremessage.dataset.url, "&reply_to=") + "&reply_to="+id+"&message="+message);
    }
  }
}



setTimeout(function () {
  // get all messages

  if (chatbox) {
  
    var chatdiv = chatbox.querySelector(".simplebar-content-wrapper");
    // console.log(chatdiv.scrollHeight);
    // chatdiv.scrollTop = chatdiv.scrollHeight;
    scrollChatToBottom(chatdiv);

  }

 

  chatbox.querySelector(".simplebar-content-wrapper").onscroll = function() {
    scrollH = chatdiv.scrollHeight;
    scrollP = chatdiv.scrollTop;
    scrollW = chatdiv.scrollWidth;
    scrollLastCP = chatdiv.lastElementChild.getBoundingClientRect().top;
    // console.log("LC:"+scrollLastCP + "H:"+scrollH, "P:"+scrollP, "W:"+scrollP, (scrollH - scrollP));
 if((scrollH - scrollP) > 1000){
  console.log("true");
  document.getElementById("godownbtn").style.display = "block";
}else{
  document.getElementById("godownbtn").style.display = "none";
  //  console.log("false");
 }

    if (scrollP < 5) {
      get_old_message();
    }
  }
  // if(getCookie("isSave")){
    
  // }
}, 2000);

function scrollChatToBottom(element) {
  element.scrollTop = element.scrollHeight;

}

function godownbtn() {
   scrollChatToBottom(chatbox.querySelector(".simplebar-content-wrapper"));
}


// get and display user status
function user_status() {
  if (document.querySelector("#receiverID")) {
    const receiverID = document.querySelector("#receiverID").value;
    $.ajax({
      type: "post",
      url: "passer",
      data: {
        get_last_seen: receiverID,
        page: "chat",
      },
      success: function (response) {
        // console.log(response);
        if (response != "null" && document.querySelector("#last_seen")) {
          // console.log(response);
          document.getElementById("last_seen").innerHTML = response;
        }
      },
    });
  }
}

function get_user_chat_list() {
  if (document.querySelector("#chat-users")) {
    console.log("my fird");
    if(!document.getElementById("lastChatTime")) {
      const input = document.createElement('input');
      input.setAttribute('type', 'hidden');
      input.setAttribute('value', '0');
      input.setAttribute('id', 'lastChatTime');
      document.body.appendChild(input);
    }
    var lastChatTime = document.getElementById("lastChatTime");
    var currentTime = Math.floor(new Date().getTime() / 1000);
    // console.log('response');
    $.ajax({
      type: "post",
      url: "chat-passer?get_user_chat_list=",
      data: {
        get_user_chat_list: "",
        page: "chat",
        time: lastChatTime.value,
      },
      success: function (response) {
        // console.log(response);
        if (response != "null" && document.querySelector("#chat-users")) {
          // console.log(response);
          if(handleChatDisplay("chat-users", response, lastChatTime.value))  {
            lastChatTime.value = currentTime;
          }
        }
      },
    });
  }
}

function handleChatDisplay(displayID, content, type) {
  if(content == null || content == "null" || content == "") {
    return ;
  }
  if (!document.getElementById(displayID)) {
    return false;
  }
  var display = document.getElementById(displayID);
  if (type == 0) {
    display.innerHTML = content;
    return true;
  }
  if(!document.querySelector("#chatHolder")) {
    var chatHolder = createHiddenDiv("chatHolder");
    if (!chatHolder) {
      console.log("Unable to create chat holder");
      return false;
    }
  }
  
  var chatHolder = document.querySelector("#chatHolder");
  chatHolder.innerHTML = content;
  const liElements = chatHolder.querySelectorAll("li");
  for (let i = 0; i < liElements.length; i++) {
    const liElement = liElements[i];
    // console.log();
    currentChatID = liElement.querySelector("a").id;
    if (document.getElementById(currentChatID)) {
      mainDiv = document.getElementById(displayID);
      removeElement = mainDiv.querySelector("#" + currentChatID);
      if(removeElement){
        const parent = removeElement.parentNode;
        try {
          mainDiv.removeChild(parent);
        } catch (error) {
          continue;
          // console.log(error);
        }
        
      }
    }
    mainDiv.insertAdjacentHTML(
      "afterbegin",
      "<li>" + liElement.innerHTML + "</li>"
    );
  // Do something with the `liElement` element.
  }
  // set admin back to the top of the chat.
  // pinChat(mainDiv);

// console.log(document.getAttribute("data-ispined"));
  // get atritube with the value off data-isadmin true
  // remove it and move it to the top of the page
  chatHolder.innerHTML = "";
  return true;
}
function pinChat(mainDiv) {
  isPined = document.querySelector(".isPined");
  isPined.remove();
  mainDiv.insertAdjacentHTML(
    "afterbegin",
    "<div class='pined'><li>" + isPined.innerHTML + "</li></div>"
  );
  
}



function createHiddenDiv(divID) {
  const div = document.createElement("div");
  div.id = divID;
  div.style.display = "none";
  document.body.appendChild(div);
  return true;
}

function disposeDiv(divID) {
  document.body.removeChild(divID);
}


// send message

var sendmessage = document.getElementById("sendmessage");
var inputBox = document.getElementById("message-input-box");
if(sendmessage) {
  sendmessage.addEventListener("click", function (e) {
    if(inputBox.value != "") {
      // eraseCookie("isSave");
      setCookie("isSave", false, 1);
      console.log(getCookie("isSave"));
    }
   });
}


function update_chat(id, message) {
  console.log([id, message]);
  let oldMessage = document.getElementById(id);
  if(oldMessage) {
    oldMessage.innerHTML = message;
  }
}
if(!url.searchParams.get("id")) {
}
get_user_chat_list();
get_group_users();
user_status();


// get_old_message();