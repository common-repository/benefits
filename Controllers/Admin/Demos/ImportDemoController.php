<?php
/**
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Controllers\Admin\Demos;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Import\Demo;
use Benefits\Models\Language\LanguageInterface;
use Benefits\Controllers\Admin\AbstractController;
use Benefits\Models\Language\LanguagesObserver;
use Benefits\Models\Cache\StaticSession;

final class ImportDemoController extends AbstractController
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processImportDemo()
    {
        $paramDemoId = isset($_POST['demo_id']) ? $_POST['demo_id'] : 0;

        // Create mandatory instances
        $objDemo = new Demo($this->conf, $this->lang, $paramDemoId);
        $objLanguagesObserver = new LanguagesObserver($this->conf, $this->lang);

        // Delete all existing content and then insert new content
        $objDemo->deleteContent();
        // INFO: This plugin does not use custom post types
        $objDemo->replaceContent();

        // Register newly imported database data for translation
        if($this->lang->canTranslateSQL())
        {
            // If WPML is enabled
            $objLanguagesObserver->registerAllForTranslation();
        }

        // INFO: This plugin does not use custom post types

        StaticSession::cacheHTML_Array('admin_debug_html', $objDemo->getDebugMessages());
        StaticSession::cacheValueArray('admin_okay_message', $objDemo->getOkayMessages());
        StaticSession::cacheValueArray('admin_error_message', $objDemo->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getPluginURL_Prefix().'demos&tab=demos');
        exit;
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function printContent()
    {
        // First - process actions
        if(isset($_POST['import_demo'])) { $this->processImportDemo(); }
    }
}
