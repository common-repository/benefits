<?php
/**
 * Benefits Observer

 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models\Benefit;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\ObserverInterface;
use Benefits\Models\Language\LanguageInterface;
use Benefits\Models\Validation\StaticValidator;

final class BenefitsObserver implements ObserverInterface
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $settings		        = array();
    private $debugMode 	            = 0;

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, array $paramSettings)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->lang = $paramLang;
        // Set saved settings
        $this->settings = $paramSettings;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getAllIds($paramBenefitId = -1)
    {
        $validBenefitId = StaticValidator::getValidInteger($paramBenefitId, -1); // -1 means 'skip'

        $sqlAdd = '';
        if($validBenefitId > 0)
        {
            // Benefit id
            $sqlAdd .= " AND benefit_id='{$validBenefitId}'";
        }

        $searchSQL = "
            SELECT benefit_id
            FROM {$this->conf->getPrefix()}benefits
            WHERE blog_id='{$this->conf->getBlogId()}'{$sqlAdd}
            ORDER BY benefit_order ASC, benefit_id ASC
		";

        //DEBUG
        //echo nl2br($searchSQL)."<br /><br />";

        $searchResult = $this->conf->getInternalWPDB()->get_col($searchSQL);

        return $searchResult;
    }


    /* --------------------------------------------------------------------------- */
    /* ----------------------- METHODS FOR ADMIN ACCESS ONLY --------------------- */
    /* --------------------------------------------------------------------------- */

    public function getTrustedAdminListHTML()
    {
        $retHTML = '';
        $benefitIds = $this->getAllIds();
        foreach ($benefitIds AS $benefitId)
        {
            $objBenefit = new Benefit($this->conf, $this->lang, $this->settings, $benefitId);
            $benefitDetails = $objBenefit->getDetails();

            // Benefit title HTML
            $benefitTitleHMTL = esc_html($benefitDetails['translated_benefit_title']);
            if($this->lang->canTranslateSQL())
            {
                $benefitTitleHMTL .= '<br />-------------------------------<br />';
                $benefitTitleHMTL .= '<span class="not-translated" title="'.$this->lang->escAttr('LANG_WITHOUT_TRANSLATION_TEXT').'">('.esc_html($benefitDetails['benefit_title']).')</span>';
            }

            // Benefit description HTML
            $benefitDescriptionHTML = esc_br_html($benefitDetails['translated_benefit_description']);
            if($this->lang->canTranslateSQL())
            {
                $benefitDescriptionHTML .= '<br />-------------------------------<br />';
                $benefitDescriptionHTML .= '<span class="not-translated" title="'.$this->lang->escAttr('LANG_WITHOUT_TRANSLATION_TEXT').'">('.esc_br_html($benefitDetails['benefit_description']).')</span>';
            }

            // HTML
            $retHTML .= '<tr>';
            $retHTML .= '<td>'.esc_html($benefitId).'</td>';
            $retHTML .= '<td>'.$benefitTitleHMTL.'</td>';
            $retHTML .= '<td>'.$benefitDescriptionHTML.'</td>';
            $retHTML .= '<td style="text-align: center">'.esc_html($benefitDetails['benefit_order']).'</td>';
            $retHTML .= '<td align="right">';
            if(current_user_can('manage_'.$this->conf->getPluginPrefix().'all_benefits'))
            {
                $retHTML .= '<a href="'.admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'add-edit-benefit&amp;benefit_id='.$benefitId).'">'.$this->lang->escHTML('LANG_EDIT_TEXT').'</a>';
                $retHTML .= ' - ';
                $retHTML .= '<a href="javascript:;" onclick="javascript:BenefitsAdmin.deleteBenefit(\''.$benefitId.'\')">'.$this->lang->escHTML('LANG_DELETE_TEXT').'</a>';
            } else
            {
                $retHTML .= '--';
            }
            $retHTML .= '</td>';
            $retHTML .= '</tr>';
        }

        return  $retHTML;
    }
}