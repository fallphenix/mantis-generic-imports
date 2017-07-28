<?php

# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license

/**
 * abstraction for end point webservice 
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */


abstract class IServiceEndPoint {
    protected $username;
    protected $password;
    protected $_client;
    protected $listAllIssues;
    protected $totalIssue;
    protected $SizeIssue; 

  abstract   public function sendAcknowledgeFromEndPoint();

   abstract  public function getListeIssueFromEndPoint($filter_issues);

    function getUsername() {
        return $this->username;
    }

    function getPassword() {
        return $this->password;
    }

    function get_client() {
        return $this->_client;
    }

    function getListAllIssues() {
        return $this->listAllIssues;
    }

    function getTotalIssue() {
        return $this->totalIssue;
    }

    function getSizeIssue() {
        return $this->SizeIssue;
    }

    function setUsername($username) {
        $this->username = $username;
    }

    function setPassword($password) {
        $this->password = $password;
    }

    function set_client($_client) {
        $this->_client = $_client;
    }

    function setListAllIssues($listAllIssues) {
        $this->listAllIssues = $listAllIssues;
    }

    function setTotalIssue($totalIssue) {
        $this->totalIssue = $totalIssue;
    }

    function setSizeIssue($SizeIssue) {
        $this->SizeIssue = $SizeIssue;
    }

}
