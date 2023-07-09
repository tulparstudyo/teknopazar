<?php
namespace Tulparstudyo\Teknopazar\Client;
class Trendyol{
    private $host='https://api.trendyol.com/sapigw/';
    private $settings = [];
    private $debug = [];

    function __construct($settings){
        $this->settings = $settings;
    }

    public function filterProducts($filter){
        $enpoint = 'suppliers/{{supplierId}}/products';
        $page = isset($filter['page'])?$filter['page']:1;
        $data= ['page'=>$page];
        $response = $this->_get($enpoint, $data);
        if(isset($response['content'])) return $response['content'];
        return [];
    }
    private function _get($enpoint, $data=[], $headers=[]){
        return $this->_load($enpoint, 'GET', $data, $headers);
    }
    private function _load($enpoint, $method, $data=[], $headers=[]){
        $enpoint = $this->host.str_replace('{{supplierId}}', $this->settings['supplierId'], $enpoint);
        $headers[] = 'Content-Type: application/json';
        $headers[] = "Authorization: Basic ".base64_encode( $this->settings['username'] . ":" . $this->settings['password'] );
        $headers[] = "user-agent: ".$this->settings['supplierId']." - SelfIntegration";
        $curl = curl_init();
        switch ($method) {
            case "GET":
                curl_setopt($curl, CURLOPT_HEADER, false);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
                if ($data)$enpoint = sprintf("%s?%s", $enpoint, http_build_query($data));
                break;
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "DELETE":
                curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "DELETE");
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
        }

        curl_setopt($curl, CURLOPT_URL, $enpoint);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        $response = curl_exec($curl);

        $status_code = curl_getinfo($curl, CURLINFO_HTTP_CODE); //get status code
        $err = curl_error($curl);
        curl_close($curl);
        if($err){
            print_r($err);
            die('hatada dur....');
        }
        $result = json_decode($response, 1);
        $result['status_code'] = $status_code;
        $error_message = '';
        if(isset($result['errors'])){
            foreach($result['errors'] as $error){
                if(isset($error['merchantSku'])){
                    $error_message .= "<hr>".$error['merchantSku'].': '.implode('<br>', $error);
                } else{
                    $error_message .= "<br>".implode('<br>', $error);
                }
            }
        }
        $result['error_message']  = $error_message;
        $this->debug['Host'] = $enpoint;
        $this->debug['Authorization'] = $this->settings['username'] . ":" . $this->settings['password'];
        $this->debug['Method'] = $method;
        $this->debug['HTTP Code'] = $status_code;
        $this->debug['Headers'] = $headers;
        return $result;

    }
}