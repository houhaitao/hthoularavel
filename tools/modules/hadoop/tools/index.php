<?php
/**
 * Created by PhpStorm.
 * User: hthou
 * Date: 2015/12/16
 * Time: 10:06
 */
define('HDTOOLS_ROOT', substr(__FILE__,0,0-strlen(basename(__FILE__))));
define('BASE_ROOT', substr(HDTOOLS_ROOT,0,0-strlen('modules/hadoop/tools/')));
require BASE_ROOT . 'include/config.inc.php';
include BASE_ROOT . 'include/db.class.php';
include BASE_ROOT . 'include/model.class.php';
$duty_detail_id = isset($argv[1]) ? intval($argv['1']) : 0;
if(empty($duty_detail_id))
{
    exit;
}
$db_conf = array(
    'host'	=>	TOOLS_DB_HOST,
    'username'	=>	TOOLS_DB_USER,
    'passwd'	=>	TOOLS_DB_PASS,
    'dbname'	=>	TOOLS_DB_NAME,
    'port'	=>	TOOLS_DB_PORT
);
$db = new db($db_conf);
include_once BASE_ROOT.'modules/hadoop/include/hdtools.class.php';
$db->prepare('select d.*,m.id as mid,m.type,m.duty_action,m.duty_detail from bg_duty_detail as d LEFT JOIN bg_duty as m on d.duty_id=m.id where d.id=?');
$db->set_int($duty_detail_id);
$info = $db->execute(true);
if(isset($info['id']) && isset($info['mid']))
{
    $class = $info['type'];
    $func = $info['duty_action'];

    $data = json_decode($info['duty_detail']);
    $file = HDTOOLS_ROOT.$class.'.php';
    if(file_exists($file))
    {
        require_once $file;
        $obj = new $class;
        if(method_exists($obj,$func))
        {
            $db->prepare('update bg_duty_detail set status=2 where id=?');
            $db->set_int($duty_detail_id);
            $db->execute();
            $flag = $class->$func($data);
            $result = $class->results();
            $flag = $flag === true ? 99 : 3;
            $db->prepare('update bg_duty_detail set status=? and detail=? where id=?');
            $db->set_int($flag);
            $db->set_string(json_encode($result));
            $db->set_int($duty_detail_id);
            $db->execute();
        }
        else
        {
            $db->prepare('update bg_duty_detail set status=0 where id=?');
            $db->set_int($duty_detail_id);
            $db->execute();
        }
    }
    else
    {
        $db->prepare('update bg_duty_detail set status=0 where id=?');
        $db->set_int($duty_detail_id);
        $db->execute();
    }

}