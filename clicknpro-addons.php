<?php
/*
Plugin Name: ClicknPro Addons
Plugin URI:  https://clicknpro.fr/
Description: This plugin adds custom functionalities to the website.
Version:     1.0
Author:      Click n Pro
Author URI:  https://clicknpro.fr/
License:     GPL2 etc
License URI: 

Copyright YEAR PLUGIN_AUTHOR_NAME (email : your email address)
ClicknPro Addons is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.
 
ClicknPro Addons is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License
along with ClicknPro Addons. If not, see (https://clicknpro.fr/ to your plugin license).
*/
CONST DSP = DIRECTORY_SEPARATOR;
CONST PLUGIN_DIR = '/clicknpro-addons';
CONST PLUGIN_DIR_PATH = WP_PLUGIN_DIR . PLUGIN_DIR;
/**
 * autoload
 *
 * @author Joe Sexton <joe.sexton@bigideas.com>
 * @param  string $class
 * @param  string $dir
 * @return bool
 */
function autoload($dir)
{
    
    foreach (scandir($dir) as $file) {
        
        // directory?
        if (is_dir($dir . DSP . $file) && substr($file, 0, 1) !== '.')
            autoload($dir . DSP . $file . DSP);

        // php file?
        if (substr($file, 0, 2) !== '._' && preg_match("/.php$/i", $file)) {
            
            // filename matches class?
            include $dir . DSP . $file;
            // if (str_replace('.php', '', $file) == $class || str_replace('.class.php', '', $file) == $class) {

                
            // }
        }
    }
}
$folder = dirname(__FILE__) . DSP . 'classes';
autoload($folder);

$clicknProAddons = new ClickNProAddons();
