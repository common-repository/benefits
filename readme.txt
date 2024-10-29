=== Benefits ===
Contributors: KestutisIT, mariusslo
Donate link: https://profiles.wordpress.org/KestutisIT
Website link: https://wordpress.org/plugins/benefits/
Tags: slider, offers, benefits, benefit, offer
Requires at least: 4.6
Tested up to: 5.2
Requires PHP: 5.6
Stable tag: trunk
License: MIT License
License URI: https://opensource.org/licenses/MIT

It’s a MIT-licensed (can be used in premium themes), high quality, native and responsive WordPress plugin to create and view slider-based benefits


== Description ==

**First** - differently than any other similar plugin, this plugin is based on MIT license, which is a holly-grail for premium theme authors on i.e. ThemeForest or similar marketplaces.
Differently to standard GPLv2 license you are not required to open-source your theme and you **CAN** include this plugin into your premium websites bundle packs.
I do say here **bundle packs**, because you should never have an benefits section to be a part of your theme, because that would be a bad idea - you need to leave your customers a flexibility for the future scale:
What if your customers will decide later go with some kind of fancy **e-commerce marketplace** system like in Amazon.com - if your customer will grow that big, he won't need to have benefits plugin anymore on their website, he will want to replace it with that fancy **e-commerce marketplace** system.
So my advise is to include this plugin in your bundle pack's `/Optional Plugins/` folder, so that you can tell about in the installation instructions, but make it fully independent from your theme.

**Second** - this plugin is fully **MVC + Templates** based. This means that it's code is not related at all to it's UI, and that allows you easily to override it's UI templates and Assets (CSS, JS, Images) by your theme very easily (and there is detailed step-by-step instructions given how to do that).
This means that you making a theme to be what the theme has to be - a UI part of your website, nothing more.

**Third** - it is much more secure than any other plugin's on the market. It is based on top-end S.O.L.I.D. coding principle with input data validation with data-patterns, output escaping.

**Fourth** - this plugin is scalable – it’s source code is fully object-oriented, clean & logical, based on MVC architectural pattern with templates engine, compliant with strict PSR-2 coding standard and PSR-4 autoloaders, and easy to understand how to add new features on your own.

**Fifth** - this plugin works well with big databases & high-traffic websites – it is created on optimal BCNF database structure and was tested on live website with 1M customers database and 500,000 active daily views.

**Sixth** - it does support official WordPress multisite as network-enabled plugin, as well as it does have support WPML string translation.
At this point, if you need more than one language, I'd strongly advise to go with official WordPress multisite setup, because it is free, it is official (so you will never need to worry about the future support), and, most important - WordPress multisite is much more suitable for websites that needs to scale. You don't want to have that additional translation bottle-neck code layer to be processed via database.

**Seventh** - it has nice user experience - it's has a default design, it does allow you to have more than 3 benefits via different slider's slides, as well as fading in and out description on mouse hover - so it is not static like what you usually get with Gutenberg.

**But the most important** is that this plugin is and always be **ads-free**. I personally really hate these **freemium**, **ads-full** or **tracking** plugins which makes majority of the plugins on w.org plugins directory (and, actually, many of premium marketplaces). So this is the key features we always maintain:
1. Never track your data (nor even by putting some kind of GDPR-compliance agreement checkbox, like `Error Log Monitor` plugin),
2. Never make it pseudo-ads-full (even such a big plugins like `WooCommerce` or `Contact Form 7` has nearly 80% of their home screen or 20% of their main buttons about `how to install \ buy other plugins`
- this is a really ugly behavior of pushing-more and going to Facebook-like business, where you get like drug-addicted to company products).

The goal of this plugin is to full-fill the needs of website-starter, that needs a great tool which can last him for many years until it will grow that big so he would grow-out current plugins and would need some kind of different plugins.

And, I believe, that many other developers had the same issue when tried to create their first premium theme or set-up a website for their client. Starting with the issues with license type to the moment when benefits section is `hardcoded` into theme code.

So I wanted to help all these developers to save their time, and I'm releasing this plugin for you to simplify your work. And I'm releasing it under MIT license, which allows you to use this plugin your website bundle without any restrictions for both - free and commercial use.

Plus - I'm giving a promise to you, that this plugin is and will always be 100% free, without any ads, 'Subscribe', 'Follow us', 'Check our page', 'Get Pro Version' or similar links.

Finally - the code is poetry - __the better is the web, the happier is the world__.

- - - -
== Languages ==

* English _(official)_
* Russian _(official)_
* Lithuanian _(official)_
* Arabian RLT _(raw translation)_
* Bulgarian _(translated by Martin Petkov)_
* Czech _(translated by Lukáš Smrček)_
* French _(translated by Edner Zephir)_
* German _(translated by Websoft AG team)_
* Greek _(translated by Alexandros Tsapournias)_
* Italian _(translated by NetHome, LTD team)_
* Korean _(translated by Eric Jazz)_
* Portuguese _(translated by HK – Agência de Publicidade)_
* Romanian _(translated by Marius Stoica)_
* Spanish _(translated by Ana Victoria Rodríguez Guerrero, Edner Zephir & Anthony Ortega)_
* Swedish _(translated by Kristian Salov)_
* Turkish _(translated by Levent Şane)_

== Live Demo ==
[Benefits (Live Demo)](https://nativerental.com/cars/ "Benefits (Live Demo)")

== GitHub Repository (for those, who want to contribute via "Pull Requests") ==
[https://github.com/SolidMVC/Benefits](https://github.com/SolidMVC/Benefits "Benefits @GitHub")

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload `Benefits` (or `benefits`) to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.
2.1. If your theme **does not* support FontAwesome icons, please **enable** FontAwesome in Benefits -> Settings -> "Global" tab.
3. Go to admin menu item `Benefits` -> `Benefit Manager` and add benefits.
4. Now create a page by clicking the [Add New] button under the page menu.
5. Add `[benefits display="benefits" layout="slider"]` shortcode to page content and click on `[Publish]` button.
6. In WordPress front-end page, where you added benefits slider shortcode, you will see slider-based benefits.
7. Congratulations, you're done! We wish you to have a pleasant work with our Benefits Plugin for WordPress.


== Frequently Asked Questions ==

= Does it supports multiple slides of benefits? =

Yes, this plugin does have support for `Slick Slider` that is seen it you have more than 3 benefits.

= Does it support URL parameters? =

Yes, if your BENEFIT ID is i.e. `4` (you can get your BENEFIT ID from `Benefits` -> `Benefit Manager`), then you can go
to your website's benefit page and show only desired benefit by following this URL structure:

`
<YOUR-SITE>.com/<BENEFIT-PAGE>/?benefit=4
`


== Screenshots ==

1. Benefits - Front-End Benefits Slider
2. Benefits - Responsive View of Benefits Slider
3. Benefits - Admin Benefit Manager
4. Benefits - Admin Global Settings
5. Benefits - Admin Import Demo
6. Benefits - Admin Plugin Updating
7. Benefits - Admin Plugin Status
8. Benefits - Admin User Manual


== Changelog ==

= 6.1.10 =
* Fixed semver issue.

= 6.1.9 =
* Fixed compatibility style routing bug
* Refactored time(UTC)
* HTTP changed to HTTPS
* Refactored CSSFile to CSS_File

= 6.1.8 =
* Initial public release! Based on S.O.L.I.D. MVC Engine, Version 6 (without extensions).


== Upgrade Notice ==

= 6.1.8+ =
* Just drag and drop new plugin folder or click 'update' in WordPress plugin manager.

= 6.1.8 =
* Initial public release!
