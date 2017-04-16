function hideLoadingScreen() {
    document.getElementById("loadingElement").style.display = "none";
}

$(function () {
    return hideLoadingScreen();
});

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

function getImages(query) {
    displayLoadingScreen();
    $.post({
        url: "https://api.memetrash.co.uk/".concat(Math.random() > 0.6666 ? 'cat' : (Math.random() > 0.5 ? 'doge' : 'fat')),
        crossDomain: true,
        data: {
            text: query,
            quantity: 3
        }
    }).done(function (httpData) {
        var imageCont = document.createElement("div");
        imageCont.id = "downloadedImageInner"

        httpData["data"]["images"].forEach(function (image) {
            var newImage = document.createElement("img");
            newImage.src = image;
            imageCont.appendChild(newImage);
        });

        document.getElementById("downloadedImageOuter").style.display = "block";
        document.getElementById("downloadedImageOuter").replaceChild(imageCont, document.getElementById("downloadedImageInner"))
        hideLoadingScreen();
    }).fail(function (error) {
        hideLoadingScreen();
        alert("YOU HAZ ERRORS");
    });
}

function postForm() {
    if (document.getElementById("inputBox").value != "") {
        getImages(document.getElementById("inputBox").value);
    } else {
        alert("Enter a value");
    }
}
