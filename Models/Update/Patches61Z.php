<?php
/**
 * Patch class
 *
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models\Update;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\StackInterface;
use Benefits\Models\Language\LanguageInterface;
use Benefits\Models\Validation\StaticValidator;

final class Patches61Z extends AbstractDatabase implements StackInterface, DatabaseInterface, PatchInterface
{
    const CURRENT_MAJOR = 6; // Positive integer [X]
    const CURRENT_MINOR = 1; // Positive integer [Y]
    const LATEST_PATCH = 10; // Positive integer [Z]
    const LATEST_RELEASE = ''; // String
    const LATEST_BUILD_METADATA = ''; // String
    const PLUGIN_PREFIX = "benefits_";

    /**
     * @param ConfigurationInterface $paramConf
     * @param LanguageInterface $paramLang
     * @param int $paramBlogId
     */
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, $paramBlogId)
    {
        parent::__construct($paramConf, $paramLang, $paramBlogId);
    }

    /**
     * SQL for early database altering
     * @return bool
     */
    public function patchDatabaseEarlyStructure()
    {
        // NOTHING HERE YET
        return TRUE;
    }

    /**
     * @return bool
     */
    public function patchData()
    {
        // NOTHING HERE YET
        return TRUE;
    }

    /**
     * SQL for late database altering
     * @return bool
     */
    public function patchDatabaseLateStructure()
    {
        // NOTHING HERE YET
        return TRUE;
    }

    /**
     * NOTE: This method has to be in update class of specific update, because settings table itself,
     *       and it's columns can change over a time as well
     * @return bool
     */
    public function updateDatabaseSemver()
    {
        $updated = FALSE;
        $validBlogId = StaticValidator::getValidPositiveInteger($this->blogId, 0);

        $newSemver = static::CURRENT_MAJOR.'.'.static::CURRENT_MINOR.'.'.static::LATEST_PATCH;
        $newSemver .= static::LATEST_RELEASE != "" ? "-".static::LATEST_RELEASE : "";
        $newSemver .= static::LATEST_BUILD_METADATA != "" ? "+".static::LATEST_BUILD_METADATA : "";

        // Update plugin semver till newest
        $semverUpdated = $this->executeQuery("
            UPDATE `{$this->conf->getPrefix()}settings`
            SET `conf_value`='{$newSemver}'
            WHERE `conf_key` IN ('conf_plugin_semver', 'conf_plugin_version') AND blog_id='{$validBlogId}'
        ");
        // Reset counter back to 0 to say that the new update can start from the first update class query. That will be used in future updates
        $counterReset = $this->executeQuery("
            UPDATE `{$this->conf->getPrefix()}settings`
            SET `conf_value`='0'
            WHERE `conf_key`='conf_updated' AND blog_id='{$validBlogId}'
        ");
        if($semverUpdated !== FALSE && $counterReset !== FALSE)
        {
            $updated = TRUE;
        }

        if($updated === FALSE)
        {
            $this->errorMessages[] = sprintf($this->lang->getText('LANG_DATABASE_UPDATE_SEMANTIC_VERSION_UPDATE_ERROR_TEXT'), $this->blogId);
        } else
        {
            $this->okayMessages[] = sprintf($this->lang->getText('LANG_DATABASE_UPDATE_SEMANTIC_VERSION_UPDATED_TEXT'), $this->blogId, $newSemver);
        }

        return $updated;
    }
}