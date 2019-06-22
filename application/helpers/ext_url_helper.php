<?php

/*
| Base URL
|
| Overwrite the base_url function to support
| loading designated content from KeyCDN.
*/
function base_url($uri='')
{
    $current_instance =& get_instance();
    $cdn_url = $current_instance->config->item('cdn_url');

    // define any extension that should use your CDN URL
    $extensions = array('css', 'js', 'svg', 'jpg', 'jpeg', 'png', 'gif', 'pdf');
    $path_parts = pathinfo($uri);

    if ( !empty($cdn_url) && 
         array_key_exists('extension', $path_parts) && 
         in_array($path_parts['extension'], $extensions) ) {
        return $cdn_url . $uri;
    }

    return $current_instance->config->base_url($uri);
}
