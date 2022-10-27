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
        //open connection
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $data = $request->toArray();

        switch ($request->getVerb()) {
            case "POST":
                curl_setopt($ch, CURLOPT_URL, $request->getUrl());
                curl_setopt($ch, CURLOPT_POST, count($data));
                curl_setopt($ch, CURLOPT_POSTFIELDS, $this->build_post_fields($data));
                break;
            case "GET":
                curl_setopt($ch, CURLOPT_URL, $request->getUrl() . "?" . $request->getQuery());
                break;
        }


        $result = curl_exec($ch);
        curl_close($ch);

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

    /**
     * Use this to send data with multidimensional arrays and CURLFiles
     *  `curl_setopt($ch, CURLOPT_POSTFIELDS, build_post_fields($postfields));`
     *
     * @param        $data
     * @param string $existingKeys - will set the paramater name, probably don't want to use
     * @param array  $returnArray - Can pass data to start with, only put good data here
     *
     * @return array
     * @author Yisrael Dov Lebow <lebow@lebowtech.com>
     * @see https://stackoverflow.com/questions/3453353/how-to-upload-files-multipart-form-data-with-multidimensional-postfields-using
     * @see http://stackoverflow.com/questions/35000754/array-2-string-conversion-while-using-curlopt-postfields/35002423#comment69460359_35002423
     */
    function build_post_fields($data, $existingKeys = '', &$returnArray = [])
    {
        if (($data instanceof \CURLFile) or !(is_array($data) or is_object($data))) {
            $returnArray[$existingKeys] = $data;
            return $returnArray;
        } else {
            foreach ($data as $key => $item) {
                $this->build_post_fields($item, $existingKeys ? $existingKeys . "[$key]" : $key, $returnArray);
            }
            return $returnArray;
        }
    }
}
