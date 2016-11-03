<?php

class ApiController extends MyRest_Controller
{
    public function init()
    {
        $this->_helper->viewRenderer->setNoRender(true);
    }
    
    public function indexAction()
    {
        $partnersObj = new Application_Model_PartnersMaster();
        //header('Content-Type: application/json');
        $response = Zend_Json::encode($partnersObj->selectData()->toArray());
        $iterations = 10000;
        

        $a = array("ZendDB" => $partnersObj->countData());

        
        
        
        $dbhost = 'infoweb.virtual';
        $dbuser = 'root';
        $dbpassword = 'asteriskReporT';
        $database = 'billing';
        
        $conn = new mysqli($dbhost, $dbuser, $dbpassword, $database);
        // Check connection
        if ($conn->connect_error) {
            die("MYSQL_INIT FAILED: " . $conn->connect_error);
        }
        $s = microtime(true);
        
        for ($i = 0; $i < $iterations; $i++){
            $res = $conn->query("Select count(*) from partners_master");
        }
        $e = microtime(true);
        
        $a["RAW"]=$e-$s;
        $a["PEAK_MEMORY"] = memory_get_peak_usage(true)/1024/1024;
        $a["MEMORY"] = memory_get_usage(true)/1024/1024;
        
        $response = Zend_Json::encode($a);
        
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
        $item = $this->_request->getPost('item');
        $this->_todo[count($this->_todo) + 1] = $item;
        echo Zend_Json::encode($this->_todo);
    }

    public function putAction()
    {
        echo Zend_Json::encode($item = $this->_request->getParams());
    }

    public function deleteAction()
    {
        echo Zend_Json::encode($item = $this->_request->getParams());
    }
    /**
     * {@inheritDoc}
     * @see Zend_Rest_Controller::headAction()
     */
    public function headAction()
    {
        // TODO Auto-generated method stub
        
    }
}