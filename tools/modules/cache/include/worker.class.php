<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class worker extends model_tool_base
{
    function __construct() {
        $db_conf = array(
            'host'	=>	TOOLS_DB_HOST,
            'username'	=>	TOOLS_DB_USER,
            'passwd'	=>	TOOLS_DB_PASS,
            'dbname'	=>	TOOLS_DB_NAME,
            'port'	=>	TOOLS_DB_PORT
        );
        parent::__construct($db_conf);
    }
    protected function get_insert_id() {
        return $this->db->get_insert_id();
    }

    protected function get_table() {
        $this->table = 'edufe_cache_worker';
    }

    protected function set_pri_key() {
        $this->pri_key = 'id';
    }
    
    public function get_path($ip,$siteid)
    {
        $this->db->prepare('select * from ' . $this->table . ' where ip=? and siteid=?');
        $this->db->set_string($ip);
        $this->db->set_string($siteid);
        $res = $this->db->execute(true);
        if(!isset($res['id']))
        {
            $data = array(
                'ip'    =>  $ip,
                'siteid'    =>  $siteid,
                'cachedir'  =>  '',
                'regtime'   =>  time()
            );
            $this->add($data);
            return false;
        }
        else
        {
            if(empty($res['cachedir']) || !is_dir($res['cachedir']))
            {
                return false;
            }
            else
            {
                return array($res['id'], $res['cachedir']);
            }
        }
    }
    
    public function get_ip()
    {
        $res = array();
        exec('ifconfig -a',$res);  
        $target_flag = false;
        $ip = false;
        foreach($res as $r)
        {
            $tmp = array();
            if($target_flag === false)
            {
                $patten = "/^([\w]+)[\s]+[\S]+.*$/";
                preg_match_all($patten,$r,$tmp);
                if(isset($tmp[1][0]) && substr($tmp[1][0],0,3)=='eth')
                {
                    $target_flag = true;
                }
            }
            else
            {
                $patten = "/^[\s]+inet[\s]+addr\:([0-9\.]+).*$/";
                preg_match_all($patten,$r,$tmp);
                $ip = $tmp[1][0];
                break;
            }
        }
        return $ip;
    }
}
