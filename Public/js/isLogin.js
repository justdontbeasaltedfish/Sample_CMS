/**
 * Created by wangjianrui on 2017/7/16.
 */

var url = '/Sample_CMS/index.php?m=home&c=login&a=isLogin';

$.post(url, {}, function (result) {
    if (result.status == 1) {
        $('#divLogin').css("display", "none");
        $('#divLogout').css("display", "inline");
        $('#userName').append(result.data.user_name);
        $('#hidden_user_id').attr("value", result.data.user_id);
    } else if (result.status == 0) {
        $('#divLogin').css("display", "inline");
        $('#divLogout').css("display", "none");
    }
}, 'JSON');