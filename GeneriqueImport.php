<?php



/**
 * Plugin mantis pour récupérer les incidents à travers un service rest ou soap
 *
 * @author fallphenix1987@gmail.com
 */
class GeneriqueImportPlugin extends MantisPlugin {

    const MANTIS_VERSION = '2.0';

    function register() { 
        $this->name = plugin_lang_get( 'title' );
	$this->description = plugin_lang_get( 'description' );
        $this->page = 'config';           # Default plugin page

        $this->version = '1.0.0';     # Plugin version string
        $this->requires = array(# Plugin dependencies, array of basename => version pairs
            'MantisCore' => self::MANTIS_VERSION,
        );

        $this->author = 'Abdourahmane Fall';
        $this->contact = 'fallphenix1987@gmail.com';
        $this->url = 'https://github.com/fallphenix/mantis-generic-imports.git';
    }
    
    function config() {
        return array(
            'url' => '',
            'username' => '',
            'password' => '',
            'pooling' => 5,
            'type' => "REST",
            'size_page'=>10,            
            'username_api_mantis' => 'administrator',
            'password_api_mantis' => 'root',
        );
    }

     function events() {
        return array(
          //  'EVENT_GENERIC_IMPORT_FOO' => EVENT_TYPE_EXECUTE,
         //   'EVENT_GENERIC_IMPORT_BAR' => EVENT_TYPE_CHAIN,
        );
    }
    
     function hooks() {
        return array(
        // 'EVENT_MENU_MAIN' => 'menu_main',
         'EVENT_MENU_MAIN_FRONT' => 'menu_main',
        );
    }
    
 function menu_main() {
		$t_menu_options = array();

	$t_page = plugin_page( 'list_issues', false, 'GeneriqueImport' );
			//$t_lang = plugin_lang_get( 'repositories', 'Source' );

			$t_menu_option = array(
				'title' => plugin_lang_get( 'issue_menu_title' ),
				'url' => $t_page,
				'access_level' => plugin_config_get( 'view_threshold' ),
				'icon' => 'fa-tree'
			);

			$t_menu_options[] = $t_menu_option;

		return $t_menu_options;
	}
    
     
}
