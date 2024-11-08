<?php
/**
 * Configuration class dependant on template
 * Note 1: This is a root class and do not depend on any other plugin classes

 * @package Benefits
 * @author KestutisIT
 * @copyright KestutisIT
 * @license MIT License. See Legal/License.txt for details.
 */
namespace Benefits\Models\Style;
use Benefits\Models\Configuration\ConfigurationInterface;
use Benefits\Models\Language\LanguageInterface;

interface StyleInterface
{
    // Constructor
    public function __construct(ConfigurationInterface &$paramConf, LanguageInterface &$paramLang, $paramSystemStyle);

    // Setters
    public function setSitewideStyles();
    public function setCompatibilityStyles();
    public function setLocalStyles();

    // Debug
    public function inDebug();

    // Getters
    public function getParentThemeCompatibilityCSS_URL();
    public function getCurrentThemeCompatibilityCSS_URL();
    public function getSitewideCSS_URL();
    public function getLocalCSS_URL();
}