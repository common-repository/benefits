<?php
/**
 * Extension replace sql when plugin is (re)enabled
 * @note        Fired every time when plugin is enabled, or enabled->disabled->enabled, etc.
 * @note2       MySQL 'REPLACE INTO' works like MySQL 'INSERT INTO', except that if there is a row
 *              with the same key you are trying to insert, it will be deleted on replace instead of giving you an error.
 * @note3       Supports [BLOG_ID] BB code
 * @package     Benefits
 * @author      Kestutis Matuliauskas
 * @copyright   Kestutis Matuliauskas
 * @license     MIT License. See Legal/License.txt for details.
 */
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

$arrReplaceSQL = array();
$arrPluginReplaceSQL = array();

$arrPluginReplaceSQL['settings'] = "(`conf_key`, `conf_value`, `conf_translatable`, `blog_id`) VALUES
('conf_benefit_thumb_h', '493', '0', [BLOG_ID]),
('conf_benefit_thumb_w', '311', '0', [BLOG_ID]),
('conf_load_font_awesome_from_plugin', '1', '0', [BLOG_ID]),
('conf_load_slick_slider_from_plugin', '1', '0', [BLOG_ID]),
('conf_short_benefit_title_max_length', '26', '0', [BLOG_ID])";