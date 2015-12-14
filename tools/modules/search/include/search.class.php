<?php
/**
 * Description of search
 * @查找数据：
 * $search = new search();
 * $data = $search->search($key, $page);
 * 
 * @查找id为228325的数据
 * $search->addQueryTerm('id', 228325);
 * $data = $search->search(null, $page);
 * 
 * @查找id为228325并且source等于1的数据并且关键词为"人民"的数据
 * $search->addQueryTerm(array('id'=>228325, 'source'=>1));
 * $data = $search->search("人民", $page);
 * 
 * @查找addtime在1257731203和125733234之间的数据并且关键词为"人民"的数据
 * $search->addRange('addtime', 125733234, 1257731203);
 * $data = $search->search("人民", $page);
 * 
 * @添加文档
 * $doc = new XSDocument($item);
 * $search->setDoc($doc);
 * 
 * @从数据库添加文档
 * $search->addFromTable('edufe_news');
 * 
 * @author menghao
 */
$dir = substr(__FILE__,0,(0-strlen(basename(__FILE__))));
define('SEARCH_CONFIG', $dir.'mytest.ini');
if(!defined('PAGE_SIZE')){
    define('PAGE_SIZE', 20);
}
if(!class_exists('XS')){
    include 'XS.php';
}

class search
{
    private $xs, $search, $index, $fields;
    public function __construct()
    {
        $this->xs = new XS(SEARCH_CONFIG);
        $this->search = $this->xs->getSearch();
        $this->index = $this->xs->getIndex();
        $this->fields = $this->getAllFields();
    }
    

    /**
     * 执行搜索
     * @param type $key
     * @param type $page
     * @param type $pagesize
     * @return type
     */
    public function search($key, $page = 1, $pagesize = PAGE_SIZE)
    {
        $siteid = \staticObj::$siteid;
        $data = $return = array();
        $offset = ($page - 1) * $pagesize;
        $this->key = $key;
        
        
        $this->search->addQueryString($key.' siteid:'.$siteid);
        $this->search->setLimit($pagesize, $offset);
        
        $result = $this->search->search();
        $return['count'] = $this->search->count();
        
        foreach($result as $doc){
            $data[] = $this->getDoc($doc);
        }
        $return['data'] = $data;
        $return['correct'] = $this->getCorrectedQuery($key);
        $return['relate'] = $this->getRelatedQuery($key, 10);
        $this->search->setQuery($key);
        $this->search->search();
        $this->pages = pages($return['count'], $page, $pagesize, 1);
        return $return;
    }
    
    public function getRelatedQuery($key, $limit = 10)
    {
        return $this->search->getRelatedQuery($key, $limit);
    }
    
    public function getExpandedQuery($key, $limit = 10)
    {
        return $this->search->getExpandedQuery($key, $limit);
    }
    
    public function getCorrectedQuery($key)
    {
        return $this->search->getCorrectedQuery($key);
    }
    
    /**
     * 热门搜索
     * @param type $limit  需要返回的热门搜索数量上限, 默认为 6, 最大值为 50
     * @param type $type 默认为 total(搜索总量), 可选值还有 lastnum(上周), currnum(本周)
     * @return type
     */
    public function getHotQuery($limit = 6, $type = 'total')
    {
        return $this->search->getHotQuery($limit, $type);
    }
    
    /**
     * 按字段查询
     * @param type $field
     * @param type $value
     */
    public function addQueryTerm($field, $value = '')
    {
        if(is_array($field)){
            foreach($field as $k=>$v){
                $this->addQueryTerm($k, $v);
            }
            return true;
        }
        return $this->search->addQueryTerm($field, $value);
    }
    
    /**
     * 设置搜索范围
     * @param string $field
     * @param mixed $from
     * @param mixed $to
     * @return type
     */
    public function addRange(string $field, mixed $from, mixed $to)
    {
        return $this->search->addRange($field, $from, $to);
    }

    /**
     * 将文档对象转为数组
     * @param XSDocument $doc
     * @return \XSDocument
     */
    private function getDoc(XSDocument $doc)
    {
        $data = array();
        
        foreach($this->fields as $field => $info)
        {
            if(in_array($info->type, array(XSFieldMeta::TYPE_TITLE, XSFieldMeta::TYPE_BODY))){
                $data[$field] = $this->search->highlight($doc[$field]);
            }else{
                $data[$field] = $doc[$field];
            }
        }
        return $data;
    }
    
    /**
     * 刷新日志
     * @return type
     */
    public function flushLogging()
    {
        return $this->index->flushLogging();
    }
    
    /**
     * 刷新索引
     * @return type
     */
    public function flushIndex()
    {
        return $this->index->flushIndex();
    }

    /**
     * 获取索引的字段
     * @return type
     */
    private function getAllFields()
    {
        return $this->xs->getAllFields();
    }
    
    /**
     * 添加自定义词典
     * @param string $content
     * @return type
     */
    public function setCustomDict($content)
    {
        return $this->index->setCustomDict($content);
    }
    
    /**
     * 添加文档
     * @param XSDocument $doc
     * @return type
     */
    public function setDoc($doc)
    {
        $bodyField = $this->getFieldBody();
        if(isset($doc['thumb']))
        {
            $doc['thumb'] = $this->getDocPic($doc[$bodyField]);
        }
        $doc[$bodyField] = strip_tags($doc[$bodyField]);
        $doc = new XSDocument($doc);
        return $this->index->update($doc);
    }
    
    public function delDoc($id)
    {
        return $this->index->del($id);
    }


    /**
     * 获取内容封面
     * @param type $content
     * @return array
     */
    public function getDocPic($content)
    {
        $imgs = array();
        preg_match_all("/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/", $content, $imgs);
        return isset($imgs[1][0]) ? $imgs[1][0] : '';
    }

    public function getFieldTitle()
    {
        return (string)$this->xs->getFieldTitle();
    }
    
    public function getFieldBody()
    {
        return (string)$this->xs->getFieldBody();
    }

    /**
     * 从数据表获取索引
     * @param string $table
     */
    public function addFromTable($table)
    {
        $db = &\staticObj::$db;
        $step = 5000;
        for($i=0;1==1;$i=$i+$step)
        {
            $db->prepare('select * from ' . $table . ' limit ?, ?');
            $db->set_int($i);
            $db->set_int($step);
            $data = $db->execute();
            foreach($data as $r)
            {
                $item = array();
                foreach($this->fields as $field => $info)
                {
                    $item[$field] = $r[$field];
                }
                if($r['status']==0)
                {
                    $this->delDoc($r['id']);
                }
                else
                {
                    $this->setDoc($item);
                }
            }
            if(sizeof($data)<$step)
            {
                break;
            }
        }
        $this->flushIndex();
        return true;
    }
}