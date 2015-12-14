<?php
class db
{
	private $params;
	private $master_conn;
	private $slave_conn;
	private $master_conf;
	private $slave_conf;
	private $stmt_list;
	private $current_stmt=false;
	private $current_sql_type=false;
	private $current_db_type=false;
	private $current_conn;
	private $config;
	private $params_list = array();
	private $current_sql;
	private $error_msg;
	private $uuid;
	private $error_flag;
    private $autocommit;
    private $transaction;
	
	function __construct($db_conf)
	{
		$this->error_flag = true;
        $this->autocommit = true;
        $this->transaction = false;
		if(!is_array($this->config))
		{
			$this->config = $db_conf;
		}
		if(!is_array($this->master_conf))
		{
			if(isset($this->config['slave']) && is_array($this->config['slave']) && sizeof($this->config['slave']) > 0)
			{
				$this->master_conf = $this->config['master'];
				$time = time();
				$k = $time%sizeof($this->config['slave']); 
				$this->slave_conf = $this->config['slave'][$k];
			}
			elseif(isset($this->config['master']))
			{
				$this->master_conf = $this->config['master'];
                $this->slave_conf = -1;
			}
			else 
			{
				$this->master_conf = $this->config;
				$this->slave_conf = -1;
			}
		}
	}
	
	/**
	 * 设置整型参数
	 *
	 * @param int $value
	 */
	public function set_int($value)
	{
		$this->set_params('i',$value);
	}
	
	/**
	 * 设置浮点型参数
	 *
	 * @param float $value
	 */
	public function set_float($value)
	{
		$this->set_params('d',$value);
	}
	
	/**
	 * 设置字符串参数
	 *
	 * @param char $value
	 */
	public function set_string($value)
	{
		$this->set_params('s',$value);
	}
	
	/**
	 * 设置blob型参数
	 *
	 * @param blob $value
	 */
	public function set_blob($value)
	{
		$this->set_params('b',$value);
	}
	
	/**
	 * 生成uuid
	 *
	 * @return unknown
	 */
    public function get_uuid()
    {
        $this->prepare('select uuid() uuid');
        $array = $this->execute(true);
        return $array['uuid'];
    }
    
    public function start_transaction()
    {
        if($this->transaction === false)
        {
            $this->transaction = true;
        }
    }
    
    public function commit()
    {
        return $this->master_conn->commit();
    }
    
    public function rollback()
    {
        $this->master_conn->rollback();
    }
    
    public function end_transaction()
    {
        $this->master_conn->autocommit(true);
        $this->autocommit = true;
        $this->transaction = false;
    }


    /**
	 * 编辑sql
	 *
	 * @param char $sql
	 * @param bool $db_type
	 * @return bool
	 */
	public function prepare($sql,$db_type='master')
	{
		$this->current_sql = $sql;
		$this->_get_db_type($sql,$db_type);
		$res = $this->_connect();
		if($res === false)
		{
			$this->error_flag = false;
			return false;
		}
		
		$this->current_stmt = $this->current_conn->prepare($sql);
		
		if($this->current_stmt===false)
		{
			$this->error_flag = false;
			$this->error_log('sql:'.$sql.'  error:'.$this->current_conn->error);
			return false;
		}
		$this->stmt_list[$sql] = $this->current_stmt;
		return true;
	}
	
	/**
	 * 简单sql执行，推荐没有参数传入的sql使用此方法,
	 * 如果是select，describe,show，返回结果集，否则返回true或者false
	 *
	 * @param char $sql
	 * @param bool $db_type
	 * @return mixed
	 */
	public function query($sql,$db_type='master')
	{
		$this->current_sql = $sql;
		$this->_get_db_type($sql,$db_type);
		$res = $this->_connect();
		if($res === false)
		{
			return false;
		}
		$res = $this->current_conn->query($sql);
		if($res === false)
		{
			$this->error_log('sql:'.$sql.'  error:'.$this->current_conn->error);
			return false;
		}
		switch ($this->current_sql_type)
		{
			case 'select':
			case 'descri':
			case 'show':
				$list = array();
				while ($row = $res->fetch_array(MYSQLI_ASSOC))
				{
					$list[] = $row;
				}
			break;
			default:
				$list = true;
		}
		$this->clear_current_data();
		return $list;
	}
	
	/**
	 * 执行查询,如果是select  返回结果集，否则返回true或者false
	 *
	 * @param bool $get_one
	 * @return mixed
	 */
	public function execute($get_one=false)
	{
		if($this->error_flag === false)
		{
			$this->error_flag = true;
			return false;
		}
		if(is_array($this->params_list) && sizeof($this->params_list)>0)
		{
			$type_str = '';
			$values ='';
			foreach ($this->params_list as $k=>$v)
			{
				$type_str .= $v[0];
				$values .= ',$this->params_list['.$k.'][1]';
			}
			$bind_func = '$this->current_stmt->bind_param(\''.$type_str.'\''.$values.');';
			eval($bind_func);
			$this->params_list = array();
		}
		$res = $this->current_stmt->execute();
		if($res === false)
		{
			$this->error_log($this->current_sql.'  '.$this->current_stmt->error);
			return false;
		}
		switch ($this->current_sql_type)
		{
			case 'select':
				$this->current_stmt->store_result();
				$result = $this->current_stmt->result_metadata();
				$field_list = array();
				$bind_res_func = '$this->current_stmt->bind_result($';
				while($field = $result->fetch_field())
				{
					$field_list[] = $field->name;
				}
				$bind_res_func .= implode(', $',$field_list).');';
				eval($bind_res_func);
				$list = array();
				while ($this->current_stmt->fetch())
				{
					$tmp = array();
					foreach ($field_list as $_field)
					{
						$tmp[$_field] = $$_field;
					}
					if($get_one === true)
					{
						$list = $tmp;
						break;
					}
					else 
					{
						$list[] = $tmp;
					}
				}
			break;
			case 'insert':
				$list = true;
			break;
			case 'update':
				$list = true;
			break;
			case 'delete':
				$list = true;
			break;
			case 'replace':
				$list = true;
			break;
			default:
				$this->error_log($this->current_sql.' can not support this sql');
				$list = false;
		}
		$this->clear_current_data();
		return $list;
	}
	
	/**
	 * 根据id获得一条记录
	 *
	 * @param char $tablename
	 * @param int $index
	 * @param char $indexname
	 * @return array
	 */
    public function get_one($tablename, $index, $indexname='id', $db_type='master')
    {
    	$clo_type = $this->get_table_construct($tablename);
    	if(!isset($clo_type['cols'][$indexname]))
        {
        	return false;
        }
        if($this->prepare("select * from $tablename where $indexname=?", $db_type)===false)
        {
            return false;
        }
        $func = 'set_'.(isset($clo_type['cols'][$indexname])?$clo_type['cols'][$indexname]:'string');
        $this->$func($index);
        return $this->execute(true);
    }
    
    /**
	 * 根据id获得一条正常记录
	 *
	 * @param char $tablename
	 * @param int $index
	 * @param char $indexname
	 * @return array
	 */
    public function get_one_normal($tablename, $index, $indexname='id', $db_type = 'master')
    {
    	$clo_type = $this->get_table_construct($tablename);
    	if(!isset($clo_type['cols'][$indexname]))
        {
        	return false;
        }
        if($this->prepare("select * from $tablename where $indexname=? and status=".STATUS_NORMAL, $db_type)===false)
        {
            return false;
        }
        $func = 'set_'.(isset($clo_type['cols'][$indexname])?$clo_type['cols'][$indexname]:'string');
        $this->$func($index);
        return $this->execute(true);
    }
	
    /**
     * 根据单个条件删除
     *
     * @param char $tablename
     * @param array $index
     * @param 键名 $indexname
     * @return bool
     */
    public function delete($tablename, $index, $indexname='id')
    {
    	$clo_type = $this->get_table_construct($tablename);
    	if(!isset($clo_type['cols'][$indexname]))
        {
        	return false;
        }
        $ids = explode(',', $index);
        if(!is_array($ids)) return false;
        $count = sizeof($ids);
        $values = implode(',', array_fill(0,$count,'?'));
        if($this->prepare("delete from $tablename where $indexname in ($values)")===false)
        {
            return false;
        }
        $func = 'set_'.(isset($clo_type['cols'][$indexname])?$clo_type['cols'][$indexname]:'string');
        foreach($ids as $id)
        {
            $this->$func($id);
        }
        return $this->execute();
    }

    /**
     * 记录逻辑删除
     * @author menghao
     * @param <string> $tablename
     * @param <array> $condition
     */
    function fake_delete($tablename, $condition)
    {
        return $this->simple_update($tablename, array('status'=>STATUS_DEL), $condition);
    }
    
    /**
     * insert 执行
     *
     * @param char $table
     * @param array $params
     * @return bool
     */
    public function insert($table,$params)
    {
    	$clo_type=$this->get_table_construct($table);
    	foreach ($params as $k=>$v)
    	{
    		if(!isset($clo_type['cols'][$k]))
    		{
    			unset($params[$k]);
    		}
    	}
    	
    	if(isset($clo_type['uuid']) && !empty($clo_type['uuid']) && isset($clo_type['cols'][$clo_type['uuid']]))
    	{
            $this->uuid = $this->get_uuid();
    		$params[$clo_type['uuid']] = empty($params[$clo_type['uuid']]) ? $this->uuid : $params[$clo_type['uuid']];
    		$this->uuid = $params[$clo_type['uuid']];
    	}
    	$cols = array_keys($params);
    	$vs = array_fill(0,sizeof($cols),'?');
    	if(sizeof($vs)==0 || sizeof($cols)==0)
    	{
    		return false;
    	}
    	$sql = 'insert into `'.$table.'` (`'.implode('`,`',$cols).'`) values ('.implode(',',$vs).')';
    	$stmt = $this->prepare($sql);
    	if($stmt===false)
    	{
    		return false;
    	}
    	foreach ($params as $k=>$v)
    	{
    		$func = 'set_'.(isset($clo_type['cols'][$k])?$clo_type['cols'][$k]:'string');
    		$this->$func($v);
    	}
    	$res = $this->execute();
    	
    	return $res;
    }
    
    /**
     * 简单的update,where条件只支持xxx=xxx and xxx=xxx ...
     *
     * @param char $table
     * @param array $params
     * @param array $condition
     * @param char $columns
     * @return bool
     */
    public function simple_update($table,$params,$condition,$columns='')
    {
    	$clo_type=$this->get_table_construct($table);
    	$value_list = array();
    	$cols = array();
    	if(is_array($params) && sizeof($params) > 0)
    	{
    		foreach ($params as $k=>$v)
    		{
    			if(!isset($clo_type['cols'][$k]))
	    		{
	    			unset($params['cols'][$k]);
	    			continue;
	    		}
    			$value_list[] = array(isset($clo_type['cols'][$k])?$clo_type['cols'][$k]:'string',$v);
    			$cols[] = '`'.$k.'`'.'=?';
    		}
    	}
    	if(sizeof($value_list)==0)
    	{
    		return false;
    	}
    	$sql = 'update `'.$table.'` set '.implode(',',$cols);
    	if(!empty($columns))
    	{
    		$sql .= sizeof($cols) > 0 ? ','.$columns : $columns;
    	}
    	$condition_array = array();
    	if(is_array($condition) && sizeof($condition) > 0)
    	{
    		foreach ($condition as $k=>$v)
    		{
    			if(!isset($clo_type['cols'][$k]))
	    		{
	    			unset($condition[$k]);
	    			continue;
	    		}
    			$condition_array[] = '`'.$k.'`=?';
    			$value_list[] = array(isset($clo_type['cols'][$k])?$clo_type['cols'][$k]:'string',$v);
    		}
    	}
    	if(sizeof($condition_array)==0)
    	{
    		return false;
    	}
    	if(sizeof($condition_array) > 0)
    	{
    		$sql .= ' where '.implode(' and ',$condition_array);
    	}
    	$stmt = $this->prepare($sql);
    	if($stmt==false)
    	{
    		return false;
    	}
    	if(sizeof($value_list) > 0)
    	{
    		foreach ($value_list as $v)
    		{
    			$func = 'set_'.$v[0];
    			$this->$func($v[1]);
    		}
    	}
    	$res = $this->execute();
    	return $res;
    	
    }
	
	/**
	 * 获得刚插入的记录的自增id
	 *
	 * @return int
	 */
	public function get_insert_id()
	{
        return $this->master_conn->insert_id;
	}
	
	/**
	 * 获得港插入的记录的uuid
	 *
	 * @return string
	 */
	public function uuid()
	{
		return $this->uuid;
	}
	
	/**
	 * 获得错误信息 
	 *
	 * @return array
	 */
	public function get_error_log()
	{
		return $this->error_msg;
	}
	
	public function clean_cache()
	{
		$dir = CACHE_TABLE;
		if (is_dir($dir)) 
		{
		    if ($dh = opendir($dir)) 
		    {
		        while (($file = readdir($dh)) !== false) 
		        {
		        	$res = stristr($file, '.log');
		            if(!empty($res) && file_exists($dir . $file))
		            {
		            	unlink($dir . $file);
		            }
		        }
		        closedir($dh);
		    }
		}

	}
	
	/**
	 * 连接数据库 
	 *
	 * @return resource
	 */
	private function _connect()
	{
		if($this->current_db_type=='master')
		{
			$config = $this->master_conf;
			$this->current_conn = &$this->master_conn;
			$conn_name = 'master_conn';
		}
		else 
		{
			$config = $this->slave_conf;
			$this->current_conn = &$this->slave_conn;
			$conn_name = 'slave_conn';
		}
		if(is_a($this->current_conn,'mysqli') && $this->current_conn->ping()===true)
		{
			$res = true;
		}
		else 
		{
			$this->$conn_name = new mysqli($config['host'],$config['username'],$config['passwd'],$config['dbname'],$config['port']);
			if (mysqli_connect_errno()) 
			{
				$this->error_log('can not connect db: '.mysqli_connect_error().'  '.implode('  ',$config));
			    return false;
			}
			$this->$conn_name->query('set names utf8');
            $res = true;
		}
        if($this->current_db_type == 'master' && $this->transaction==true && $this->autocommit==true)
        {
            $this->master_conn->autocommit(false);
            $this->autocommit = false;
        }
        return $res;
	}
	
	/**
	 * 获得数据表字段及对应的类型
	 *
	 * @param string $table
	 * @return array
	 */
	public function get_table_construct($table)
	{
            $res = $this->query('describe `'.$table.'`','master');
            $list = array();
            $uuid_col = '';
            foreach ($res as $k=>$v)
            {
                    $type = preg_replace('/\([0-9,]*\)/','',$v['Type']);
                    if($v['Type']=='char(36)' && $v['Key']=='PRI')
                    {
                            $uuid_col = $v['Field'];
                    }
                    $list[$v['Field']] = $this->set_type($type);
            }
            $result = array(
                    'uuid'	=>	$uuid_col,
                    'cols'	=>	$list
            );
            return $result;
	}
	
	/**
	 * 设置数据库字段类型对应的类型
	 *
	 * @param char $type
	 * @return char
	 */
	private function set_type($type)
	{
		switch (strtolower($type))
		{
			case 'int':
			case 'tinyint':
			case 'smallint':
			case 'medium':
			case 'bigint':
			case 'bit':
				return 'int';
				break;
			case 'decimal':
			case 'float':
			case 'double':
			case 'real':
				return 'float';
				break;
			default:
				return 'string';
		}
	}
	
	/**
	 * 设置参数到数组里
	 *
	 * @param char $type
	 * @param char $value
	 */
	private function set_params($type,$value)
	{
		array_push($this->params_list,array($type,$value));
	}
	
	/**
	 * 获得sql执行的类型,并确定数据库连接类型
	 *
	 * @param string $sql
	 * @param string $db_type
	 */
	private function _get_db_type($sql,$db_type)
	{
		if(substr(strtolower($sql),0,4)=='show')
		{
			$sql_type = 'show';
		}
		else 
		{
			$sql_type = substr(strtolower($sql),0,6);
		}
		$this->current_sql_type = $sql_type;
		if($db_type === false)
		{
			$this->current_db_type = ($this->current_sql_type=='select' && $this->slave_conf != -1) ? 'slave' : 'master';
		}
		else 
		{
			$this->current_db_type = $db_type;
		}
	}
	
	/**
	 * 清除本次sql执行产生的数据
	 *
	 */
	private function clear_current_data()
	{
		//$this->current_conn = false;
		$this->current_db_type = false;
		$this->current_sql = false;
		$this->current_sql_type=false;
		$this->current_stmt = false;
	}
	
	/**
	 * 记录错误日志
	 *
	 * @param char $error_msg
	 */
	private function error_log($error_msg)
	{
		$this->error_msg[] = $error_msg;
	}
	
	/**
	 * 关闭数据库连接以及stmt句柄
	 *
	 */
	function __destruct()
	{
//        if(DEBUG && !defined('TERMINAL'))
//        {
//            include_once 'coverage.class.php';
//            $coverage = new coverage();
//            $data = (xdebug_get_code_coverage());
//            $coverage->save($data);
//        }
		$this->uuid = false;
		if(is_array($this->stmt_list))
		{
			foreach ($this->stmt_list as $k=>$stmt)
			{
				$stmt->close();
			}
			$this->stmt_list = array();
		}
		if(is_a($this->master_conn,'mysqli'))
		{
			$this->master_conn->close();
		}
		if(is_a($this->slave_conn,'mysqli'))
		{
			$this->slave_conn->close();
		}
	}
	
	
}
?>
