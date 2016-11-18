$(function () {
    if (localStorage.saved_content) {
        $('#content').val(localStorage.saved_content);
    }

    // init jsapi
    wx.config({
        debug: false,
        appId: jsapi_sign.appId,
        timestamp: jsapi_sign.timestamp,
        nonceStr: jsapi_sign.nonceStr,
        signature: jsapi_sign.signature,
        jsApiList: [
            'chooseImage',
            'previewImage',
            'uploadImage'
        ]
    });

    var post_msg = function (content, type) {
        if (!type) type = 0;
        // save content
        localStorage.saved_content = $.trim($('#content').val());

        $('#btn_send, #btn_sendpic').addClass('disabled');
        $.post('/message/post' + (wall_id ? '/wallid/'+wall_id : ''),
            {'content': content, 'type': type},
            function (resp, status) {
                if (status == 'success') {
                    if (resp.success) {
                        if (type == 0) {
                            localStorage.removeItem('saved_content');
                            $('#content').val('');
                        }
                        location.search = new Date().getTime();
                        //location.reload(true);
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
                $('#btn_send, #btn_sendpic').removeClass('disabled');
            }
        );
    };

    $('#btn_send').click(function () {
        if ($(this).hasClass('disabled')) return false;
        var content = $.trim($('#content').val());
        if (!content) return;
        // post
        if (content.length > 200) {
            alert('内容有点长，请缩短后再发送');
        } else {
            post_msg(content);
        }
    });

    $('#btn_sendpic').click(function () {
        if ($(this).hasClass('disabled')) return false;
        wx.chooseImage({
            count: 1,
            sizeType: ['compressed'],
            sourceType: ['album', 'camera'],
            success: function (res) {
                wx.uploadImage({
                    localId: res.localIds[0],
                    isShowProgressTips: 1,
                    success: function (res) {
                        post_msg(res.serverId, 1);
                    }
                });
            }
        });
    });

    $('#my_messages').find('img').click(function () {
        wx.previewImage({
            urls: [location.origin + $(this).attr('src')]
        });
    })
});