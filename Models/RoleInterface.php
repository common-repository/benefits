<?php
/**
 * Role must-have interface
 * Interface purpose is describe all public methods used available in the class and enforce to use them
 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Language\LanguageInterface;

interface RoleInterface
{
    /**
     * @param ConfigurationInterface $paramConf
     * @param LanguageInterface $paramLang
     */
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang);

    /**
     * Debug status
     * @return bool
     */
    public function inDebug();

    /**
     * @return string
     */
    public function getRoleName();

    /**
     * @return array
     */
    public function getCapabilities();

    /**
     * @return bool
     */
    public function add();

    /**
     * @return void
     */
    public function remove();

    /**
     * @return void
     */
    public function addCapabilities();

    /**
     * @return void
     */
    public function removeCapabilities();
}