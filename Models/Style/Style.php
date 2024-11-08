<?php
/**
 * Style class to handle visual view

 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models\Style;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\File\StaticFile;
use Benefits\Models\Language\LanguageInterface;

final class Style implements StyleInterface
{
    private $conf                 = NULL;
    private $lang                 = NULL;
    private $debugMode            = 0; // 0 - disabled, 1 - regular debug, 2+ - deep debug
    private $styleName            = "";
    private $sitewideStyles       = array();
    private $compatibilityStyles  = array();
    private $localStyles          = array();

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, $paramSystemStyle)
    {
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->conf = $paramConf;
        $this->lang = $paramLang;

        // Set style name
        $this->styleName = sanitize_text_field($paramSystemStyle);
    }

    public function setSitewideStyles()
    {
        $cssFolderPath = $this->conf->getRouting()->getFrontSitewideCSS_Path('', FALSE);
        $cssFolderURL = $this->conf->getRouting()->getFrontSitewideCSS_URL('', FALSE);

        $this->sitewideStyles = array();
        $cssFiles = StaticFile::getFolderFileList($cssFolderPath, array("css"));
        foreach($cssFiles AS $cssFile)
        {
            $cssTemplateData = get_file_data($cssFolderPath.$cssFile, array('StyleName' => 'Style Name'));
            $this->sitewideStyles[] = array(
                "style_name" => sanitize_text_field($cssTemplateData['StyleName']),
                "file_path" => $cssFolderPath,
                "file_name" => sanitize_text_field($cssFile),
                "file_url" => $cssFolderURL.sanitize_text_field($cssFile),
            );
        }

        if($this->debugMode >= 2)
        {
            echo "<br /><br />---------------------------------------------------------------------------------";
            echo "<br /><strong>[setSitewideStyles()]</strong> \$cssFolderPath: {$cssFolderPath}</strong>";
            echo "<br /><strong>[setSitewideStyles()]</strong> \$cssFolderURL: {$cssFolderURL}</strong>";
            echo "<br /><strong>[setSitewideStyles()]</strong> CSS FILES:<br />".var_export($cssFiles, TRUE);
            echo "<br /><br /><strong>[setSitewideStyles()]</strong> SITEWIDE STYLES: ".nl2br(print_r($this->sitewideStyles, TRUE));
            echo "<br />---------------------------------------------------------------------------------";
        }
    }

    public function setCompatibilityStyles()
    {
        $cssFolderPath = $this->conf->getRouting()->getFrontCompatibilityCSS_Path('', FALSE);
        $cssFolderURL = $this->conf->getRouting()->getFrontCompatibilityCSS_URL('', FALSE);

        $this->compatibilityStyles = array();
        $cssFiles = StaticFile::getFolderFileList($cssFolderPath, array("css"));
        foreach($cssFiles AS $cssFile)
        {
            $cssTemplateData = get_file_data($cssFolderPath.$cssFile, array('ThemeName' => 'Theme Name'));
            $this->compatibilityStyles[] = array(
                "theme_name" => sanitize_text_field($cssTemplateData['ThemeName']),
                "file_path" => $cssFolderPath,
                "file_name" => sanitize_text_field($cssFile),
                "file_url" => $cssFolderURL.sanitize_text_field($cssFile),
            );
        }

        if($this->debugMode >= 2)
        {
            echo "<br /><br />---------------------------------------------------------------------------------";
            echo "<br /><strong>[setCompatibilityStyles()]</strong> \$cssFolderPath: {$cssFolderPath}</strong>";
            echo "<br /><strong>[setCompatibilityStyles()]</strong> \$cssFolderURL: {$cssFolderURL}</strong>";
            echo "<br /><strong>[setCompatibilityStyles()]</strong> CSS FILES:<br />".var_export($cssFiles, TRUE);
            echo "<br /><strong>[setCompatibilityStyles()]</strong> COMPATIBILITY STYLES: ".nl2br(print_r($this->compatibilityStyles, TRUE));
            echo "<br />---------------------------------------------------------------------------------";
        }
    }

    public function setLocalStyles()
    {
        $cssFolderPath = $this->conf->getRouting()->getFrontLocalCSS_Path('', FALSE);
        $cssFolderURL = $this->conf->getRouting()->getFrontLocalCSS_URL('', FALSE);

        $this->localStyles = array();
        $cssFiles = StaticFile::getFolderFileList($cssFolderPath, array("css"));
        foreach($cssFiles AS $cssFile)
        {
            // Case-insensitive check
            $cssTemplateData = get_file_data($cssFolderPath.$cssFile, array('StyleName' => 'Style Name'));
            $this->localStyles[] = array(
                "style_name" => sanitize_text_field($cssTemplateData['StyleName']),
                "file_path" => $cssFolderPath,
                "file_name" => sanitize_text_field($cssFile),
                "file_url" => $cssFolderURL.sanitize_text_field($cssFile),
            );
        }

        if($this->debugMode >= 2)
        {
            echo "<br /><br />---------------------------------------------------------------------------------";
            echo "<br /><strong>[setLocalStyles()]</strong> \$cssFolderPath: {$cssFolderPath}</strong>";
            echo "<br /><strong>[setLocalStyles()]</strong> \$cssFolderURL: {$cssFolderURL}</strong>";
            echo "<br /><strong>[setLocalStyles()]</strong> CSS FILES:<br />".var_export($cssFiles, TRUE);
            echo "<br /><br /><strong>[setLocalStyles()]</strong> LOCAL STYLES: ".nl2br(print_r($this->localStyles, TRUE));
            echo "<br />---------------------------------------------------------------------------------";
        }
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getParentThemeCompatibilityCSS_URL()
    {
        // Get parent theme name
        $parentThemeName = "";
        $objParentTheme = wp_get_theme(get_template());
        $objCurrentTheme = wp_get_theme();
        if(!is_null($objParentTheme) && !is_null($objCurrentTheme))
        {
            $parentThemeName = $objParentTheme->get('Name') != $objCurrentTheme->get('Name') ? $objParentTheme->get('Name') : '';
        }

        // Get the stylesheet file and it's path
        $compatibilityFileURL = '';
        foreach($this->compatibilityStyles AS $theme)
        {
            if($theme['theme_name'] == $parentThemeName && $theme['file_name'] != '' && $parentThemeName != '')
            {
                $compatibilityFileURL = $theme['file_url'];
            }
        }

        if($this->debugMode)
        {
            echo "<br />PARENT THEME NAME: {$parentThemeName}";
            echo "<br />PARENT THEME COMPATIBILITY CSS FILE URL: ".$compatibilityFileURL;
        }

        return $compatibilityFileURL;
    }

    public function getCurrentThemeCompatibilityCSS_URL()
    {
        // Get current theme name
        $currentThemeName = "";
        $objCurrentTheme = wp_get_theme();
        if(!is_null($objCurrentTheme))
        {
            $currentThemeName = $objCurrentTheme->get('Name');
        }

        // Get the stylesheet file and it's path
        $compatibilityFileURL = '';
        foreach($this->compatibilityStyles AS $theme)
        {
            if($theme['theme_name'] == $currentThemeName && $theme['file_name'] != '')
            {
                $compatibilityFileURL = $theme['file_url'];
            }
        }

        if($this->debugMode)
        {
            echo "<br />CURRENT THEME NAME: {$currentThemeName}";
            echo "<br />CURRENT THEME COMPATIBILITY CSS FILE URL: ".$compatibilityFileURL;
        }

        return $compatibilityFileURL;
    }

    public function getSitewideCSS_URL()
    {
        // Get the stylesheet file and it's path
        $selectedFileURL = '';
        $defaultFileURL = '';
        foreach($this->sitewideStyles AS $style)
        {
            if($defaultFileURL == '' && $style['file_name'] != '')
            {
                $defaultFileURL = $style['file_url'];
            }
            if($style['style_name'] == $this->styleName && $style['file_name'] != '')
            {
                $selectedFileURL = $style['file_url'];
            }
        }

        // If selected style not exist, then select the last available file
        $fileURL = $selectedFileURL != '' ? $selectedFileURL : $defaultFileURL;

        if($this->debugMode)
        {
            echo "<br />SELECTED SITEWIDE STYLE FILE URL: {$selectedFileURL}";
            echo "<br />DEFAULT SITEWIDE STYLE FILE URL: {$defaultFileURL}";
            echo "<br />SITEWIDE STYLE FILE URL: {$fileURL}";
        }

        return $fileURL;
    }

    public function getLocalCSS_URL()
    {
        // Get the stylesheet file and it's path
        $selectedFileURL = '';
        $defaultFileURL = '';
        foreach($this->localStyles AS $style)
        {
            if($defaultFileURL == '' && $style['file_name'] != '')
            {
                $defaultFileURL = $style['file_url'];
            }
            if($style['style_name'] == $this->styleName && $style['file_name'] != '')
            {
                $selectedFileURL = $style['file_url'];
            }
        }

        // If selected style not exist, then select the last available file
        $fileURL = $selectedFileURL != '' ? $selectedFileURL : $defaultFileURL;

        if($this->debugMode)
        {
            echo "<br />SELECTED LOCAL STYLE FILE URL: {$selectedFileURL}";
            echo "<br />DEFAULT LOCAL STYLE FILE URL: {$defaultFileURL}";
            echo "<br />LOCAL STYLE FILE URL: {$fileURL}";
        }

        return $fileURL;
    }
}