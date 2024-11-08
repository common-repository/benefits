<?php
/**
 * Status must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models\Status;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Language\LanguageInterface;

interface SingleStatusInterface
{
    /**
     * @param ConfigurationInterface $paramConf
     * @param LanguageInterface $paramLang
     * @param int $paramBlogId
     */
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, $paramBlogId);
    public function getId();
    public function inDebug();

    /**
     * Get additional links to show in local plugins manager
     * @return array
     */
    public function getActionLinks();

    /**
     * Additional links to show in next to local plugin description
     * @return array
     */
    public function getInfoLinks();

    /**
     * @note1 - This function maintains backwards compatibility to SMVC 6.0.0 and newer
     * @note2 - This function says if there are plugin struct of required semver
     * @param string $paramRequiredPluginSemver
     * @return bool
     */
    public function checkPluginDB_StructExistsOf($paramRequiredPluginSemver);

    /**
     * Differently to "Exists of semver" class method, this class method is based
     * on existence of compatible data
     *
     * @note1 - This function maintains backwards compatibility to SMVC 6.0.0 and newer
     * @note2 - This function says if the data exists for of required semver
     * @return bool
     */
    public function checkPluginCompatibleDataExists();

    /**
     * @note1 - This function maintains backwards compatibility to SMVC 6.0.0 and newer
     * @note2 - This function says if there data exists for at least one extension
     * @param string $paramRequiredPluginSemver
     * @return bool
     */
    public function checkPluginDataExistsOf($paramRequiredPluginSemver);

    /**
     * @note - This function maintains backwards compatibility to SMVC 6.0.0 and newer
     * @return string
     */
    public function getPluginSemverInDatabase();

    /**
     * Is the NS database semver is newer or same as code semver. If no - we should be read for update
     * @note make sure the blog id here is ok for network
     * @return bool
     */
    public function isPluginDataUpToDateInDatabase();

    /**
     * NOTE: Update may exist, but the system might be not compatible for update
     * @return bool
     */
    public function checkPluginUpdateExists();

    /**
     * @return bool
     */
    public function canUpdatePluginDataInDatabase();

    /**
     * Can we do a major upgrade, i.e. from 1.*.* to 2.*.* etc., not 1.0.* to 1.1.*
     * @return bool
     */
    public function canMajorlyUpgradePluginDataInDatabase();
}