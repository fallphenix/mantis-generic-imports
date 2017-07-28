<?php
# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license
require_once  'SOAPService.php';
require_once  'RESTService.php';

/**
 * Factory to create implementation instance
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
class ServiceFactory
{
    public static function create($type)
    {
        
        switch ($type) {
            case "REST":
                 return new RESTService();
                break;
           case "SOAP":
                 return new SOAPService();
                break;
            default:
                throw new Exception(plugin_lang_get('exception_unknow_implementation'));
                break;
        }
      
    }
}
