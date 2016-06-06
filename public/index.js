function error() {
    alert("YOU HAZ ERRORS");
    debugger;
}
function hideLoadingScreen() {
    document.getElementById("loadingElement").style.display = "none";
}
$(function () { return hideLoadingScreen(); });
var animations = [
    "spin",
    "zoom",
    "bg",
    "invert",
    "blur",
    "rainbow"
];
function displayLoadingScreen() {
    var animationToUse = animations[Math.floor(Math.random() * animations.length)];
    document.getElementById("loadingElement").style.display = "block";
    document.getElementById("loadingTrollFaceScreen").style.animationName = animationToUse;
}
var pusher;
$(function () {
    pusher = new Pusher($('meta[name="pusher"]').attr('content'), {
        cluster: "eu"
    });
});
function getImages(query) {
    displayLoadingScreen();
    $.post({
        url: "/lol",
        data: {
            text: query
        }
    }).done(function (httpData) {
        if (httpData["error"] != undefined) {
            error();
        }
        var channel = pusher.subscribe(httpData.data.task);
        channel.bind("lol", function (pusherData) {
            var imageCont = document.createElement("div");
            imageCont.id = "downloadedImageInner"
            pusherData.message.ids.forEach(function (id) {
                var newImage = document.createElement("img");
                newImage.src = "result/" + id;
                imageCont.appendChild(newImage);
            });
            document.getElementById("downloadedImageOuter").replaceChild(imageCont, document.getElementById("downloadedImageInner"))
            hideLoadingScreen();
        });
    }).fail(function (error) {
        error();
    });
}
function postForm() {
    if (document.getElementById("inputBox").value != "") {
        getImages(document.getElementById("inputBox").value);
    }
    else {
        alert("Enter a value");
    }
}
