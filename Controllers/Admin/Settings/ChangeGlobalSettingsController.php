<?php
/**
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Controllers\Admin\Settings;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\PostType\ItemPostType;
use Benefits\Models\PostType\DecisionMakerPostType;
use Benefits\Models\PostType\PagePostType;
use Benefits\Models\Cache\StaticSession;
use Benefits\Models\Settings\Setting;
use Benefits\Models\Language\LanguageInterface;
use Benefits\Controllers\Admin\AbstractController;

final class ChangeGlobalSettingsController extends AbstractController
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processSave()
    {
        $key = 'conf_use_sessions';
        $objSetting = new Setting($this->conf, $this->lang, $key);
        $objSetting->saveNumber(isset($_POST[$key]) ? $_POST[$key] : 1, 1, array(0, 1), TRUE);

        $key = 'conf_system_style';
        $objSetting = new Setting($this->conf, $this->lang, $key);
        $objSetting->saveText(isset($_POST[$key]) ? $_POST[$key] : '');

        $key = 'conf_load_font_awesome_from_plugin';
        $objSetting = new Setting($this->conf, $this->lang, $key);
        $objSetting->saveNumber(isset($_POST[$key]) ? $_POST[$key] : 0, 0, array(0, 1), TRUE);

        $key = 'conf_load_slick_slider_from_plugin';
        $objSetting = new Setting($this->conf, $this->lang, $key);
        $objSetting->saveNumber(isset($_POST[$key]) ? $_POST[$key] : 0, 0, array(0, 1), TRUE);

        StaticSession::cacheValueArray('admin_okay_message', array($this->lang->getText('LANG_SETTINGS_GLOBAL_SETTINGS_UPDATED_TEXT')));

        wp_safe_redirect('admin.php?page='.$this->conf->getPluginURL_Prefix().'settings&tab=global-settings');
        exit;
    }

    /**
     * @return array
     */
    public function getSettings()
    {
        $retSettings = array();

        if($this->dbSets->get('conf_use_sessions') == 1)
        {
            $trustedUseSessionsHTML  = '<option value="0">'.$this->lang->escHTML('LANG_NO_TEXT').'</option>'."\n";
            $trustedUseSessionsHTML .= '<option value="1" selected="selected">'.$this->lang->escHTML('LANG_YES_TEXT').'</option>'."\n";
        } else
        {
            $trustedUseSessionsHTML  = '<option value="0" selected="selected">'.$this->lang->escHTML('LANG_NO_TEXT').'</option>'."\n";
            $trustedUseSessionsHTML .= '<option value="1">'.$this->lang->escHTML('LANG_YES_TEXT').'</option>'."\n";
        }
        $retSettings['trusted_use_sessions_html'] = $trustedUseSessionsHTML;


        if($this->dbSets->get('conf_load_font_awesome_from_plugin') == 1)
        {
            $trustedLoadFontAwesomeFromPluginHTML  = '<option value="0">'.$this->lang->escHTML('LANG_SETTING_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $trustedLoadFontAwesomeFromPluginHTML .= '<option value="1" selected="selected">'.$this->lang->escHTML('LANG_SETTING_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        } else
        {
            $trustedLoadFontAwesomeFromPluginHTML  = '<option value="0" selected="selected">'.$this->lang->escHTML('LANG_SETTING_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $trustedLoadFontAwesomeFromPluginHTML .= '<option value="1">'.$this->lang->escHTML('LANG_SETTING_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        }
        $retSettings['trusted_load_font_awesome_from_plugin_html'] = $trustedLoadFontAwesomeFromPluginHTML;


        if($this->dbSets->get('conf_load_slick_slider_from_plugin') == 1)
        {
            $selectLoadSlickSliderFromPlugin  = '<option value="0">'.$this->lang->escHTML('LANG_SETTING_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadSlickSliderFromPlugin .= '<option value="1" selected="selected">'.$this->lang->escHTML('LANG_SETTING_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        } else
        {
            $selectLoadSlickSliderFromPlugin  = '<option value="0" selected="selected">'.$this->lang->escHTML('LANG_SETTING_LOAD_FROM_OTHER_PLACE_TEXT').'</option>'."\n";
            $selectLoadSlickSliderFromPlugin .= '<option value="1">'.$this->lang->escHTML('LANG_SETTING_LOAD_FROM_PLUGIN_TEXT').'</option>'."\n";
        }
        $retSettings['select_load_slick_slider_from_plugin'] = $selectLoadSlickSliderFromPlugin;

        return $retSettings;
    }

    /**
     * @return void
     */
    public function printContent()
    {
        // First - process actions
        if(isset($_POST['update_global_settings'])) { $this->processSave(); }
    }
}
