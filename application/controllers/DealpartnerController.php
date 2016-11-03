<?php

class DealpartnerController extends MyRest_Controller
{
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function indexAction()
    {
        $page = $this->_request->getParam('page'); // get the requested page
        $limit = $this->_request->getParam('rows'); // get how many rows we want to have into the grid
        $sidx = $this->_request->getParam('sidx'); // get index row - i.e. user click to sort
        $sord = $this->_request->getParam('sord'); // get the direction
        $request = $this->_request->getParam('request');
        
        $dealStatus	= trim($this->_request->getParam('dealStatus')); /* ACT = 1, EXP = 0, ALL*/
        $strMaster	= trim($this->_request->getParam('strMaster'));
        $strPAM		= trim($this->_request->getParam('strPAM'));
        
        $dealPartnerObj = new Application_Model_DealPartner();
        
        $filters = array();
        
        if ($dealStatus == 'ACT' OR $dealStatus == '') {
            $filters["deals_active"] = array(">"=>"0");
        } else if ($dealStatus == 'EXP') {
            $filters["deals_active"] = array("="=>"0");
        }
        
        $filters["status"] = array("="=>"1");
        $filters["name"] = array("LIKE"=>$strMaster."%");
        $filters["partner_account_manager"] = array("LIKE"=>$strPAM."%");
        
        if ($request=="autocome") {
            $count = $dealPartnerObj->getTotal($filters);
        } else {
            $filters_tmp["status"] = array("="=>"1");
            $filters_tmp["deals_active"] = $filters["deals_active"];
            
            $count = $dealPartnerObj->getTotal($filters_tmp);
        }
        
        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        
        if ($page > $total_pages){
            $page=$total_pages;
        }
        if ($limit<0){
            $limit = 0;
        }
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        if ($start<0){
            $start = 0;
        }
        
        if($sidx){
            $order = $sidx." ".$sord;
        }else{
            $order = null;
        }
        
        $data = $dealPartnerObj->selectData($filters,$order,$limit,$start);
        $response = new stdClass();
        $response->page = $page;
        $response->total = $total_pages;
        $response->records = $count;
        $i=0;
        foreach ($data as $row){
            $response->rows[$i]['cell']=array(
                $row['id'],
                $row['name'],
                $row['bill_partners'],
                $row['partner_account_manager'],
                $row['deals_active'],
                $row['pam_deal_revexpect_IN'],
                $row['pam_deal_revexpect_OUT'],
                $row['status']
            );
            $i++;
        }
        //header('Content-Type: application/json');
        $response = Zend_Json::encode($response);
        
        $this->getResponse()->setBody($response);
        $this->getResponse()->setHttpResponseCode(200)
                            ->setHeader('Content-Type', 'application/json');
    }

    public function getAction()
    {
        $partner_id = $this->_request->getParam('id');
        $partnersObj = new Application_Model_PartnersMaster();
        $partner = $partnersObj->getDataById($partner_id);
        
        header('Content-Type: application/json');
        
        if ($partner){
            header('HTTP/1.0 200 OK');
            echo Zend_Json::encode($partner->toArray());
        }else{
            header('HTTP/1.0 404 Not Found');
            echo Zend_Json::encode("Partner with id: $partner_id does not exist");
        }
    }

    public function postAction()
    {
        echo Zend_Json::encode($item = $this->_request->getParams());
    }

    public function putAction()
    {
        echo Zend_Json::encode($item = $this->_request->getParams());
    }

    public function deleteAction()
    {
        echo Zend_Json::encode($item = $this->_request->getParams());
    }
}