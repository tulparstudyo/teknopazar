<?php
namespace Tulparstudyo;
class Tekopazar{
    protected $source;
    protected $adapter;
    protected $errors = [];
    function __construct($pazaryeri, $source, $instance=null) {
        $this->source = $this->_load_source($source, $instance);
        $this->adapter = $this->_load_adapter($pazaryeri, $this->source);
    }
    public function go($action, $params){
        $this->errors = [];
        if(method_exists($this->adapter, $action)){
            return $this->adapter->$action($params);
        } else {
            $error= 'Metod Bulunamadı: '.$action;
            $this->_add_error($this->adapter->name, $error);
        }
        return null;
    }
    public function client_get_products($page){
        $this->adapter->get_products($page);
    }
    private function _add_error($module, $error){
        $this->errors[] = [
            'time'=>date('H:i:s'),
            'module'=>$module,
            'error'=>$error,
        ];
    }
}