function poll() {
    $.ajax('/message/get/poll/true/wallid/' + wall_id + '/offset/' + latest_id, {
        dataType: "json",
        success: function (json) {
            if (!json.success) {
                console.log('Poll messages failed...');
            } else {
                // process messages
                var msg = json.messages;
                if (msg.length == 0) return;
                var msg_html = '';
                for (var i = msg.length-1; i >= 0; i--) {
                    if (msg[i].sex != 2) {
                        var msg_class = 'msg_left';
                    } else {
                        var msg_class = 'msg_right';
                    }
                    msg_html += '<div class="msg ' + msg_class + '"><div class="msg_headimg"><img src="' + msg[i].headimgurl + '" /><br />' + msg[i].nickname + '</div><div class="triangle"></div><div class="msg_wrapper"><div class="msg_content">' + msg[i].content + '</div></div></div>';
                }
                // update view
                latest_id = msg[0].id;
                var scrolldown = (document.body.scrollTop >= document.body.scrollHeight - window.innerHeight - 200);
                $('#container').append('<div class="msg_list">' + msg_html + '</div>');
                if (scrolldown) {
                    scrollToBottom();
                } else {
                    $('#newmsg_tip').fadeIn(200);
                }
            }
        },
        complete: function () {
            // continue to poll
            setTimeout(poll, 1000);
        }
    });
}

function scrollToBottom() {
    $('body').animate({'scrollTop': document.body.scrollHeight}, 1000);
    $('#newmsg_tip').fadeOut(200);
}

$(function () {
    latest_id = 0;
    $('#newmsg_tip').on('click', scrollToBottom);
    $('#big_close').on('click', function () {
        $('#big_container').fadeOut(200);
    });
    $('body').on('dblclick', '.msg_content', function () {
        $('#big_msg').html($(this).html()).css('background', $(this).css('background'));
        $('#big_container').fadeIn(200);
    });
    poll();
});