<?php
define('TOOLS_ROOT', substr(__FILE__,0,0-strlen(basename(__FILE__))));
require TOOLS_ROOT . 'include/config.inc.php';
include TOOLS_ROOT . 'include/db.class.php';
include TOOLS_ROOT . 'include/model.class.php';
$module_dir = TOOLS_ROOT .'modules/';

while(1==1)
{
	$counts = file_exists(TOOLS_ROOT.'count.log') ? unserialize(file_get_contents(TOOLS_ROOT.'count.log')) : array();;
    $fp = fopen(TOOLS_ROOT.'count.log', 'w');
    $modules = scandir($module_dir);
    if(is_array($modules))
    {
        foreach($modules as $m)
        {
            if($m!='.' && $m!='..' && is_dir($module_dir.$m.'/'))
            {
                $files = scandir($module_dir.$m.'/');
		
                if(is_array($files))
                {
                    foreach ($files as $f)
                    {
                        if(substr($f,-10)=='.class.php')
                        {
                            require_once $module_dir . $m . '/' .$f;
                            $class = substr($f,0,-10);
                            $obj = new $class();
                            if(isset($obj->times))
                            {
                                if(!isset($counts[$class]))
                                {
                                    $counts[$class] = 0;
                                }
                                $cc = $counts[$class];
				$counts[$class] += 1;
                            }
                            else
                            {
                                $cc = 0;
                            }
                            if($cc>=$obj->times)
                            {
				echo $class.'---'.$cc."\n";
                                $obj->run();
                                $counts[$class] = 0;
                                echo "class:".$class." run complete\n";
                                sleep(2);
                            }
                        }
                    }
                }
            }
        }
    }
    $str = serialize($counts);
    fwrite($fp, $str);
    fclose($fp);
    sleep(5);
}
?>
