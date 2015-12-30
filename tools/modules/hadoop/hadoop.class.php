<?php
/**
 * Created by PhpStorm.
 * User: hthou
 * Date: 2015/12/15
 * Time: 8:38
 */
$dir = substr(__FILE__,0,(0-strlen(basename(__FILE__))));
require_once $dir.'include/hcore.class.php';
include_once $dir.'include/hdtools.class.php';
class hadoop
{
    private $core;
    public $times;
    private $dir;
    public function __construct()
    {
        $this->core = new hcore();
        $this->times = 1;
        $this->dir = substr(__FILE__,0,(0-strlen(basename(__FILE__))));
    }
    private function init()
    {
        $host = HADOOP_HOST_NAME;
        $ip = $this->core->get_ip();
        $int_ip = ip2long($ip);
        $host_info = $this->core->get_server_by_hostname($host);
        if(isset($host_info['id']) && $host_info['ip']==$int_ip && $host_info['status']!=1)
        {
            return $host_info;
        }
        elseif(!isset($host_info['id']))
        {
            $ip_info = $this->core->get_server_by_ip($ip);
            if(!isset($ip_info['id']))
            {
                $conf_arr = array();
                $data = array(
                    'hostname'      =>  $host,
                    'ip'            =>  $int_ip,
                    'status'        =>  1,
                    'conf'          =>  json_encode($conf_arr),
                    'role'          =>  HADOOP_ROLE
                );
                $this->core->add($data);
                return false;
            }
            else
            {
                return false;
            }
        }
        else
        {
            return false;
        }
    }

    public function run()
    {
        $server_info = $this->init();
        if($server_info !== false)
        {
            $duty = $this->core->get_duty($server_info['id']);
            if($duty !== false)
            {
                $cmd = 'php '.$this->dir.'tools/index.php '.$duty['id'];
                exec($cmd);
                /*$classname = $duty['type'];
                if(file_exists($this->dir.$classname.'.php'))
                {
                    require_once $this->dir.$classname.'.php';
                    $obj = new $classname();
                    $func = $duty['duty_action'];
                    if(method_exists($obj,$func) === true)
                    {
                        $this->core->doing_duty($duty['id']);
                        $flag = $obj->$func($duty['params']);
                        $result = $obj->results();
                        $this->core->upd_duty_result($duty['id'],$flag,$result);
                    }
                    else
                    {
                        $this->core->can_not_do_duty($duty['id']);
                    }
                    
                }
                else
                {
                    $this->core->can_not_do_duty($duty['id']);
                }*/
            }
        }
    }
}