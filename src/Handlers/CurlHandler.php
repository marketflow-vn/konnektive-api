<?php

namespace Konnektive\Handlers;

use Konnektive\Contracts\IHandler;
use Konnektive\Request\Request;
use Konnektive\Response\Response;

class CurlHandler implements IHandler
{

    /**
     * @param $request \Konnektive\Request\Request
     * @return \Konnektive\Response\Response
     */
    public function handle(Request $request)
    {
        echo 'May muon cai gi tao???';
        //open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = $request->toArray();
        echo 'bo may la data day';
var_dump($data); 
$verb = $request->getVerb();
echo 'ditmemay api a';
var_dump($verb); 
        switch ($request->getVerb()) {
            case "POST":
                curl_setopt($ch, CURLOPT_URL, $request->getUrl());
                curl_setopt($ch, CURLOPT_POST, count($data));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                break;
            case "GET":
                curl_setopt($ch, CURLOPT_URL, $request->getUrl() . "?" . $request->getQuery());
                break;
        }

        $result = curl_exec($ch);
        echo "may toi so roi\n";
        var_dump(curl_error($ch)); 
        var_dump($ch); 
        curl_close($ch);
        echo "thua\n";

        if (json_decode($result)) {
            return new Response($result);
        } else {

            $response = new Response("");
            $response->result = "FAILURE";
            $response->raw = $result;
            $response->message = "API result was not properly formatted.";

            return $response;
        }
    }
}