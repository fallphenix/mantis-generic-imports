<?php

# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license

access_ensure_global_level(plugin_config_get('view_threshold'));
$t_can_manage = access_has_global_level(plugin_config_get('manage_threshold'));

/**
 * Ajax to list all category by project
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax() && $t_can_manage) {

    $p_project_id = (int) $_POST["id_project"];
    $response = array("SUCESS" => TRUE);
    $all_categories_by_project = category_get_all_rows($p_project_id);
    $response["data"] = json_encode($all_categories_by_project);
    echo json_encode($response);
} else {
    echo json_encode(array("SUCESS" => FALSE));
}



