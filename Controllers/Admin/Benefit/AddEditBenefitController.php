<?php
/**
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Controllers\Admin\Benefit;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Benefit\Benefit;
use Benefits\Models\Language\LanguageInterface;
use Benefits\Controllers\Admin\AbstractController;
use Benefits\Models\Cache\StaticSession;

final class AddEditBenefitController extends AbstractController
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        parent::__construct($paramConf, $paramLang);
    }

    private function processDelete($paramBenefitId)
    {
        $objBenefit = new Benefit($this->conf, $this->lang, $this->dbSets->getAll(), $paramBenefitId);
        $objBenefit->delete();

        StaticSession::cacheHTML_Array('admin_debug_html', $objBenefit->getDebugMessages());
        StaticSession::cacheValueArray('admin_okay_message', $objBenefit->getOkayMessages());
        StaticSession::cacheValueArray('admin_error_message', $objBenefit->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getPluginURL_Prefix().'benefit-manager&tab=benefits');
        exit;
    }

    private function processSave($paramBenefitId)
    {
        // Create mandatory instances
        $objBenefit = new Benefit($this->conf, $this->lang, $this->dbSets->getAll(), $paramBenefitId);

        $saved = $objBenefit->save($_POST);
        if($saved && $this->lang->canTranslateSQL())
        {
            $objBenefit->registerForTranslation();
        }

        StaticSession::cacheHTML_Array('admin_debug_html', $objBenefit->getDebugMessages());
        StaticSession::cacheValueArray('admin_okay_message', $objBenefit->getOkayMessages());
        StaticSession::cacheValueArray('admin_error_message', $objBenefit->getErrorMessages());

        wp_safe_redirect('admin.php?page='.$this->conf->getPluginURL_Prefix().'benefit-manager&tab=benefits');
        exit;
    }

    /**
     * @throws \Exception
     * @return void
     */
    public function printContent()
    {
        // Process actions
        if(isset($_GET['delete_benefit'])) { $this->processDelete($_GET['delete_benefit']); }
        if(isset($_POST['save_benefit'], $_POST['benefit_id'])) { $this->processSave($_POST['benefit_id']); }

        $paramBenefitId = isset($_GET['benefit_id']) ? $_GET['benefit_id'] : 0;
        $objBenefit = new Benefit($this->conf, $this->lang, $this->dbSets->getAll(), $paramBenefitId);
        $localDetails = $objBenefit->getDetails();

        // Set the view variables
        $this->view->backToListURL = admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'benefit-manager&tab=benefits');
        $this->view->formAction = admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'add-edit-benefit&noheader=true');
        if(!is_null($localDetails))
        {
            $this->view->benefitId = $localDetails['benefit_id'];
            $this->view->benefitTitle = $localDetails['benefit_title'];
            $this->view->benefitImageURL = $localDetails['benefit_image_url'];
            $this->view->demoBenefitImage = $localDetails['demo_benefit_image'];
            $this->view->benefitDescription = $localDetails['benefit_description'];
            $this->view->benefitOrder = $localDetails['benefit_order'];
        } else
        {
            $this->view->benefitId = 0;
            $this->view->benefitTitle = '';
            $this->view->benefitImageURL = '';
            $this->view->demoBenefitImage = 0;
            $this->view->benefitDescription = '';
            $this->view->benefitOrder = '';
        }

        // Print the template
        $templateRelPathAndFileName = 'Benefit'.DIRECTORY_SEPARATOR.'AddEditBenefitForm.php';
        echo $this->view->render($this->conf->getRouting()->getAdminTemplatesPath($templateRelPathAndFileName));
    }
}
