<?php
# Copyright (c) 2017 abdourahmane fall
# Licensed under the MIT license
require_api( 'access_api.php' );
require_api( 'utility_api.php' );
/**
 * fonctions helpers for listing issues
 *
 * @author abdourahmane fall  fallphenix1987@gmail.com
 */
function getListEnum($name) {
    $t_enum_values = MantisEnum::getValues(config_get($name . "_enum_string"));
    $t_enum_values = array_map(function($enum) use($name) {
        return array(
            "id" => $enum,
            "value" => get_enum_element($name, $enum)
        );
    }, $t_enum_values);
    return $t_enum_values;
}

function findPriorityByName($name, $t_enum_values) {

    $result = array_values(array_filter($t_enum_values, function($value) use($name) {
                return $value["value"] === $name;
            }));

    return (!empty($result)) ? $result[0] : array();
}


//function layout_navbar_button_bar() {
//	if( !auth_is_user_authenticated() ) {
//		return;
//	}
//
//	$t_can_report_bug = access_has_any_project_level( 'report_bug_threshold' );
//	$t_can_invite_user = current_user_is_administrator();
//
//	if( !$t_can_report_bug && !$t_can_invite_user ) {
//		return;
//	}
//
//	echo '<li class="hidden-sm hidden-xs">';
//  	echo '<div class="btn-group btn-corner padding-right-8 padding-left-8">';
//
//  	if( $t_can_report_bug ) {
//		$t_bug_url = string_get_bug_report_url();
//	  	echo '<a class="btn btn-primary btn-sm" href="' . $t_bug_url . '">';
//		echo '<i class="fa fa-edit"></i> ' . lang_get( 'report_bug_link' )."xx";
//		echo '</a>';
//  	}
//
//	if( $t_can_invite_user ) {
//		echo '<a class="btn btn-primary btn-sm" href="manage_user_create_page.php">';
//		echo '<i class="fa fa-user-plus"></i> ' . lang_get( 'invite_users' );
//		echo '</a>';
//	}
//
//	echo '</div>';
//  	echo '</li>';
//}