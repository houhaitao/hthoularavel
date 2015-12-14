<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class clean extends model_tool_base
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
        $this->table = 'edufe_cache_clean';
    }

    protected function set_pri_key() {
        $this->pri_key = 'id';
    }
    
    public function get_wait_id_to_clean($workerid)
    {
        $this->db->prepare('select id from ' . $this->table . ' where workerid=? and do_status=?');
        $this->db->set_int($workerid);
        $this->db->set_int(STATUS_WAIT);
        $res = $this->db->execute(true);
        if(!isset($res['id']))
        {
            return false;
        }
        return $res['id'];
    }
    
    public function clean_dir($dir)
    {
        if(!is_dir($dir))
        {
            return false;
        }
        $dir .= substr($dir, -1) == DIRECTORY_SEPARATOR ? '' : DIRECTORY_SEPARATOR;
        $files = scandir($dir);
        
        if(is_array($files))
        {
            foreach($files as $f)
            {
                if($f=='.' || $f=='..')
                {
                    ;
                }
                else
                {
                    if(is_dir($dir.$f.'/'))
                    {
                        $this->clean_dir($dir.$f.'/');
                        rmdir($dir.$f.'/');
                    }
                    else
                    {
                        unlink($dir.$f);
                    }
                }
            }
        }
    }
    
}
