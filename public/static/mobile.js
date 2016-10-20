$(function () {
    $('#btn_send').click(function () {
        if ($(this).hasClass('disabled')) return false;
        var content = $.trim($('#content').val());
        if (content.length > 200) {
            alert('内容有点长，请缩短后再发送~');
        } else {
            $(this).addClass('disabled');
            $.post('/message/post',
                {'content': content},
                function (resp, status) {
                    if (status == 'success') {
                        if (resp.success) {
                            location.reload();
                        } else {
                            if (resp.error) alert(resp.error);
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