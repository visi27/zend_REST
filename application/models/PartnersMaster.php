<?php

class Application_Model_PartnersMaster extends Zend_Db_Table_Abstract
{

    protected $_name = 'partners_master';

    protected $_primary = 'partner_id';

    public function getDataById($id, $current = true)
    {
        if ($current == true) {
            return $this->find($id)->current();
        } else {
            return $this->find($id);
        }
    }

    public function selectData($filters = array(), $sortField = null, $limit = null, $start = 0)
    {
        $select = $this->select()
        ->from($this,array("partner_id", "partner_acc_name", "partner_master", "partner_company", "partner_is_client", "partner_is_provider"));
        // add any filters which are set
        if (count($filters) > 0) {
            foreach ($filters as $field => $filter) {
                if (count($filter) > 0) {
                    foreach ($filter as $operator => $value)
                        $select->where($field . $operator . '?', $value);
                }
            }
        }
        // add the sort field if it is set
        if (null != $sortField) {
            $select->order($sortField);
        }
        // add the limit field if it is set
        if (null != $limit) {
            $select->limit($limit, $start);
        }
        // $test = $select->__toString();
        return $this->fetchAll($select);
        // return $select->__toString();
    }
    
    public function countData($filters = array())
    {
        $select = $this->select()
        ->from($this,array("count(*) as count"));
        // add any filters which are set
        
        $iterations = 10000;
        
        $s = microtime(true);
        for ($i = 0; $i < $iterations; $i++){
            $this->fetchAll($select);
        }
        
        $e = microtime(true);
        
        return $e-$s;
    }
}
?>