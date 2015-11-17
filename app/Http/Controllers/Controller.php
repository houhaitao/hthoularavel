<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

abstract class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
    protected $hht_response;
    protected $flag;
    protected $url;

    function hht_make_search_url($data,$encode='')
    {
        $url = $this->url;
        $encode = explode(',',$encode);
        unset($data['_token']);
        unset($data['edufepm']);
        $url_data = array();
        if(sizeof($data) > 0)
        {
            $url .= '?';
            foreach($data as $k=>$v)
            {
                $value = in_array($k,$encode) ? urlencode($v) : $v;
                $url_data[] = $k.'='.$value;
            }
            $url .= implode('&',$url_data);
        }
        return $url;
    }

    function hht_alert($id,$type,$text)
    {
        $this->flag=false;
        $text = str_replace(array("'",'"'),array("\\'",'\\\"'),$text);
        $this->hht_response .= "my_alert(\\'{$id}\\',\\'{$type}\\',\\'{$text}\\');";
    }

    function hht_alert_ok($type,$text)
    {
        $this->flag=false;
        $text = str_replace(array("'",'"'),array("\\'",'\\\"'),$text);
        $this->hht_response .= "div_su_alert(\\'{$type}\\',\\'{$text}\\');";
    }

    function hht_ref($time)
    {
        $this->flag=false;
        $this->hht_response.="self_ref({$time});";
    }
    function hht_redirect($url)
    {
        $this->flag=false;
        $url = str_replace(array("'",'"'),array("\\'",'\\\"'),$url);
        $this->hht_response.="self.location=\\'{$url}\\'";
    }
    function hht_response_execute()
    {
        if($this->flag===true)
        {
            $this->hht_response = "";
            return true;
        }
        $this->flag = true;
        if(!empty($this->hht_response))
        {
            header("Cache-Control: no-store, no-cache, must-revalidate");
            header("Cache-Control: post-check=0, pre-check=0", false);
            header("Pragma: no-cache");
            ?>
            <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
            <html xmlns="http://www.w3.org/1999/xhtml">
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                <script language="javascript">
                    try
                    {
                        parent.r_call('<?php echo $this->hht_response;?>');
                    }
                    catch(exception)
                    {
                        alert(exception.message);
                    }
                </script>
            </head>
            <body>
            </body>
            </html>
            <?php
            $this->hht_response = "";
        }
        exit;
    }
}
