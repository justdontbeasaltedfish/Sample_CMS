<?php

//use

/**
 * 公用的方法
 */

function sendEmail($user_id, $user_email, $user_activation_hash)
{
    $mail = new PHPMailer;
    $mail->CharSet = 'UTF-8';
    $mail->IsSMTP();
    $mail->SMTPDebug = 0;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = 'ssl';

    $mail->Host = 'smtp.qq.com';
    $mail->Username = '2094947437@qq.com';
    $mail->Password = 'sawwgaffivkvegdh';
    $mail->Port = 465;

    $mail->From = '2094947437@qq.com';
    $mail->FromName = 'Sample_CMS';
    $mail->AddAddress($user_email);
    $mail->Subject = '激活 Sample_CMS 账号';
    $mail->Body = "请点击此链接来激活你的账号：http://39.108.130.201/Sample_CMS/index.php?m=home&c=register&a=activateAccount&user_id=$user_id&user_activation_verification_code=$user_activation_hash";

    $wasSendingSuccessful = $mail->Send();

    if ($wasSendingSuccessful) {
        return true;
    } else {
//        $this->error = $mail->ErrorInfo;
        return false;
    }
}

function show($status, $message, $data = array())
{
    $result = array(
        'status' => $status,
        'message' => $message,
        'data' => $data,
    );

    exit(json_encode($result));
}

function getMd5Password($password)
{
    return md5($password . C('MD5_PRE'));
}

function getMenuType($type)
{
    return $type == 1 ? '后台菜单' : '前端导航';
}

function status($status)
{
    if ($status == 0) {
        $statusString = '关闭';
    } elseif ($status == 1) {
        $statusString = '正常';
    } elseif ($status == -1) {
        $statusString = '删除';
    }
    return $statusString;
}

function getAdminMenuUrl($nav)
{
    $url = '/Sample_CMS/admin.php?c=' . $nav['c'] . '&a=' . $nav['f'];
    return $url;
}

function getActive($navControllerName)
{
    $controllerName = strtolower(CONTROLLER_NAME);
    if (strtolower($navControllerName) == $controllerName) {
        return 'class="active"';
    }
    return '';
}

function showKind($status, $data)
{
    header('Content-type:application/json;charset=UTF-8');
    if ($status == 0) {
        exit(json_encode(array('error' => 0, 'url' => $data)));
    }
    exit(json_encode(array('error' => 1, 'message' => '上传失败')));
}

function getLoginAdminName()
{
    return $_SESSION['adminUser']['username'] ? $_SESSION['adminUser']['username'] : '';
}

function getLoginUserName()
{
    return $_SESSION['userInfo']['user_name'] ? $_SESSION['userInfo']['user_name'] : '';
}

function getCatName($navs, $id)
{
    foreach ($navs as $nav) {
        $navList[$nav['menu_id']] = $nav['name'];
    }
    return isset($navList[$id]) ? $navList[$id] : '';
}

function getCopyFromById($id)
{
    $copyFrom = C("COPY_FROM");
    return $copyFrom[$id] ? $copyFrom[$id] : '';
}

function isThumb($thumb)
{
    if ($thumb) {
        return '<span style="color:red">有</span>';
    }
    return '无';
}

/**
 * +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 * +----------------------------------------------------------
 * @static
 * @access public
 * +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * +----------------------------------------------------------
 * @return string
+----------------------------------------------------------
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    $len = substr($str);
    if (function_exists("mb_substr")) {
        if ($suffix)
            return mb_substr($str, $start, $length, $charset) . "...";
        else
            return mb_substr($str, $start, $length, $charset);
    } elseif (function_exists('iconv_substr')) {
        if ($suffix && $len > $length)
            return iconv_substr($str, $start, $length, $charset) . "...";
        else
            return iconv_substr($str, $start, $length, $charset);
    }
    $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
    $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
    $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
    $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("", array_slice($match[0], $start, $length));
    if ($suffix) return $slice . "…";
    return $slice;
}





