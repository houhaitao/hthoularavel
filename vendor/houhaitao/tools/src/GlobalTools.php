<?php
/**
 * Created by PhpStorm.
 * User: 无聊小刚
 * Date: 2015/12/9
 * Time: 16:23
 */
namespace Tools;

class GlobalTools
{
    /**
     * 客户端ip
     *
     * @return string
     */
    public static function ip()
    {
        if(getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), 'unknown'))
        {
            $ip = getenv('HTTP_CLIENT_IP');
        }
        elseif(getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), 'unknown'))
        {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        }
        elseif(getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), 'unknown'))
        {
            $ip = getenv('REMOTE_ADDR');
        }
        elseif(isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], 'unknown'))
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return preg_match("/[\d\.]{7,15}/", $ip, $matches) ? $matches[0] : 'unknown';
    }

    /**
     * 文字标题缩略显示
     * @author zham, 20080105
     *
     * @param string $string
     * @param integer $limit
     * @param string $ext_str
     * @return string
     */
    public static function str_cut($string, $limit=10, $ext_str="..."){
        $string = trim($string);
        if(get_real_len($string, "UTF-8") > $limit){
            return get_real_sub($string, $limit, "UTF-8").$ext_str;
        }else{
            return $string;
        }
    }

    public static function new_htmlspecialchars($string)
    {
        return is_array($string) ? array_map('new_htmlspecialchars', $string) : htmlspecialchars($string, ENT_QUOTES);
    }

    public static function text2html($string,$cutlen=false)
    {
        if(intval($cutlen) > 0)
        {
            $string = str_cut($string, $cutlen);
        }
        return nl2br(str_replace(' ','&nbsp;',self::new_htmlspecialchars($string)));
    }

    public static function filter_xss($string, $allowedtags = '', $disabledattributes = array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavaible', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragdrop', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterupdate', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmoveout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload'))
    {
        if(is_array($string))
        {
            foreach($string as $key => $val) $string[$key] = filter_xss($val, ALLOWED_HTMLTAGS);
        }
        else
        {
            $subject = preg_replace_callback('/<(.*?)>/i', 'prc_callback', strip_tags($string, $allowedtags));
            $string = preg_replace('/\s(' . implode('|', $disabledattributes) . ').*?([\s\>])/', '\\2', $subject);
        }
        return $string;
    }



    /**
     * 二维数组输出csv
     * @author menghao
     * @param type $data
     * @param type $filename
     * @return type
     */
    public static function make_csv($data, $filename)
    {
        header('Content-Type: application/vnd.ms-excel');
        if(strpos(strtolower($_SERVER['HTTP_USER_AGENT']), "msie") !== false)
        {
            $filename = urlencode($filename);
            $filename = str_replace("+", "%20", $filename);
            header('Content-Disposition: attachment;filename="'. $filename . '.csv"');
        }
        else
        {
            header("Content-disposition: attachment; filename=\"{$filename}.csv\"");
        }

        if(!is_array($data))
        {
            return false;
        }
        foreach($data as $r)
        {
            if(!is_array($r))
            {
                continue;
            }
            $line = implodeids($r);
            $line = mb_convert_encoding($line, 'gb2312','utf-8');
            echo $line . "\n";
        }
        exit;
    }

    public static function genRandomString($len)
    {
        $chars = array(
            "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k",
            "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v",
            "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G",
            "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R",
            "S", "T", "U", "V", "W", "X", "Y", "Z", "0", "1", "2",
            "3", "4", "5", "6", "7", "8", "9");
        $charsLen = count($chars) - 1;
        shuffle($chars);
        $output = "";
        for ($i=0; $i<$len; $i++)
        {
            $output .= $chars[mt_rand(0, $charsLen)];
        }
        return $output;
    }

    public static function forward_encode($url)
    {
        return urlencode(base64_encode($url));
    }

    public static function forward_decode($url)
    {
        return base64_decode(urldecode($url));
    }
    /**
     * 生成当前页面的后退地址参数
     *
     * @return unknown
     */
    public static function make_forward($url='')
    {
        $url = !empty($url) ? $url : $_SERVER['REQUEST_URI'];
        return self::forward_encode($url);
    }

    /**
     * 获得后退地址参数
     *
     * @param unknown_type $default_url
     * @return unknown
     */
    public static function get_forward($default_url='')
    {
        $forward = isset($_GET['forward']) ? $_GET['forward'] : (isset($_POST['forward']) ? $_POST['forward']:'');
        $forward = isset($forward) ? self::forward_decode($forward) : '';
        $forward = empty($forward) ? $default_url : $forward;
        return $forward;
    }

}