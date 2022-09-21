<?php 

namespace Konnektive;

require 'C:\Repo\konnektive-api/vendor/autoload.php';
use Konnektive\Dispatcher;
use Konnektive\Request\Customer\QueryCustomersRequest;

 function queryCustomer(){
        $dispatcher = new Dispatcher();
        /**
        * @var $request \Konnektive\Request\Customer\AddCustomerNoteRequest;
        */
        $request = new QueryCustomersRequest();
        $request->loginId = "knk.api";
        $request->password = "APIp@sswd4KNK?!";
        $request->customerId = "1780914";
        var_dump($request); 
 
        /**
        * @var $response \Konnektive\Response\Response;
        */
        $response = $dispatcher->handle($request);
        var_dump($response); 
        if($response->isSuccessful()){ 
          file_put_contents('oidoioi.json', json_encode($response));
        }

    }


$res = queryCustomer();