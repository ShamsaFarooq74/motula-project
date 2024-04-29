<?php

function standard_date_time_format($datetime) {

    return date('d-m-Y h:i A', strtotime($datetime));
}

function getImageUrl($image_name,$type='') {

    $url_scheme = '';
    $domain = '';

    $full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $url_scheme = parse_url($full_url, PHP_URL_SCHEME);

    $domain = request()->getHttpHost();


    if($type == 'company'){
        return  $url_scheme.'://'.$domain.'/company-logo/'.$image_name;
    }elseif($type == 'ads'){
        return $url_scheme.'://'.$domain.'/images/ads/'.$image_name;
    }
    elseif($type == 'images')
    {
        return $url_scheme.'://'.$domain.'/images/profile-pic/'.$image_name;
    }
    elseif($type == 'receipt')
    {
        return $url_scheme.'://'.$domain.'/images/receipt/'.$image_name;
    }
    elseif($type == 'product-attachments')
    {
        return $url_scheme.'://'.$domain.'/images/product-attachments/'.$image_name;
    }
    elseif($type == 'chat-attachments')
    {
        return $url_scheme.'://'.$domain.'/images/chat/'.$image_name;
    }
    elseif($type == 'temporary')
    {
        return $url_scheme.'://'.$domain.'/temporary/'.$image_name;
    }
    else
    {
        return $url_scheme.'://'.$domain.'/assets/images/'.$image_name;
    }

}
//function url_and_domain()
//{
//    $url_scheme = '';
//    $domain = '';
//
//    $full_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//    $url_scheme = parse_url($full_url, PHP_URL_SCHEME);
//
//    $domain = request()->getHttpHost();
//    return [$url_scheme,$domain]
//}
