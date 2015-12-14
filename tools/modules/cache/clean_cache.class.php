<?php
$dir = substr(__FILE__,0,(0-strlen(basename(__FILE__))));
require_once $dir.'include/worker.class.php';
require_once $dir.'include/clean.class.php';
class clean_cache
{
    private $worker;
    private $clean;
    public $times;
    function __construct() {
        $this->worker = new worker();
        $this->clean = new clean();
        $this->times = 1;
    }
    
    private function init()
    {
        $sites = explode(',', TOOLS_SITEID);
        $ip = $this->worker->get_ip();
        if($ip === false)
        {
            return false;
        }
        $dirs = array();
        if(is_array($sites))
        {
            foreach ($sites as $s)
            {
                $dir = $this->worker->get_path($ip, $s);
                if($dir === false)
                {
                    continue;
                }
                else
                {
                    $dirs[] = $dir;
                }
            }
        }
        if(sizeof($dirs) === 0)
        {
            return false;
        }
        else
        {
            return $dirs;
        }
    }
    
    function make_clean($dirs)
    {
        foreach ($dirs as $d)
        {
            $id = $this->clean->get_wait_id_to_clean($d[0]);
            if($id === false)
            {
                continue;
            }
            $this->clean->edit($id, array('do_status'=>STATUS_RECYCLE,'do_start_time'=>time()));
            sleep(5);
            $this->clean->clean_dir($d[1]);
            $this->clean->edit($id, array('do_status'=>STATUS_NORMAL,'do_end_time'=>time()));
        }
    }

    public function run()
    {
        $dirs = $this->init();
        if($dirs === false)
        {
            return false;
        }
        $this->make_clean($dirs);
        return true;
    }
}

