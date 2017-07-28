<?php
# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license

require_once 'IServiceEndPoint.php';
/**
 * soap implementation
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
class SOAPService extends IServiceEndPoint{

   
    public function __construct() {
          ini_set('soap.wsdl_cache_enabled',0);
        $this->_client = new SoapClient(plugin_config_get('url'));
    }

    public function sendAcknowledgeFromEndPoint() {
        $params = array(
            "id" => $_POST["id"],
            "id_issue" => $_POST["id_issue"],
        );
        $return = $this->_client->acknowledge($params);        
        return $return->return;
    }

    public function getListeIssueFromEndPoint($filter_issues) {
        
       

        $params = array(
            "page" => ($_GET['pagination-page'] > 0) ? ($_GET['pagination-page'] - 1) : 0,
            "size" => plugin_config_get('size_page'),
            "filter" => $filter_issues
        );

        $response = $this->_client->listAllIssues($params);
        $return = $response->return;
        $listAllIssues = $return->issues;
        $this->SizeIssue = max($return->size / plugin_config_get('size_page'), 1);
        $this->totalIssue = $return->size;

        if (!is_array($listAllIssues) && $listAllIssues) {
            $listAllIssues = array($listAllIssues);
        }
        if ($listAllIssues == null) {
            $listAllIssues = array();
        }
        $this->listAllIssues = $listAllIssues;
    }

   

}

