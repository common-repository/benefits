<?php
/**
 * Benefit Element
 *
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models\Benefit;
use Benefits\Models\AbstractStack;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\ElementInterface;
use Benefits\Models\File\StaticFile;
use Benefits\Models\Formatting\StaticFormatter;
use Benefits\Models\StackInterface;
use Benefits\Models\Validation\StaticValidator;
use Benefits\Models\Language\LanguageInterface;

final class Benefit extends AbstractStack implements StackInterface, ElementInterface
{
    private $conf 	                = NULL;
    private $lang 		            = NULL;
    private $debugMode 	            = 0;
    private $benefitId              = 0;
    private $shortTitleMaxLength	= 15;
    private $thumbWidth	            = 71;
    private $thumbHeight		    = 81;

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, array $paramSettings, $paramBenefitId)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->lang = $paramLang;
        $this->benefitId = StaticValidator::getValidValue($paramBenefitId, 'positive_integer', 0);

        if(isset($paramSettings['conf_short_benefit_title_max_length']))
        {
            // Set short title max length
            $this->shortTitleMaxLength = StaticValidator::getValidPositiveInteger($paramSettings['conf_short_benefit_title_max_length'], 26);
        }

        if(isset($paramSettings['conf_benefit_thumb_w'], $paramSettings['conf_benefit_thumb_h']))
        {
            // Set image dimensions
            $this->thumbWidth = StaticValidator::getValidPositiveInteger($paramSettings['conf_benefit_thumb_w'], 71);
            $this->thumbHeight = StaticValidator::getValidPositiveInteger($paramSettings['conf_benefit_thumb_h'], 81);
        }
    }

    private function getDataFromDatabaseById($paramBenefitId, $paramColumns = array('*'))
    {
        $validBenefitId = StaticValidator::getValidPositiveInteger($paramBenefitId, 0);
        $validSelect = StaticValidator::getValidSelect($paramColumns);

        $sqlQuery = "
            SELECT {$validSelect}
            FROM {$this->conf->getPrefix()}benefits
            WHERE benefit_id='{$validBenefitId}'
        ";
        $retData = $this->conf->getInternalWPDB()->get_row($sqlQuery, ARRAY_A);

        return $retData;
    }

    public function getId()
    {
        return $this->benefitId;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    /**
     * @note Do not translate title here - it is used for editing
     * @param bool $paramIncludeUnclassified - not used
     * @return mixed
     */
    public function getDetails($paramIncludeUnclassified = FALSE)
    {
        $ret = $this->getDataFromDatabaseById($this->benefitId);
        if(!is_null($ret))
        {
            // Make raw
            $ret['benefit_title'] = stripslashes($ret['benefit_title']);
            $ret['benefit_image'] = stripslashes($ret['benefit_image']);
            $ret['benefit_description'] = stripslashes($ret['benefit_description']);

            // Retrieve translation
            $ret['translated_benefit_title'] = $this->lang->getTranslated("be{$ret['benefit_id']}_benefit_title", $ret['benefit_title']);
            $ret['translated_short_benefit_title'] = StaticFormatter::getTruncated($ret['translated_benefit_title'], $this->shortTitleMaxLength);
            $ret['translated_benefit_description'] = $this->lang->getTranslated("be{$ret['benefit_id']}_benefit_description", $ret['benefit_description']);

            // Note: providing exact file name is important here, because then the system will correctly decide
            //       from which exact folder to load that file, as not all demo images may be overridden by the theme
            if($ret['demo_benefit_image'] == 1)
            {
                $imageFolder = $this->conf->getRouting()->getDemoGalleryURL($ret['benefit_image'], FALSE);
            } else
            {
                $imageFolder = $this->conf->getGlobalGalleryURL();
            }

            // Extend with additional rows
            $ret['short_benefit_title'] = StaticFormatter::getTruncated($ret['benefit_title'], $this->shortTitleMaxLength);
            $ret['benefit_thumb_url'] = $ret['benefit_image'] != "" ? $imageFolder."thumb_".$ret['benefit_image'] : "";
            $ret['benefit_image_url'] = $ret['benefit_image'] != "" ? $imageFolder.$ret['benefit_image'] : "";
            $ret['dynamic_benefit_caption'] = $ret['benefit_title'] != "" ? $ret['benefit_title'] : $ret['benefit_description'];
            $ret['translated_dynamic_benefit_caption'] = $ret['translated_benefit_title'] != "" ? $ret['translated_benefit_title'] : $ret['translated_benefit_description'];
        }

        return $ret;
    }

    /**
     * @param array $params
     * @return false|int
     */
    public function save(array $params)
    {
        $saved = FALSE;
        $ok = TRUE;
        $validBenefitId = StaticValidator::getValidPositiveInteger($this->benefitId, 0);
        $validBenefitTitle = isset($params['benefit_title']) ? esc_sql(sanitize_text_field($params['benefit_title'])) : ''; // for sql query only
        $validBenefitDescription = isset($params['benefit_description']) ? esc_sql(implode("\n", array_map('sanitize_text_field', explode("\n", $params['benefit_description'])))) : ''; // for sql query only

        if(isset($params['benefit_order']) && StaticValidator::isPositiveInteger($params['benefit_order']))
        {
            $validBenefitOrder = StaticValidator::getValidPositiveInteger($params['benefit_order'], 1);
        } else
        {
            // SELECT MAX
            $sqlQuery = "
                SELECT MAX(benefit_order) AS max_order
                FROM {$this->conf->getPrefix()}benefits
                WHERE 1
            ";
            $maxOrderResult = $this->conf->getInternalWPDB()->get_var($sqlQuery);
            $validBenefitOrder = !is_null($maxOrderResult) ? intval($maxOrderResult)+1 : 1;
        }

        if($validBenefitTitle == "" && $validBenefitDescription == "")
        {
            // Either benefit title or description is required
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('LANG_BENEFIT_DEAL_TITLE_OR_DESCRIPTION_REQUIRED_ERROR_TEXT');
        }

        // Search for existing benefit title, if it is not blank
        $benefitTitleExistsQuery = "
            SELECT benefit_id
            FROM {$this->conf->getPrefix()}benefits
            WHERE benefit_id!={$validBenefitId} AND benefit_title='{$validBenefitTitle}' AND benefit_title!=''
            AND blog_id='{$this->conf->getBlogId()}'
        ";
        $benefitTitleExists = $this->conf->getInternalWPDB()->get_var($benefitTitleExistsQuery);
        if(!is_null($benefitTitleExists))
        {
            $ok = FALSE;
            $this->errorMessages[] = $this->lang->getText('LANG_BENEFIT_TITLE_EXISTS_ERROR_TEXT');
        }

        if($validBenefitId > 0 && $ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                UPDATE {$this->conf->getPrefix()}benefits SET
                benefit_title='{$validBenefitTitle}', benefit_description='{$validBenefitDescription}',
                benefit_order='{$validBenefitOrder}'
                WHERE benefit_id='{$validBenefitId}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            // Only if there is error in query we will skip that, if no changes were made (and 0 was returned) we will still process
            if($saved !== FALSE)
            {
                $benefitEditData = $this->conf->getInternalWPDB()->get_row("
                    SELECT *
                    FROM {$this->conf->getPrefix()}benefits
                    WHERE benefit_id='{$validBenefitId}' AND blog_id='{$this->conf->getBlogId()}'
                ", ARRAY_A);

                // Upload image
                if(
                    isset($params['delete_benefit_image']) && $benefitEditData['benefit_image'] != "" &&
                    $benefitEditData['demo_benefit_image'] == 0
                ) {
                    // Unlink files only if it's not a demo image
                    unlink($this->conf->getGlobalGalleryPath().$benefitEditData['benefit_image']);
                    unlink($this->conf->getGlobalGalleryPath()."thumb_".$benefitEditData['benefit_image']);
                }

                $validUploadedImageFileName = '';
                if($_FILES['benefit_image']['tmp_name'] != '')
                {
                    $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['benefit_image'], $this->conf->getGlobalGalleryPathWithoutEndSlash(), "benefit_");
                    StaticFile::makeThumbnail($this->conf->getGlobalGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                    $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                }

                if($validUploadedImageFileName != '' || isset($params['delete_benefit_image']))
                {
                    // Update the sql
                    $this->conf->getInternalWPDB()->query("
                        UPDATE {$this->conf->getPrefix()}benefits SET
                        benefit_image='{$validUploadedImageFileName}', demo_benefit_image='0'
                        WHERE benefit_id='{$validBenefitId}' AND blog_id='{$this->conf->getBlogId()}'
                    ");
                }
            }

            if($saved === FALSE)
            {
                $this->errorMessages[] = $this->lang->getText('LANG_BENEFIT_UPDATE_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('LANG_BENEFIT_UPDATED_TEXT');
            }
        } else if($ok)
        {
            $saved = $this->conf->getInternalWPDB()->query("
                INSERT INTO {$this->conf->getPrefix()}benefits
                (
                    benefit_title, benefit_description,
                    benefit_order, blog_id
                ) VALUES
                (
                    '{$validBenefitTitle}', '{$validBenefitDescription}',
                    '{$validBenefitOrder}', '{$this->conf->getBlogId()}'
                )
            ");

            // We will process only if there one line was added to sql
            if($saved)
            {
                // Get newly inserted benefit id
                $validInsertedNewBenefitId = $this->conf->getInternalWPDB()->insert_id;

                // Update the core benefit id for future use
                $this->benefitId = $validInsertedNewBenefitId;

                $validUploadedImageFileName = '';
                if($_FILES['benefit_image']['tmp_name'] != '')
                {
                    $uploadedImageFileName = StaticFile::uploadImageFile($_FILES['benefit_image'], $this->conf->getGlobalGalleryPathWithoutEndSlash(), "benefit_");
                    StaticFile::makeThumbnail($this->conf->getGlobalGalleryPath(), $uploadedImageFileName, $this->thumbWidth, $this->thumbHeight, "thumb_");
                    $validUploadedImageFileName = esc_sql(sanitize_file_name($uploadedImageFileName)); // for sql query only
                }

                if($validUploadedImageFileName != '')
                {
                    // Update the sql
                    $this->conf->getInternalWPDB()->query("
                        UPDATE {$this->conf->getPrefix()}benefits SET
                        benefit_image='{$validUploadedImageFileName}', demo_benefit_image='0'
                        WHERE benefit_id='{$validInsertedNewBenefitId}' AND blog_id='{$this->conf->getBlogId()}'
                    ");
                }
            }

            if($saved === FALSE || $saved === 0)
            {
                $this->errorMessages[] = $this->lang->getText('LANG_BENEFIT_INSERTION_ERROR_TEXT');
            } else
            {
                $this->okayMessages[] = $this->lang->getText('LANG_BENEFIT_INSERTED_TEXT');
            }
        }

        return $saved;
    }

    public function registerForTranslation()
    {
        $benefitDetails = $this->getDetails();
        if(!is_null($benefitDetails))
        {
            $this->lang->register("be{$this->benefitId}_benefit_title", $benefitDetails['benefit_title']);
            $this->lang->register("be{$this->benefitId}_benefit_description", $benefitDetails['benefit_description']);
            $this->okayMessages[] = $this->lang->getText('LANG_BENEFIT_REGISTERED_TEXT');
        }
    }

    /**
     * @return false|int
     */
    public function delete()
    {
        $deleted = FALSE;
        $benefitDetails = $this->getDetails();
        if(!is_null($benefitDetails))
        {
            $deleted = $this->conf->getInternalWPDB()->query("
                DELETE FROM {$this->conf->getPrefix()}benefits
                WHERE benefit_id='{$benefitDetails['benefit_id']}' AND blog_id='{$this->conf->getBlogId()}'
            ");

            if($deleted)
            {
                // Unlink image file
                if($benefitDetails['demo_benefit_image'] == 0 && $benefitDetails['benefit_image'] != "")
                {
                    unlink($this->conf->getGlobalGalleryPath().$benefitDetails['benefit_image']);
                    unlink($this->conf->getGlobalGalleryPath()."thumb_".$benefitDetails['benefit_image']);
                }
            }
        }

        if($deleted === FALSE || $deleted === 0)
        {
            $this->errorMessages[] = $this->lang->getText('LANG_BENEFIT_DELETION_ERROR_TEXT');
        } else
        {
            $this->okayMessages[] = $this->lang->getText('LANG_BENEFIT_DELETED_TEXT');
        }

        return $deleted;
    }
}