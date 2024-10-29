<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
    <span><?=esc_html($lang['LANG_MANUAL_URL_PARAMETERS_TEXT']);?></span>
</h1>
<p>
    For some particular situations, instead of using shortcodes and creating a different WordPress page for each shortcode,
    you may want to use URL parameter, i.e. to include a specific benefit based on some kind of website&#39;s search.
</p>
<p>
    <strong>All supported URL parameters:</strong>
</p>
<ul>
    <li>
        benefit=[X] - where [X] is your benefit id, taken from Benefits -&gt; Benefit Manager -&gt; Benefits
    </li>
</ul>

<p>Please keep in mind that:</p>
<ol>
    <li>URL parameters can be send via $_GET only.</li>
    <li>Shortcode attributes has higher priority over URL parameters, so URL parameter will only work if that specific
        shortcode attribute is not used for that shortcode, or that specific shortcode attribute
        is set to &#39;-1&#39; (all).</li>
</ol>

<h3>Example:</h3>
<p>To show only a benefit with ID=4, go to &#39;https://your-site.com/benefits/?benefit=4&#39; URL.</p>