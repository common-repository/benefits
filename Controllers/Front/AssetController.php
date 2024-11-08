<?php
/**
 * Initializer class to load front-end
 * Final class cannot be inherited anymore. We use them when creating new instances
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Controllers\Front;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Style\Style;
use Benefits\Models\Language\LanguageInterface;

final class AssetController
{
    private $conf 	                            = NULL;
    private $lang 		                        = NULL;
    private static $mandatoryPlainJSInitialized = FALSE;

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->lang = $paramLang;
    }

    /**
     * We use this method, because WP_LOCALIZE_SCRIPT does not do the great job,
     * and even the 'l10n_print_after' param is a backward-compatible feature, that has issues of initializing first or second count
     */
    public function enqueueMandatoryPlainJS()
    {
        // Do nothing in this plugin
    }

    public function enqueueMandatoryScripts()
    {
        // Do nothing in this plugin
    }

	public function registerScripts()
	{
        // Register scripts for further use - in file_exists we must use PATH, and in register_script we must use URL

        if(defined('SCRIPT_DEBUG') && SCRIPT_DEBUG)
        {
            // Debug scripts

            // 1. Slick slider
            wp_register_script(
                'slick-slider', $this->conf->getRouting()->get3rdPartyAssetsURL('slick/slick.js'),
                array('jquery')
            );
        } else
        {
            // Regular scripts

            // 1. Slick slider script
            wp_register_script(
                'slick-slider', $this->conf->getRouting()->get3rdPartyAssetsURL('slick/slick.min.js'),
                array('jquery')
            );
        }

        wp_register_script($this->conf->getPluginHandlePrefix().'main', $this->conf->getRouting()->getFrontJS_URL('BenefitsMain.js'), array('jquery'), '1.0', TRUE);
	}

    public function enqueueMandatoryStyles()
    {
        $styleSql = "SELECT conf_value AS conf_system_style
            FROM {$this->conf->getPrefix()}settings
            WHERE conf_key='conf_system_style' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $styleSetting = $this->conf->getInternalWPDB()->get_var($styleSql);
        $styleName = !is_null($styleSetting) ? $styleSetting : '';

        $objStyle = new Style($this->conf, $this->lang, $styleName);
        // Set sitewide styles
        $objStyle->setSitewideStyles();
        // Set compatibility styles
        $objStyle->setCompatibilityStyles();
        $parentThemeCompatibilityCSS_FileURL = $objStyle->getParentThemeCompatibilityCSS_URL();
        $currentThemeCompatibilityCSS_FileURL = $objStyle->getCurrentThemeCompatibilityCSS_URL();
        $sitewideCSS_FileURL = $objStyle->getSitewideCSS_URL();

        if($this->lang->isRTL())
        {
            // Add .rtl body class, then we will able to set different styles for rtl version
            add_filter( 'body_class', function( $classes ) {
                return array_merge( $classes, array( 'rtl' ) );
            } );
        }

        // Register compatibility styles for further use
        if($parentThemeCompatibilityCSS_FileURL != '')
        {
            wp_register_style($this->conf->getPluginURL_Prefix().'parent-theme-front-compatibility', $parentThemeCompatibilityCSS_FileURL);
        }
        if($currentThemeCompatibilityCSS_FileURL != '')
        {
            wp_register_style($this->conf->getPluginURL_Prefix().'current-theme-front-compatibility', $currentThemeCompatibilityCSS_FileURL);
        }

        // Register plugin sitewide style for further use
        if($sitewideCSS_FileURL != '')
        {
            wp_register_style($this->conf->getPluginURL_Prefix().'front-sitewide', $sitewideCSS_FileURL);
        }

        // As these styles are mandatory, enqueue them here
        // Note: Order is important, common stylesheet has to be loaded
        //       AFTER the system style due potentially used CSS4-Variables in the system style file
        wp_enqueue_style($this->conf->getPluginURL_Prefix().'parent-theme-front-compatibility');
        wp_enqueue_style($this->conf->getPluginURL_Prefix().'current-theme-front-compatibility');
        wp_enqueue_style($this->conf->getPluginURL_Prefix().'front-sitewide');
    }

    public function registerStyles()
	{
        $styleSql = "SELECT conf_value AS conf_system_style
            FROM {$this->conf->getPrefix()}settings
            WHERE conf_key='conf_system_style' AND blog_id='{$this->conf->getBlogId()}'
        ";
        $styleSetting = $this->conf->getInternalWPDB()->get_var($styleSql);
        $styleName = !is_null($styleSetting) ? $styleSetting : '';

        $objStyle = new Style($this->conf, $this->lang, $styleName);
        // Set local system styles
        $objStyle->setLocalStyles();

        // Register 3rd party styles for further use (register even it the file is '' - WordPress will process that as needed)
        if(defined('SCRIPT_DEBUG') && SCRIPT_DEBUG)
        {
            // Debug style

            // 1. Font-Awesome styles
            // NOTE: In front-end, Font-Awesome should be always loaded by default from the plugin after install / demo import,
            //       as if we load it from the theme, after theme's update it will fail to keep up with FA version.
            wp_register_style('font-awesome', $this->conf->getRouting()->get3rdPartyAssetsURL('font-awesome/css/font-awesome.css'));
        } else
        {
            // Regular style

            // 1. Font-Awesome styles
            // NOTE: In front-end, Font-Awesome should be always loaded by default from the plugin after install / demo import,
            //       as if we load it from the theme, after theme's update it will fail to keep up with FA version.
            wp_register_style('font-awesome', $this->conf->getRouting()->get3rdPartyAssetsURL('font-awesome/css/font-awesome.min.css'));
        }

        // 2. Slick slider with it's theme
        wp_register_style('slick-slider', $this->conf->getRouting()->get3rdPartyAssetsURL('slick/slick.css'));
        wp_register_style('slick-theme', $this->conf->getRouting()->get3rdPartyAssetsURL('slick/slick-theme.css'));

        // Register plugin local style for further use
        $localCSS_FileURL = $objStyle->getLocalCSS_URL();
        if($localCSS_FileURL != '')
        {
            wp_register_style($this->conf->getPluginHandlePrefix().'main', $localCSS_FileURL);
        }
	}
}