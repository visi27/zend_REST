<?php

class Application_Model_DealPartner extends Zend_Db_Table_Abstract
{

    protected $_name = 'DEAL_partner';

    protected $_primary = 'id';

    public function getDataById($id, $current = true)
    {
        if ($current == true) {
            return $this->find($id)->current();
        } else {
            return $this->find($id);
        }
    }

    public function selectData($filters = array(), $sortField, $limit = null, $start = 0)
    {
        $select = $this->select()
        ->from($this,array("id","name","bill_partners","partner_account_manager","deals_active",
                           "pam_deal_revexpect_IN","pam_deal_revexpect_OUT","status"));
          
        // add any filters which are set
        if (count($filters) > 0) {
            foreach ($filters as $field => $filter) {
                if (count($filter) > 0) {
                    foreach ($filter as $operator => $value)
                        $select->where($field ." ". $operator ." ". '?', $value);
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
    
    public function getTotal($filters = array(), $sortField = null, $limit = null, $start = 0){
        $select = $this->select()
                        ->from($this, array("count(1) as count"));
        $log = new Util_FileLogger();
        if (count($filters) > 0) {
            foreach ($filters as $field => $filter) {
                if (count($filter) > 0) {
                    foreach ($filter as $operator => $value)
                        $select->where($field . $operator . '?', $value);
                }
            }
        }
        $log->log($select->__toString());
         $result = $this->fetchRow($select);
         if ($result) {
             return $result->count;
         } else {
             return 0;
         }
         
         
    }
    
    public function countData($filters = array())
    {
        
    }
}
?>