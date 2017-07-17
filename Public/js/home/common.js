/**
 * Created by wangjianrui on 2017/7/15.
 */

/**
 * 注册功能
 */
$('#buttonRegister').on('click', function () {
    var formData = $('#formRegister').serializeArray();

    var data = {};
    $(formData).each(function (i) {
        data[this.name] = this.value;
    });

    var url = '/Sample_CMS/index.php?m=home&c=register&a=register';

    $.post(url, data, function (result) {
        if (result.status == 1) {
            dialog.success(result.message, '');
        } else if (result.status == 0) {
            dialog.error(result.message);
        }
    }, 'JSON');
});

/**
 * 登录功能
 */
$('#buttonLogin').on('click', function () {
    var formData = $('#formLogin').serializeArray();

    var data = {};
    $(formData).each(function () {
        data[this.name] = this.value;
    });

    var url = '/Sample_CMS/index.php?m=home&c=login&a=login';

    $.post(url, data, function (result) {
        if (result.status == 0) {
            dialog.error(result.message);
        } else if (result.status == 1) {
            dialog.success(result.message, result.data.url);
        }
    }, 'JSON');
});

/**
 * 评论功能
 */
$('#buttonComment').on('click', function () {
    var formData = $('#formComment').serializeArray();

    var data = {};
    $(formData).each(function () {
        data[this.name] = this.value;
    });

    if (!data.user_id) {
        dialog.error('请登陆！');
        return;
    }

    var url = '/Sample_CMS/index.php?m=home&c=comment&a=index';

    $.post(url, data, function (result) {
        if (result.status == 0) {
            dialog.error(result.message);
        } else if (result.status == 1) {
            refresh(true);
        }
    }, 'JSON')
});

function refresh(isInsert) {
    var data = {'news_id': $('#hidden_news_id').val()};

    url = '/Sample_CMS/index.php?m=home&c=comment&a=refresh';

    var $comment = $('#ulComment');

    $.post(url, data, function (result) {
        if (result.status == 1) {
            $('#textArea_comment').val('');
            var count = result.data.length;
            if (isInsert) {
                var time = getLocalTime(result.data[0]['create_time']);
                $comment.prepend('<li class="list-group-item">' +
                    '<h5 class="list-group-item-heading" style="color: #0000bf">' + result.data[0]['user_name'] + '   <small>' + time + '</small> </h5>' +
                    '<p class="list-group-item-text">' + result.data[0]['comment_text'] + '</p>' +
                    '<span style="position: relative; top: -45px; left: 790px" class="label label-info">' + count + '楼</span>' +
                    '<div style="position: relative; top: 10px; left: 700px" id="div_comment_like_count" attr_comment_id="' + result.data[0]['comment_id'] + '"> ' +
                    '<span style="color: pink" class="glyphicon glyphicon-thumbs-up" aria-hidden="true"> ' +
                    '<span style="color: pink" class="badge" id="span_comment_like_count">' + result.data[0]['comment_like_count'] + '</span></span> ' +
                    '</div> ' +
                    '</li>');
            } else {
                $(result.data).each(function (i) {
                    var $floor = count - i;
                    var time = getLocalTime(this.create_time);
                    $comment.append('<li class="list-group-item"> ' +
                        '<h5 class="list-group-item-heading" style="color: #0000bf">' + this.user_name + '   <small>' + time + '</small> </h5> ' +
                        '<p class="list-group-item-text">' + this.comment_text + '</p> ' +
                        '<span style="position: relative; top: -45px; left: 790px" class="label label-info">' + $floor + '楼</span> ' +
                        '<div style="position: relative; top: 10px; left: 700px" id="div_comment_like_count" attr_comment_id="' + this.comment_id + '"> ' +
                        '<span style="color: pink" class="glyphicon glyphicon-thumbs-up" aria-hidden="true"> ' +
                        '<span style="color: pink" class="badge" id="span_comment_like_count">' + this.comment_like_count + '</span></span> ' +
                        '</div> ' +
                        '</li>');
                })
            }
        } else if (result.status == 0) {
            dialog.error('刷新失败！');
        }
    }, 'JSON');
}

$('#ulComment').on('click', '#div_comment_like_count', function () {
    var comment_id = $(this).attr('attr_comment_id');
    var comment_like_count = $(this).find('span').eq(1).text();

    var data = {
        'comment_id': comment_id,
        'comment_like_count': comment_like_count
    };

    url = '/Sample_CMS/index.php?m=home&c=comment&a=updateCommentLikeCount';

    var $comment_like_count = $(this).find('span').eq(1);
    $.post(url, data, function (result) {
        if (result.status == 1) {
            console.log(comment_like_count);
            $comment_like_count.text(parseInt(comment_like_count) + 1);
        } else if (result.status == 0) {
            console.log(result.message);
        }
    }, 'JSON');
});

function getLocalTime(nS) {
    return new Date(parseInt(nS) * 1000).toLocaleString().replace(/:\d{1,2}$/, ' ');
}