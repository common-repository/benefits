<?php
/**
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Controllers\Admin\Manual;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Formatting\StaticFormatter;
use Benefits\Models\Language\LanguageInterface;
use Benefits\Controllers\Admin\AbstractController;

final class ManualController extends AbstractController
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function printContent()
    {
        // 1. Set the view variables - Tabs
        $this->view->tabs = StaticFormatter::getTabParams(array(
            'instructions', 'shortcodes', 'shortcode-parameters', 'url-parameters-hashtags', 'ui-overriding'
        ), 'instructions', isset($_GET['tab']) ? $_GET['tab'] : '');

        // Print the template
        $templateRelPathAndFileName = 'Manual'.DIRECTORY_SEPARATOR.'Tabs.php';
        echo $this->view->render($this->conf->getRouting()->getAdminTemplatesPath($templateRelPathAndFileName));
    }
}
