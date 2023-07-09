<?php
namespace Tulparstudyo\Teknopazar\Source;
class Opencart{
    private $db ;
    function __construct($oc){
        $this->db = $oc->db;
    }
    public function get_pazaryeri_settings($code){
        return [
            'supplierId'=>'108498',
            'username'=>'OtttIGZPJFwCqonagh2v',
            'password'=>'8D5OhXAwAE1JxU1uJNIq',
        ];
    }
    public function convert_product($item){
        $languages = $this->getLanguages();
        print_r($item);
        $product = [];
        $product['model'] = 'T'.$item['id'] ;
        $product['image'] = '';
        $product['price'] = $item['salePrice'] ;
        $product['quantity'] = $item['quantity'] ;
        $product['brand_name'] = $item['brand'];
        $product['category'] = [];
        foreach($languages as $language){
            $product['product_description'][$language['language_id']]['name'] = $item['description'];
        }
        $product['options'] = [];

        return $product;
        }

    public function convert_products($items){
        $result = [];
        foreach($items as $item){
            $result[] =  $this->convert_product($item);
        }
        return $result;
    }
    public function getLanguage($language_id) {
        $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "language WHERE language_id = '" . (int)$language_id . "'");
        return $query->row;
    }
    public function getLanguages() {
        $sql = "SELECT * FROM " . DB_PREFIX . "language";
        $query = $this->db->query($sql);
        return $query->rows;
    }


}