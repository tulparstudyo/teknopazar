<?php
function getColorGroup($color_name){
    if(empty($color_name)) return 'Renksiz';
    $turkishChars = ['ı', 'ğ', 'İ', 'Ğ', 'ç', 'Ç', 'ş', 'Ş', 'ö', 'Ö', 'ü', 'Ü'];
    $latinChars = ['i', 'g', 'i', 'g', 'c', 'c', 's', 's', 'o', 'o', 'u', 'u'];
    $color_name = str_replace($turkishChars, $latinChars, $color_name);
    $color_name = strtolower($color_name);
    $colors = ['Bej', 'Beyaz', 'Bordo', 'Ekru', 'Gri', 'Haki', 'Kahverengi', 'Kırmızı', 'Lacivert', 'Mavi', 'Mor', 'Pembe', 'Sarı', 'Siyah', 'Turkuaz', 'Turuncu', 'Yeşil', 'Krem', 'Çok Renkli'];
    foreach($colors as $color){
        $scolor = str_replace($turkishChars, $latinChars, $color);
        $scolor = strtolower($scolor);
        if(strpos($color_name, $scolor)){
            return $color;
        }
    }
    return 'Çok Renkli';
}
