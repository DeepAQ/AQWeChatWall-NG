$(function () {
    if (localStorage.saved_content) {
        $('#content').val(localStorage.saved_content);
    }

    $('#btn_send').click(function () {
        if ($(this).hasClass('disabled')) return false;
        var content = $.trim($('#content').val());
        // save content
        localStorage.saved_content = content;
        // post
        if (content.length > 200) {
            alert('内容有点长，请缩短后再发送');
        } else {
            $(this).addClass('disabled');
            $.post('/message/post',
                {'content': content},
                function (resp, status) {
                    if (status == 'success') {
                        if (resp.success) {
                            localStorage.saved_content = null;
                            location.reload();
                        } else {
                            if (resp.relogin) {
                                location = '/wechat/login';
                            } else if (resp.error) {
                                alert(resp.error);
                            }
                        }
                    } else {
                        alert('微信墙繁忙, 请稍后再尝试发送');
                    }
                    $('#btn_send').removeClass('disabled');
                }
            );
        }
    });
});