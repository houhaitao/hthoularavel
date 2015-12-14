<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
$dir = substr(__FILE__,0,(0-strlen(basename(__FILE__))));
require_once $dir.'include/search.class.php';
class search_tool extends model_tool_base
{
    public $times;
    function __construct() 
    {
        $db_conf = array(
            'host'	=>	TOOLS_DB_HOST,
            'username'	=>	TOOLS_DB_USER,
            'passwd'	=>	TOOLS_DB_PASS,
            'dbname'	=>	TOOLS_DB_NAME,
            'port'	=>	TOOLS_DB_PORT
        );
        parent::__construct($db_conf);
        $this->times = 20;
    }
    protected function get_insert_id() {
        return $this->db->uuid();
    }

    protected function get_table() {
        $this->table = 'edufe_search';
    }

    protected function set_pri_key() {
        $this->pri_key = 'id';
    }
    
    private function make_index()
    {
        $step = 5000;
        $sql = "select * from edufe_search order by id limit ?,?";
        $search_db = new search();
        $total = 0;
        $stime = time();
        for($i=0;1==1;$i+=$step)
        {
            $this->db->prepare($sql);
            $this->db->set_int($i);
            $this->db->set_int($step);
            $res = $this->db->execute();
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
    }
    

    public function run()
    {
        
        //$this->make_index();
    }

}