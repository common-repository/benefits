<?php
/**
 * NOTE: As this is non-extension based plugin, there is no data network-populate / network-drop data links if the plugin is network-enabled
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Controllers\Admin\Status;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Formatting\StaticFormatter;
use Benefits\Models\Language\LanguageInterface;
use Benefits\Models\Cache\StaticSession;
use Benefits\Models\Status\NetworkStatus;
use Benefits\Models\Update\NetworkPatchesObserver;
use Benefits\Models\Update\NetworkUpdatesObserver;
use Benefits\Models\Validation\StaticValidator;
use Benefits\Views\PageView;

final class NetworkController
{
    protected $conf         = NULL;
    protected $lang 	    = NULL;
    protected $view 	    = NULL;

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->lang = $paramLang;
    }

    /**
     * @throws \Exception
     */
    private function processUpdate()
    {
        // Create mandatory instances
        $objStatus = new NetworkStatus($this->conf, $this->lang);
        $objUpdatesObserver = new NetworkUpdatesObserver($this->conf, $this->lang);
        $objPatchesObserver = new NetworkPatchesObserver($this->conf, $this->lang);

        // Allow only one update at-a-time per site refresh. We need that to save resources of server to not to get to timeout phase
        $allUpdatableSitesSemverUpdated = FALSE;
        $currentMinPluginSemverInDatabase = $objStatus->getMinPluginSemverInDatabase();
        $latestSemver = $this->conf->getPluginSemver();

        // ----------------------------------------
        // NOTE: A PLACE FOR UPDATE CODE
        // ----------------------------------------
        if($this->conf->isNetworkEnabled())
        {
            if(version_compare($currentMinPluginSemverInDatabase, $latestSemver, '=='))
            {
                // It's a last version
                $allUpdatableSitesSemverUpdated = TRUE;
            }
            // NOTE: Before this statement there could be update scope in the future

            // Process patches
            // NOTE: Is import here to get plugin semver once again, to make sure we have up to date data
            $updatedMinPluginSemverInDatabase = $objStatus->getMinPluginSemverInDatabase();
            $updatedMaxPluginSemverInDatabase = $objStatus->getMaxPluginSemverInDatabase();
            if(version_compare($updatedMinPluginSemverInDatabase, '6.1.0', '>=') && version_compare($updatedMaxPluginSemverInDatabase, '6.2.0', '<'))
            {
                // Process 6.1.Z patches
                $allUpdatableSitesSemverUpdated = $objPatchesObserver->doPatch(6, 1);
            }
            // NOTE: There could be another 'ELSE IF' scope for later patches in the future

            // Cache update messages
            StaticSession::cacheHTML_Array('admin_debug_html', $objUpdatesObserver->getSavedDebugMessages());
            StaticSession::cacheValueArray('admin_okay_message', $objUpdatesObserver->getSavedOkayMessages());
            StaticSession::cacheValueArray('admin_error_message', $objUpdatesObserver->getSavedErrorMessages());

            // Cache patch messages
            StaticSession::cacheHTML_Array('admin_debug_html', $objPatchesObserver->getSavedDebugMessages());
            StaticSession::cacheValueArray('admin_okay_message', $objPatchesObserver->getSavedOkayMessages());
            StaticSession::cacheValueArray('admin_error_message', $objPatchesObserver->getSavedErrorMessages());
        }

        // Check if plugin is up-to-date
        $pluginUpToDate = $objStatus->isAllBlogsWithPluginDataUpToDate();

        if($allUpdatableSitesSemverUpdated === FALSE || $pluginUpToDate === FALSE)
        {
            // Failed or if there is more updates to go
            wp_safe_redirect('admin.php?page='.$this->conf->getPluginURL_Prefix().'network-status&tab=status');
        } else
        {
            // Completed
            wp_safe_redirect('admin.php?page='.$this->conf->getPluginURL_Prefix().'network-status&tab=status&completed=1');
        }
        exit;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function printContent()
    {
        // Message handler - should always be at the begging of method (in the very first line)
        $ksesedDebugHTML = StaticValidator::inWP_Debug() ? StaticSession::getKsesedHTML_Once('admin_debug_html') : '';
        $errorMessage = StaticSession::getValueOnce('admin_error_message');
        $okayMessage = StaticSession::getValueOnce('admin_okay_message');

        // Both - _POST and _GET supported
        if(isset($_GET['update']) || isset($_POST['update'])) { $this->processUpdate(); }

        // Create mandatory instances
        $objStatus = new NetworkStatus($this->conf, $this->lang);

        // Create view
        $objView = new PageView();

        // 1. Set the view variables - Tabs
        $objView->tabs = StaticFormatter::getTabParams(array('status', 'license'), 'status', isset($_GET['tab']) ? $_GET['tab'] : '');

        // 2. Set the view variables - other
        $objView->staticURLs = $this->conf->getRouting()->getFolderURLs();
        $objView->lang = $this->lang->getAll();
        $objView->ksesedDebugHTML = $ksesedDebugHTML;
        $objView->errorMessage = $errorMessage;
        $objView->okayMessage = $okayMessage;
        $objView->statusTabFormAction = network_admin_url('admin.php?page='.$this->conf->getPluginURL_Prefix().'network-status&noheader=true');
        $objView->networkEnabled = TRUE;
        $objView->goToNetworkAdmin = FALSE;
        $objView->updateExists = $objStatus->checkPluginUpdateExistsForSomeBlog();
        $objView->updateAvailable = $objStatus->canUpdatePluginDataInSomeBlog();
        $objView->majorUpgradeAvailable = $objStatus->canMajorlyUpgradePluginDataInSomeBlog();
        $objView->canUpdate = $objStatus->canUpdatePluginDataInSomeBlog();
        $objView->canMajorlyUpgrade = $objStatus->canMajorlyUpgradePluginDataInSomeBlog();
        $objView->databaseMatchesCodeSemver = $objStatus->isAllBlogsWithPluginDataUpToDate();
        $objView->minDatabaseSemver = $objStatus->getMinPluginSemverInDatabase();
        $objView->newestExistingSemver = $this->conf->getPluginSemver();
        $objView->newestSemverAvailable = $this->conf->getPluginSemver();

        // Print the template
        $templateRelPathAndFileName = 'Status'.DIRECTORY_SEPARATOR.'NetworkTabs.php';
        echo $objView->render($this->conf->getRouting()->getAdminTemplatesPath($templateRelPathAndFileName));
    }
}
