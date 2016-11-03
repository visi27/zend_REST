<?php

class Application_Model_Deal extends Zend_Db_Table_Abstract
{

    protected $_name = 'DEAL_main';

    protected $_primary = 'id';

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
        
    }
}
?>