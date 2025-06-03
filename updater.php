<?php

/**
 * Updates plugins on website without dashboard for security reasons
 * Requires WP-CLI to be installed and configured
 */
	
	
	
	if ($argc < 2) {
		echo "Usage: wordpress-plugin-site-updater.php <path_to_wordpress_site_folder>\n";
		echo "Usage Example: php wordpress-plugin-site-updater.php /DEVEL_WEB_STORAGE_UBUNTU/{username}/{sitename}\n";
		exit(1); 
	}	
	
	$wp_path = $argv[1]; // argument specifying the path pointing to the site folder path
	$plugin_path = "$wp_path/wp-content/plugins"; // slice double slash
	
	shell_exec("sudo chmod -R 744 $wp_path/*");	
	
	// checking if path exists
	if (!is_dir($wp_path)) {
		echo "Error: Wordpress path does not exist at : $wp_path\n\n";
		exit(1);
	}
	
	$upgrade_temp_backup_path = "$wp_path/wp-content/upgrade-temp-backup"; 
	
	
	shell_exec("sudo chmod 777 $upgrade_temp_backup_path");	
	

	shell_exec("sudo chmod -R 777 $plugin_path");
	shell_exec("sudo chown -R charoula: $wp_path/*");
	shell_exec("sudo chmod 777 $plugin_path");
//	shell_exec("sudo chmod 777 $upgrade_temp_backup_path");
	
	//if (!is_dir($upgrade_temp_backup_path)) { 
	//	if (!mkdir($upgrade_temp_backup_path, 0777, true)) { 
			echo "Error: Could not create directory: $upgrade_temp_backup_path\n"; 
	//		exit(1); 
	//	} 
	//}
	chdir($wp_path);
	
	echo "-- WORDPRESS INFORMATION --:\n";
	echo "\n";
	$wp_info_output = shell_exec("wp --allow-root --path=$wp_path --info");
	echo "\n<pre>\n".$wp_info_output."</pre>";
	echo "\n\n";
	$current_plugin_list = shell_exec("wp --allow-root --path=$wp_path plugin list");
	echo "\n<pre>\n".$current_plugin_list."</pre>\n\n";
	echo "Entering mode=MAINTENANCE...\n";
	$wp_activate_maintenance = shell_exec("wp --allow-root --path=$wp_path plugin activate maintenance");
	echo "\n<pre>\n".$wp_activate_maintenance."</pre>";
	
	// updating plugins
	$wp_update_plugins = shell_exec("wp --allow-root plugin update --all --exclude=motopress-content-editor");	
	echo "\n<pre>\n".$wp_update_plugins."</pre>\n";
	
	// updating the wordpress site version
	$wp_update_site_version = shell_exec("wp --allow-root core update");
	echo "\n<pre>\n".$wp_update_site_version."</pre>\n";	

	if (strpos($wp_update_plugins, 'Error:') !== false) {
    		echo "Plugin update FAILED\n";
    		exit(1);
	} else {
   		 echo "Plugin update SUCCESSFUL\n";
	}
	echo "\n";
	if (strpos($wp_update_site_version, 'Error:') !== false) {
		echo "Wordpress site version update FAILED\n";
		exit(1);
	} else {
		echo "Wordpress site version update SUCCESS\n";
	}

	echo "\n\nExiting mode=MAINTENANCE...\n";
	$wp_deactivate_maintenance = shell_exec("wp --allow-root --path=$wp_path plugin deactivate maintenance");
	echo "\n<pre>\n".$wp_deactivate_maintenance."</pre>";

	echo "\n\nPROCESS FINISHED...\n";
	
	shell_exec("sudo chown -R www-data: $wp_path/*");
	// shell_exec("sudo chown -R www-data: $plugin_path");
	//shell_exec("sudo chmod -R 544 $plugin_path");
	//shell_exec("sudo chmod 544 $plugin_path");
	//shell_exec("sudo chmod 544 $upgrade_temp_backup_path");
        shell_exec("sudo find $wp_path -type d -exec chmod 555 {} \;");
        shell_exec("sudo find $wp_path -type f -exec chmod 444 {} \;");
	// shell_exec("sudo chmod -R 544 $wp_path/*");
?>
