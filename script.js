var i = 0;
function show_info_box(message, iserror){
    i++;
    if(iserror){
        var r = Math.floor(Math.random() * 100);
        $("body").prepend('<div class="notify_box float error" id="' + r + '"></div>');
        $("#" + r).append('<p class="notify_content"><i class="bx bxs-error"></i> ' + message + '</p>');
        $("#" + r).css('top', (parseInt($("#" + r).css('top')) * i ) + 40 + "px");
        setTimeout(function(){ $("#" + r).addClass("animate")}, 4500);
        setTimeout(function(){ $("#" + r).remove(); i--;}, 5000);
    }else{
        var r = Math.floor(Math.random() * 100);
        $("body").prepend('<div class="notify_box float success" id="' + r + '"></div>');
        $("#" + r).append('<p class="notify_content"><i class="bx bxs-check-circle"></i> ' + message + '</p>');
        $("#" + r).css('top', (parseInt($("#" + r).css('top')) * i ) + 40 + "px");
        setTimeout(function(){ $("#" + r).addClass("animate")}, 4500);
        setTimeout(function(){ $("#" + r).remove(); i--;}, 5000);
    }
}

function show_modal(modal){
    $("#" + modal).css('display', 'block');
}

$(".close").click(function(){
    $(".modal_container").css('display', 'none');
});

$(".nav_toggle").click(function(){
    $(".items").toggleClass("visible");
});

function playSound(url) {
    const audio = new Audio(url);
    audio.play();
}