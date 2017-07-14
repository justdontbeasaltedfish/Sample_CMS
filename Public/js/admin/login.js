/**
 * @author justdontbeasaltedfish
 */
$("#login").on("click", function () {
    var userName = $("input[name='userName']").val();
    var password = $("input[name='password']").val();

    var url = "/Sample_CMS/admin.php?c=login&a=check";
    var data = {
        "userName": userName,
        "password": password
    };

    $.post(url, data, function (result) {
        if (result.status == 0) {
            return dialog.error(result.message);
        }
        if (result.status == 1) {
            return dialog.success(result.message, "/Sample_CMS/admin.php?c=index&a=index");
        }
    }, "JSON")
});