/**
 * @author justdontbeasaltedfish
 * 添加按钮操作
 */
$("#button-add").on("click", function () {
    var url = SCOPE.add_url;
    window.location.href = url;
});

/**
 * 提交form表单操作
 */
$("#singcms-button-submit").on("click", function () {
    var formData = $("#singcms-form").serializeArray();

    var data = {};
    $(formData).each(function (i) {
        data[this.name] = this.value;
    });
    var url = SCOPE.save_url;
    var jump_url = SCOPE.jump_url;

    $.post(url, data, function (result) {
        if (result.status == 0) {
            dialog.error(result.message);
        }
        if (result.status == 1) {
            dialog.success(result.message, jump_url);
        }
    }, "JSON");
});

/*
 编辑模型
 */
$(".singcms-table #singcms-edit").on("click", function () {
    var id = $(this).attr("attr-id");
    var url = SCOPE.edit_url + "&id=" + id;

    window.location.href = url;
});


/**
 * 删除操作JS
 */
$(".singcms-table #singcms-delete").on("click", function () {
    var id = $(this).attr("attr-id");
    var message = $(this).attr("attr-message");
    var url = SCOPE.set_status_url;

    var data = {
        "id": id,
        "status": -1
    };

    layer.open({
        type: 0,
        title: '是否提交？',
        btn: ['Yes', 'No'],
        icon: 3,
        closeBtn: 2,
        content: "是否确定" + message + '？',
        scrollbar: true,
        yes: function () {
            todelete(url, data);
        }

    });

});
function todelete(url, data) {
    $.post(url, data, function (result) {
            if (result.status == 1) {
                return dialog.success(result.message, '');
            } else {
                return dialog.error(result.message);
            }
        }
        , "JSON");
}

/**
 * 排序操作
 */
$('#button-listorder').on('click', function () {
    var formData = $('#singcms-listorder').serializeArray();

    var data = {};
    $(formData).each(function (i) {
        data[this.name] = this.value;
    });
    var url = SCOPE.listorder_url;

    $.post(url, data, function (result) {
        if (result.status == 1) {
            dialog.success(result.message, result.data.jump_url);
        } else if (result.status == 0) {
            dialog.error(result.message);
        }
    }, 'JSON');

});

/**
 * 修改状态
 */
$('.singcms-table #singcms-on-off').on('click', function () {

    var id = $(this).attr('attr-id');
    var status = $(this).attr("attr-status");
    var url = SCOPE.set_status_url;

    data = {};
    data['id'] = id;
    data['status'] = status;

    layer.open({
        type: 0,
        title: '是否提交？',
        btn: ['yes', 'no'],
        icon: 3,
        closeBtn: 2,
        content: "是否确定更改状态",
        scrollbar: true,
        yes: function () {
            // 执行相关跳转
            todelete(url, data);
        },

    });

});

/**
 * 推送JS相关
 */
$("#singcms-push").click(function () {
    var id = $("#select-push").val();
    if (id == 0) {
        return dialog.error("请选择推荐位");
    }
    push = {};
    postData = {};
    $("input[name='pushcheck']:checked").each(function (i) {
        push[i] = $(this).val();
    });

    postData['push'] = push;
    postData['position_id'] = id;
    //console.log(postData);return;
    var url = SCOPE.push_url;
    $.post(url, postData, function (result) {
        if (result.status == 1) {
            // TODO
            return dialog.success(result.message, result['data']['jump_url']);
        }
        if (result.status == 0) {
            // TODO
            return dialog.error(result.message);
        }
    }, "json");

});