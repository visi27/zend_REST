<?php

abstract class MyRest_Controller extends Zend_Rest_Controller
{

    public function __construct(Zend_Controller_Request_Abstract $request, Zend_Controller_Response_Abstract $response, array $invokeArgs = array())
    {
        $test = $this->getRequest();
        parent::__construct($request, $response, $invokeArgs);
    }

    public function headAction()
    {
        // you should add your own logic here to check for cache headers from the request
        $this->getResponse()->setBody(null);
    }

    public function optionsAction()
    {
        $this->getResponse()->setBody(null);
        $this->getResponse()->setHeader('Allow', 'OPTIONS, HEAD, INDEX, GET, POST, PUT, DELETE');
    }
}