/*errors
success
*/

type GrahamsStuff = SuccessObject;

interface SuccessObject{
    success: {
        message: string
    }
    data: {
        task: string;//Channel to subscribe to
    }
}

interface ErrorObject{
    error: {

    }
}

interface PusherData{
    message: {
        ids: string[]
    }
}

function error(){
    alert("YOU HAZ ERRORS");
    debugger;
}

function hideLoadingScreen(){
    document.getElementById("loadingElement").style.display = "none";
}

$(() => hideLoadingScreen());

var animations = [
    "spin",
    "zoom",
    "bg",
    "invert",
    "blur",
    "rainbow"
]

function displayLoadingScreen(){
    var animationToUse = animations[Math.floor(Math.random() * animations.length)];
    document.getElementById("loadingElement").style.display = "block";
    document.getElementById("loadingTrollFaceScreen").style.animationName = animationToUse;
}

var pusher: _pusher.Pusher;
$(() => {
    pusher = new Pusher($('meta[name="pusher"]').attr('content'), {
        cluster: "eu"
    });
})

function getImages(query: string){
    displayLoadingScreen()
    $.post({
        url: "/lol",
        data: {
            text: query
        }
    }).done((httpDataStr: string) => {
        var httpData = <GrahamsStuff>JSON.parse(httpDataStr);

        if(httpData["error"] != undefined){
            error();
        }

        var channel = pusher.subscribe(httpData.data.task);
        channel.bind("lol", (pusherData: PusherData) => {
            var imageCont = document.createElement("div");
            pusherData.message.ids.forEach(id => {
                let newImage = document.createElement("img");
                newImage.src = "result/" + id;
                imageCont.appendChild(newImage);
            })
            document.getElementById("downloadedImageOuter").appendChild(imageCont);
            hideLoadingScreen();
        })
    }).fail((error) => {
        error();
    })
}

function postForm(){
    if((<HTMLInputElement>document.getElementById("inputBox")).value != ""){
        getImages((<HTMLInputElement>document.getElementById("inputBox")).value);
    }
    else{
        alert("Enter a value");
    }
}
