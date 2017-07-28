<?php

access_ensure_global_level(plugin_config_get('view_threshold'));
$t_can_manage = access_has_global_level(plugin_config_get('manage_threshold'));

/**
 * Ajax process to create issue via mantis soap api
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
function is_ajax() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
}

if (is_ajax() && $t_can_manage) {

    $issue = array(
        'summary' => gpc_get_string("summary"),
        'description' => gpc_get_string("description"),
        'project' => array(
            'id' => gpc_get_int('projectId', 0)
        ),
        'category' => category_full_name(gpc_get_int("category", 0)),
        'reporter' => array(
            "id" => gpc_get_int('reporterId', 0)
        ),
        'view_state' => array(
            'id' => gpc_get_int('view_state', 0)
        ),
        'priority' => array(
            'id' => gpc_get_int('priority', 0)
        ),
        'severity' => array(
            'id' => gpc_get_int('severity', 0)
        ),
        'reproducibility' => array(
            'id' => gpc_get_int('reproducibility', 0)
        ),
        'date_submitted' => gpc_get_string('dateSubmitted'),
        'handler'=>array(
            'name'=>gpc_get_int( 'assignedTo', 0 )
        ),
        'custom_fields'=> array(
            array(
                'field'=>array(
                    "name"=>"OrigineIssue"
                ),
                "value"=>gpc_get_string("origine")
            )
        ),
        'resolution' => array(
            'id' => gpc_get_int('resolution', 0)
        ),
    );

    $siteURL = 'http' . (empty($_SERVER['HTTPS']) ? '' : 's') . '://' . $_SERVER['HTTP_HOST'] . '/api/soap/mantisconnect.php?wsdl';

    $client = new SoapClient($siteURL);
    $username = plugin_config_get('username_api_mantis');
    $password = plugin_config_get('password_api_mantis');


    try {
        $return = $client->mc_issue_add($username, $password, $issue);
        $response = array("SUCESS" => TRUE, "response" => $return);
    } catch (Exception $ex) {
        $return = $ex->getMessage();
        $response = array("SUCESS" => FALSE, "response" => $return);
    }


    echo json_encode($response);
} else {
    echo json_encode(array("SUCESS" => FALSE));
}



