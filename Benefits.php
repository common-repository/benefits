<?php
/**
 * Plugin Name: Benefits
 * Plugin URI: https://wordpress.org/plugins/benefits/
 * Description: It’s a high quality, native and responsive WordPress plugin to create and view slider-based benefits
 * Version: 6.1.10
 * Author: KestutisIT
 * Author URI: https://profiles.wordpress.org/KestutisIT
 * Text Domain: benefits
 * Domain Path: /Languages
 * License: MIT License. See Legal/License.txt for details.
 */
namespace Benefits;

defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

// Include PHP 5.6 backwards compatibility file
require_once 'php56_compat.php';

// Require missing WordPress core functions
require_once 'formatting.php';

// Require mandatory models
require_once 'Models/Configuration/ConfigurationInterface.php';
require_once 'Models/Routing/RoutingInterface.php';
require_once 'Models/Configuration/Configuration.php';
require_once 'Models/Semver/SemverInterface.php';
require_once 'Models/Semver/Semver.php';
require_once 'Models/Validation/StaticValidator.php';

// Require autoloader and main plugin controller
require_once 'Models/Load/AutoLoad.php';
require_once 'Controllers/MainController.php';

use Benefits\Models\Configuration\Configuration;
use Benefits\Controllers\MainController;

if(!class_exists('Benefits\Benefits'))
{
    final class Benefits
    {
        // Configuration
        const REQUIRED_PHP_VERSION = '5.6.0';
        const REQUIRED_WP_VERSION = 4.6;
        const OLDEST_COMPATIBLE_PLUGIN_SEMVER = '6.0.0';
        const PLUGIN_SEMVER = '6.1.10';

        // Settings
        /**
         * @var array - Plugin settings. We don't use constant here, because it is supported only since PHP 5.6
         */
        private static $params = array(
            'plugin_prefix' => 'benefits_',
            'plugin_handle_prefix' => 'benefits-',
            'plugin_url_prefix' => 'benefits-',
            'plugin_css_prefix' => 'benefits-',
            'theme_ui_folder_name' => 'BenefitsUI', // Folder in your current theme path, that may override plugin’s UI
            'plugin_name' => 'Benefits',
            'shortcode' => 'benefits',
            'text_domain' => 'benefits',
        );

        /**
         * @var Configuration - Conf Without Routing
         */
        private static $objConfiguration = NULL;

        /**
         * @var MainController - Main Controller
         */
        private static $objMainController = NULL;

        private static $uninstallHookRegistered = FALSE;

        /**
         * @return Configuration
         */
        public static function getConfiguration()
        {
            if(is_null(static::$objConfiguration) || !(static::$objConfiguration instanceof Configuration))
            {
                // Create an instance of plugin configuration model
                static::$objConfiguration = new Configuration(
                    $GLOBALS['wpdb'],
                    get_current_blog_id(),
                    static::REQUIRED_PHP_VERSION, phpversion(),
                    static::REQUIRED_WP_VERSION, $GLOBALS['wp_version'],
                    static::OLDEST_COMPATIBLE_PLUGIN_SEMVER, static::PLUGIN_SEMVER,
                    __FILE__,
                    static::$params
                );
            }
            return static::$objConfiguration;
        }

        /**
         * Creates new or returns existing instance of plugin main controller
         * @return MainController
         */
        public static function getMainController()
        {
            if(is_null(static::$objMainController) || !(static::$objMainController instanceof MainController))
            {
                // NOTE: This is not passing by reference!
                static::$objMainController = new MainController(static::getConfiguration());
            }

            return static::$objMainController;
        }

        /**
         * Registers plugin uninstall hook
         * NOTE #1: separated from dynamic objects, because uninstall hook can be called in static context only!
         */
        public static function registerUninstallHook()
        {
            if(static::$uninstallHookRegistered === FALSE)
            {
                static::$uninstallHookRegistered = TRUE;

                register_uninstall_hook(__FILE__, array(__CLASS__, 'uninstall'));
            }
        }

        public static function uninstall()
        {
            // This check allows us to use plugin only in the correct way
            if(static::$uninstallHookRegistered === TRUE)
            {

                static::getMainController()->uninstall();
            }
        }
    }

    // Register static hooks
    Benefits::registerUninstallHook();

    // Run the plugin
    Benefits::getMainController()->run();
}