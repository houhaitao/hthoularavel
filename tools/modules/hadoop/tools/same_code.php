<?php

/**
 * Created by PhpStorm.
 * User: hthou
 * Date: 2015/12/15
 * Time: 11:11
 */
class same_code extends model_tool_base implements hdtools
{
    protected $result=array();
    private $duty_table;
    private $duty_detail_table;
    private $base_table;
    function __construct() {
        $db_conf = array(
            'host'	=>	TOOLS_DB_HOST,
            'username'	=>	TOOLS_DB_USER,
            'passwd'	=>	TOOLS_DB_PASS,
            'dbname'	=>	TOOLS_DB_NAME,
            'port'	=>	TOOLS_DB_PORT
        );
        $this->duty_table = 'bg_duty';
        $this->duty_detail_table = 'bg_duty_detail';
        $this->base_table = 'bg_base_info';
        parent::__construct($db_conf);
    }
    protected function get_insert_id() {
        return $this->db->get_insert_id();
    }

    protected function get_table() {
        $this->table = 'bg_server';
    }

    protected function set_pri_key() {
        $this->pri_key = 'id';
    }

    public function set_result($result)
    {
        $this->result = array_merge($this->result,$result);
    }

    public function results()
    {
        return $this->result;
    }

    protected function get_base_path()
    {
        $this->db->prepare('select * from '.$this->base_table.' where code=?');
        $this->db->set_string('SERVER_HTTP_HOST');
        $res = $this->db->execute(true);
        $host = isset($res['datavalue']) ? $res['datavalue'] : false;;
        if($host === false)
        {
            return false;
        }
        else
        {
            return $host . 'tools/hdcode.zip';
        }
    }

    public function do_same_code($data)
    {
        $code_url = $this->get_base_path();
        if($code_url === false)
        {
            $this->set_result(array('message'=>'服务端地址未设置'));
            return false;
        }
        exec('rm -f '.HDTOOLS_ROOT.'hdcode.zip');
        $target_dir = HDTOOLS_ROOT;
        $cmd = 'wget '.$code_url.' --directory-prefix='.$target_dir;
        exec($cmd);
        if(file_exists($target_dir.'hdcode.zip')===false)
        {
            $this->set_result(array('message'=>'服务端代码未获取'));
            return false;
        }
        $cmd = "yum install -y unzip";
        exec($cmd);
        $cmd=  "unzip ".HDTOOLS_ROOT.'hdcode.zip -d '.$target_dir;
        exec($cmd);
        exec('rm -f '.HDTOOLS_ROOT.'hdcode.zip');
        return true;
    }
}