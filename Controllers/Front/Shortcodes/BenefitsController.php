<?php
/**
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Controllers\Front\Shortcodes;
use Benefits\Controllers\Front\AbstractController;
use Benefits\Models\Benefit\Benefit;
use Benefits\Models\Benefit\BenefitsObserver;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Language\LanguageInterface;

final class BenefitsController extends AbstractController
{
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, $paramArrLimits = array())
    {
        parent::__construct($paramConf, $paramLang, $paramArrLimits);
    }

    /**
     * @param string $paramLayout
     * @param string $paramStyle
     * @return string
     * @throws \Exception
     */
    public function getContent($paramLayout = "Slider", $paramStyle = "")
    {
        // Create mandatory instances
        $objBenefitsObserver = new BenefitsObserver($this->conf, $this->lang, $this->dbSets->getAll());

        $benefitIds = $objBenefitsObserver->getAllIds($this->benefit);
        $benefits = array();
        foreach($benefitIds AS $benefitId)
        {
            $objBenefit = new Benefit($this->conf, $this->lang, $this->dbSets->getAll(), $benefitId);
            $benefits[] = $objBenefit->getDetails();
        }

        // Get the template
        $this->view->benefits = $benefits;
        $retContent = $this->getTemplate('', 'Benefits', $paramLayout, $paramStyle);

        return $retContent;
    }
}