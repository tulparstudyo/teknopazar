<?php
namespace Tulparstudyo\Teknopazar\Adapter;
class Trendyol{
    public $code = 'trendyol';
    public $name = 'Trendyol';
    protected $source;
    protected $client;
    function __construct($source){
        $this->source = $source;
        $this->client = $this->_load_client();
    }
    function client_get_products($page){
        $result =  $this->client->filterProducts(['page'=>$page]);
        return $this->source->convert_products($result);
    }
    private function _load_client(){
        $class_name = "Tulparstudyo\\Teknopazar\\Client\\".ucfirst(strtolower($this->code));
        if(!class_exists($class_name)){
            $class_path = dirname(__FILE__).'/clients/client.'.strtolower($this->code).'.php';
            if(is_file($class_path)){
                include $class_path;
                if(class_exists($class_name)){
                    return new $class_name($this->source->get_pazaryeri_settings($this->code));
                }
            }
        }
        return null;
    }

}