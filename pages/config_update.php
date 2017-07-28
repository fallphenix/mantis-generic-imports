<?php

# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license

/**
 * Save Config form for api connexions
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
form_security_validate('plugin_GeneriqueImport_config_update');
auth_reauthenticate();
access_ensure_global_level(plugin_config_get('manage_threshold'));

$f_pooling = gpc_get_int('pooling');
$f_size_page = gpc_get_int('size_page');
$f_url = gpc_get_string('url');
$f_username = gpc_get_string('username');
$f_password = gpc_get_string('password');
$f_type = gpc_get_string('type');

$f_username_api_mantis = gpc_get_string('username_api_mantis');
$f_password_api_mantis = gpc_get_string('password_api_mantis');

//only support for soap and rest
if ($f_type === "REST" || $f_type === "SOAP") {
    plugin_config_set('type', $f_type);
}

plugin_config_set('username', $f_username);
plugin_config_set('password', $f_password);
plugin_config_set('url', $f_url);
plugin_config_set('username_api_mantis', $f_username_api_mantis);
plugin_config_set('password_api_mantis', $f_password_api_mantis);



if ($f_pooling > 0) {
    plugin_config_set('pooling', $f_pooling);
}
if ($f_size_page > 0) {
    plugin_config_set('size_page', $f_size_page);
}

form_security_purge('plugin_GeneriqueImport_config_update');
print_successful_redirect(plugin_page('config', true));
