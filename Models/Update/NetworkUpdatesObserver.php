<?php
/**
 * Network updates observer
 *
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models\Update;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Language\Language;
use Benefits\Models\Language\LanguageInterface;
use Benefits\Models\PrimitiveObserverInterface;
use Benefits\Models\Status\SingleStatus;

final class NetworkUpdatesObserver implements PrimitiveObserverInterface
{
    private $conf 	                    = NULL;
    private $lang 		                = NULL;
    private $debugMode 	                = 0;
    private $savedMessages              = array();

    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang)
    {
        // Set class settings
        $this->conf = $paramConf;
        // Already sanitized before in it's constructor. Too much sanitization will kill the system speed
        $this->lang = $paramLang;
    }

    public function inDebug()
    {
        return ($this->debugMode >= 1 ? TRUE : FALSE);
    }

    public function getSavedDebugMessages()
    {
        return isset($this->savedMessages['debug']) ? $this->savedMessages['debug'] : array();
    }

    public function getSavedOkayMessages()
    {
        return isset($this->savedMessages['okay']) ? $this->savedMessages['okay'] : array();
    }

    public function getSavedErrorMessages()
    {
        return isset($this->savedMessages['error']) ? $this->savedMessages['error'] : array();
    }

    private function saveAllMessages($paramArrMessages)
    {
        if(isset($paramArrMessages['debug']))
        {
            $this->savedMessages['debug'] = array_merge($this->getSavedDebugMessages(), $paramArrMessages['debug']);
        }
        if(isset($paramArrMessages['okay']))
        {
            $this->savedMessages['okay'] = array_merge($this->getSavedOkayMessages(), $paramArrMessages['okay']);
        }
        if(isset($paramArrMessages['error']))
        {
            $this->savedMessages['error'] = array_merge($this->getSavedErrorMessages(), $paramArrMessages['error']);
        }
    }

    // NOTE: In the future there could be a method like 'do615_UpdateTo620'
}