function error() {
    alert("YOU HAZ ERRORS");
}
function hideLoadingScreen() {
    document.getElementById("loadingElements").style.display = "none";
}
hideLoadingScreen();
function getImages(query) {
    $.post({
        url: "/lol",
        data: {
            text: query
        }
    }).done(function (httpDataStr) {
        var httpData = JSON.parse(httpDataStr);
        if (httpData["error"] != undefined) {
            error();
        }
        var pusher = new Pusher($('meta[name="pusher"]').attr('content'), {
            cluster: "eu"
        });
        var channel = pusher.subscribe(httpData.data.task);
        channel.bind("lol", function (pusherData) {
            var imageCont = document.createElement("div");
            pusherData.message.ids.forEach(function (id) {
                var newImage = document.createElement("img");
                newImage.src = "result/" + id;
                imageCont.appendChild(newImage);
            });
            document.getElementById("imagePlacement").appendChild(imageCont);
        });
    }).fail(function (error) {
        error();
    });
}
function postForm() {
    if (document.getElementById("inputBox").value != "") {
        alert("Hi");
    }
    else {
        alert("Please enter a value");
    }
}
