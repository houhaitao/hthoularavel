<?php
/**
 * Created by PhpStorm.
 * User: hthou
 * Date: 2015/12/15
 * Time: 8:39
 */
class hcore extends model_tool_base
{
    private $duty_table;
    private $duty_detail_table;
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

    public function get_server_by_hostname($hostname)
    {
        $this->db->prepare('select * from ' .$this->table.' where hostname=?');
        $this->db->set_string($hostname);
        $res = $this->db->execute(true);
        return $res;
    }
    public function get_server_by_ip($ip)
    {
        $this->db->prepare('select * from ' .$this->table.' where ip=?');
        $this->db->set_int(ip2long($ip));
        $res = $this->db->execute(true);
        return $res;
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

    public function doing_duty($id)
    {
        $this->db->prepare('update ' . $this->duty_detail_table . ' set status=2 where id=?');
        $this->db->set_int($id);
        $this->db->execute();
        return true;
    }

    public function can_not_do_duty($id)
    {
        $this->db->prepare('update ' . $this->duty_detail_table . ' set status=0 where id=?');
        $this->db->set_int($id);
        $this->db->execute();
        return true;
    }

    public function upd_duty_result($id,$flag,$result)
    {
        $flag = $flag === true ? 99 : 3;
        $this->db->prepare('update ' . $this->duty_detail_table . ' set status=? and detail=? where id=?');
        $this->db->set_int($flag);
        $this->db->set_string(json_encode($result));
        $this->db->set_int($id);
        $this->db->execute();
        return true;
    }



    public function get_duty($server_id)
    {
        $this->db->prepare('select * from ' .$this->duty_detail_table.' where server_id=? and status=? order by id asc limit 1');
        $this->db->set_int($server_id);
        $this->db->set_int(1);
        $detail_res = $this->db->execute(true);
        $time = time();
        if(isset($detail_res['id']))
        {
            $this->db->prepare('select * from '.$this->duty_table.' where id=?');
            $this->db->set_int($detail_res['duty_id']);
            $res = $this->db->execute(true);
            if(isset($res['id']))
            {
                $result = $detail_res;
                $result['type'] = $res['type'];
                $result['duty_time'] = $res['duty_time'];
                $result['duty_time_frequency'] = $res['duty_time_frequency'];
                $result['duty_action'] = $res['duty_action'];
                if($res['duty_time']=='0' && $res['duty_time']==0 && $detail_res['lasttime']>0) //如果为已经执行完毕的一次性任务
                {
                    return false;
                }
                if($detail_res['lasttime'] > 0 && ($res['duty_time_frequency']+$detail_res['lasttime'])>$time) //如果周期性任务还没到执行时间
                {
                    return false;
                }
                if($detail_res['lasttime']>0 && $res['duty_time']>0 && $res['duty_time_frequency']==0) //如果单纯的定期任务，并且已经执行完毕
                {
                    return false;
                }
                if($detail_res['lasttime']==0 && $time < $res['duty_time'] && $res['duty_time'] >0) //如果定期任务，还未到执行时间
                {
                    return false;
                }
                return $result;
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
}