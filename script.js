var notificationCount = 0;

function createNotification(message, type) {
    notificationCount++;

    const $notification = $('<div>').addClass('notify_box float ' + type);
    const $content = $('<p>').addClass('notify_content').html(message);

    $notification.append($content);
    $('body').prepend($notification);

    const topPosition = 40 + (notificationCount * 40);
    $notification.css('top', topPosition + 'px');

    setTimeout(function () {
        $notification.addClass('animate');
    }, 4500);

    setTimeout(function () {
        $notification.remove();
        notificationCount--;
    }, 5000);
}

function show_info_box(message, iserror) {
    const messageType = iserror ? 'error' : 'success';
    createNotification(message, messageType);
}

function show_modal(modal) {
    $('#' + modal).css('display', 'block');
}

$('.modal_container').on('click', '.close', function () {
    $('.modal_container').css('display', 'none');
});

$('.nav_toggle').click(function () {
    $('.items').toggleClass('visible');
});

function playSound(url) {
    const audio = new Audio(url);
    audio.play();
}
