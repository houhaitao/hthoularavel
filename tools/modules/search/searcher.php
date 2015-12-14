<?php
require './include/common.inc.php';
require_once APP_ROOT . 'include/search.class.php';
$step = 5000;
$sql = "select * from " . DB_PRE . "search order by id limit ?,?";
$search_db = new search();
$total = 0;
$stime = time();
for($i=0;1==1;$i+=$step)
{
    $db->prepare($sql);
    $db->set_int($i);
    $db->set_int($step);
    $res = $db->execute();
    $size = sizeof($res);
    echo "start:".$i."\n";
    foreach($res as $v)
    {
        if($v['status']==99)
        {
            $data = $v;
            unset($data['fid']);
            unset($data['lasttime']);
            unset($data['status']);
            $search_db->setDoc($data);
        }
        else
        {
            $search_db->delDoc($v['id']);
        }
        $total++;
    }
    if(sizeof($res) != $step)
    {
        break;
    }
}
$etime = time();
$diff = $etime - $stime;
echo "total: ".$total." documents\n";
echo "total: ".$diff." seconds\n";

