<?php
/**
 * Extension install insert sql data
 * @note        Supports all installation BB codes
 * @package     Benefits
 * @author      Kestutis Matuliauskas
 * @copyright   Kestutis Matuliauskas
 * @license     MIT License. See Legal/License.txt for details.
 */
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

$arrInsertSQL = array();
$arrPluginInsertSQL = array();

$arrPluginInsertSQL['settings'] = "(`conf_key`, `conf_value`, `conf_translatable`, `blog_id`) VALUES
('conf_benefit_thumb_h', '493', '0', [BLOG_ID]),
('conf_benefit_thumb_w', '311', '0', [BLOG_ID]),
('conf_load_font_awesome_from_plugin', '1', '0', [BLOG_ID]),
('conf_load_slick_slider_from_plugin', '1', '0', [BLOG_ID]),
('conf_plugin_semver', '[PLUGIN_SEMVER]', '0', [BLOG_ID]),
('conf_short_benefit_title_max_length', '26', '0', [BLOG_ID]),
('conf_system_style', 'Crimson Red', '0', [BLOG_ID]),
('conf_updated', '0', '0', [BLOG_ID]),
('conf_use_sessions', '1', '0', [BLOG_ID]),
('conf_timestamp', '[TIMESTAMP]', '0', [BLOG_ID]);";
