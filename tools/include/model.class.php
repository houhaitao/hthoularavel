<?php
abstract class model_tool_base
{
    protected $table;
    protected $db;
    protected $pri_key;


    abstract protected function get_table();
    abstract protected function get_insert_id();
    abstract protected function set_pri_key();

    public function __construct($db_conf)
    {
        $this->db = new db($db_conf);
        $this->get_table();
        $this->set_pri_key();
    }
    
    public function add($data)
    {
        $res = $this->db->insert($this->table,$data);
        if($res === false)
        {
            return false;
        }
        return $this->get_insert_id();
    }
    
    public function edit($id,$data)
    {
        $res = $this->db->simple_update($this->table, $data, array($this->pri_key => $id));
        return $res;
    }
    
    public function delete($id)
    {
        $res = $this->db->delete($this->table, $id,  $this->pri_key);
        return $res;
    }
    
    public function get_one($id)
    {
        $res = $this->db->get_one($this->table,$id, $this->pri_key);
        return $res;
    }
    
    public function get_one_normal($id)
    {
        $res = $this->db->get_one_normal($this->table,$id, $this->pri_key);
        return $res;
    }
    
    
}

