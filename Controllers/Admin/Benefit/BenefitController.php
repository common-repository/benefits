<?php
/**
 * @package Benefits
 * @note Variables prefixed with 'local' are not used in templates
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Controllers\Admin\Benefit;
use Benefits\Controllers\Admin\AbstractController;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Benefit\BenefitsObserver;
use Benefits\Models\Formatting\StaticFormatter;
use Benefits\Models\Language\LanguageInterface;

final class BenefitController extends AbstractController
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
        // Create mandatory instances
        $objBenefitsObserver = new BenefitsObserver($this->conf, $this->lang, $this->dbSets->getAll());

        // 1. Set the view variables - Tabs
        $this->view->tabs = StaticFormatter::getTabParams(
            array('benefits'), 'benefits', isset($_GET['tab']) ? $_GET['tab'] : ''
        );

        // 2. Set the view variables - benefits tab
        $this->view->addNewBenefitURL = admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'add-edit-benefit&benefit_id=0');
        $this->view->trustedAdminBenefitListHTML = $objBenefitsObserver->getTrustedAdminListHTML();

        // Print the template
        $templateRelPathAndFileName = 'Benefit'.DIRECTORY_SEPARATOR.'ManagerTabs.php';
        echo $this->view->render($this->conf->getRouting()->getAdminTemplatesPath($templateRelPathAndFileName));
    }
}
