<?php

class PartnersController extends MyRest_Controller
{
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function indexAction()
    {
        $partnersObj = new Application_Model_PartnersMaster();
        $limit = $this->_request->getParam('limit');
        $start = $this->_request->getParam('start');
        $callback = $this->_request->getParam('callback');
        $draw = $this->_request->getParam('draw');
        
        //header('Content-Type: application/json');
        $results = $partnersObj->selectData(null,null,$limit,$start);
        $count = $results->count();
        $response = Zend_Json::encode(array("draw" => $draw,
                                            "recordsTotal" => $count,
                                            "recordsFiltered" => $count,
                                            "data" => $results->toArray()));
        
        if($callback){
            $response = $callback.'('.$response.')';
        }
        
        $this->getResponse()->setBody($response);
        $this->getResponse()->setHttpResponseCode(200)
                            ->setHeader('Content-Type', 'application/json');
    }

    public function getAction()
    {
        $partner_id = $this->_request->getParam('id');
        $partnersObj = new Application_Model_PartnersMaster();
        $partner = $partnersObj->getDataById($partner_id);
        
        $callback = $this->_request->getParam('callback');

        if ($partner){
            $response = Zend_Json::encode($partner->toArray());
            
            if($callback){
                $response = $callback.'('.$response.')';
            }
            
            $this->getResponse()->setHttpResponseCode(200)
                            ->setHeader('Content-Type', 'application/json');
            $this->getResponse()->setBody($response);
        }else{
            $this->getResponse()->setHttpResponseCode(404)
                            ->setHeader('Content-Type', 'application/json');
            $this->getResponse()->setBody(Zend_Json::encode("Partner with id: $partner_id does not exist"));
        }
    }

    public function postAction()
    {
        $todo = array();
        $item = $this->_request->getPost('item');
        $todo[count($todo) + 1] = $item;
        echo Zend_Json::encode($todo);
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