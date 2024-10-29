<?php
/**
 * Demo data
 * @package     Benefits
 * @author      Kestutis Matuliauskas
 * @copyright   Kestutis Matuliauskas
 * @license     MIT License. See Legal/License.txt for details.
 *
 * @benefits-plugin-demo
 * Demo UID: 2
 * Demo Name: Car Rental Agency - Solid Black
 * Demo Enabled: 1
 */
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );

$arrPluginReplaceSQL = array();

// First - include a common demo SQL data, to avoid repeatedness
include('Shared/CarRentalAgencySQLPartial.php');

// Then - list tables that are different for each demo version

// NOTE: 'benefit_id' does not matter here and can be set automatically
$arrPluginReplaceSQL['benefits'] = "(`benefit_title`, `benefit_image`, `demo_benefit_image`, `benefit_description`, `benefit_order`, `blog_id`) VALUES
('', 'benefit_good-price-and-quality-ratio_solid-black.png', 1, 'Good price & quality ratio', 1, [BLOG_ID]),
('', 'benefit_constantly-maintained-vehicles_solid-black.png', 1, 'Constantly maintained vehicles', 2, [BLOG_ID]),
('', 'benefit_trustworthy-customer-support_solid-black.png', 1, 'Trustworthy customer support', 3, [BLOG_ID]),
('', 'benefit_unlimited-mileage_solid-black.png', 1, 'Unlimited mileage', 4, [BLOG_ID]),
('', 'benefit_kasko-and-civilian-insurance-included_solid-black.png', 1, 'KASKO & Civilian insurance included', 5, [BLOG_ID])";

$arrPluginReplaceSQL['settings'] = "(`conf_key`, `conf_value`, `conf_translatable`, `blog_id`) VALUES
('conf_benefit_thumb_h', '493', '0', [BLOG_ID]),
('conf_benefit_thumb_w', '311', '0', [BLOG_ID]),
('conf_load_font_awesome_from_plugin', '1', '0', [BLOG_ID]),
('conf_load_slick_slider_from_plugin', '1', '0', [BLOG_ID]),
('conf_short_benefit_title_max_length', '26', '0', [BLOG_ID]),
('conf_system_style', 'Solid Black', '0', [BLOG_ID]),
('conf_updated', '0', '0', [BLOG_ID]),
('conf_use_sessions', '1', '0', [BLOG_ID])";