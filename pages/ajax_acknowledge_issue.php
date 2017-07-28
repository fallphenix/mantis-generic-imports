<?php

# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license

require_once 'ServiceFactory.php';
/**
 * Ajax process to send end point acknowledge
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
access_ensure_global_level(plugin_config_get('view_threshold'));
$t_can_manage = access_has_global_level(plugin_config_get('manage_threshold'));

function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax() && $t_can_manage) {
    
   
    try {
        $t_type_webservice = plugin_config_get('type');
        $_service = ServiceFactory::create($t_type_webservice);
        $bool = $_service->sendAcknowledgeFromEndPoint();
        if ($bool) :
            $response = array("SUCESS" => TRUE);
        else:
            $response = array("SUCESS" => FALSE);
        endif;
    } catch (Exception $ex) {
        $response = array("SUCESS" => FALSE);
    }

    echo json_encode($response);
} else {
    echo json_encode(array("SUCESS" => FALSE));
}



