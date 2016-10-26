$(function () {
    var rolling = 0;
    $('#start_stop').click(function () {
        if (!rolling) {
            var draw_num = Number($('#draw_num').val());
            if (!draw_num) {
                alert("请输入正确的抽取人数!");
                return;
            }
            // start rolling
            rolling = setInterval(function () {
                var results = [];
                for (var i=0; i<draw_num; i++) {
                    var rand = Math.floor(Math.random() * user_list.length);
                    while (results.indexOf(rand) >= 0 || $.trim(user_list[rand]) == '')
                        rand = Math.floor(Math.random() * user_list.length);
                    results.push(rand);
                    $('.user').eq(i).html(user_list[rand]);
                }
            }, 50);
            // update UI
            $(this).html('停止抽奖');
        } else {
            // stop rolling
            clearInterval(rolling);
            rolling = false;
            $(this).html('开始抽奖');
        }
    });
});