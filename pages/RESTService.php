<?php
# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license

require_once 'IServiceEndPoint.php';
require_once 'restclient.php';

/**
 * Rest implementation
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
class RESTService extends IServiceEndPoint {

    public function __construct() {

        $this->_client = new RestClient([
            'base_url' => plugin_config_get('url'),
            'format' => "json"
        ]);
    }

    public function sendAcknowledgeFromEndPoint() {
     
        
         $result = $this->_client->get("issues/acknowledge/".$_POST["id"]."/".$_POST["id_issue"]);
         $return=false;
         if ($result->info->http_code == 200) { 
              $return = $result->decode_response();
         }
         
        return $return;
    }

    public function getListeIssueFromEndPoint($filter_issues) {
        $params = array(
            "page" => ($_GET['pagination-page'] > 0) ? ($_GET['pagination-page'] - 1) : 0,
            "size" => plugin_config_get('size_page'),
            "filter" => $filter_issues
        );
        $result = $this->_client->get("issues", $params);
        
        if ($result->info->http_code == 200) {
            $return = $result->decode_response();
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
        } else {
            $this->listAllIssues=array();
            $this->totalIssue=0;
            $this->SizeIssue=0;
        }

    }

}
