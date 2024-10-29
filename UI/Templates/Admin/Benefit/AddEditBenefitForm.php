<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('jquery-validate');
wp_enqueue_script('benefits-admin');

// Styles
wp_enqueue_style('jquery-validate');
wp_enqueue_style('benefits-admin');
?>
<p>&nbsp;</p>
<div id="container-inside" style="width:1000px;">
   <span style="font-size:16px; font-weight:bold"><?=esc_html($lang['LANG_BENEFIT_ADD_EDIT_TEXT']);?></span>
   <input type="button" value="<?=esc_attr($lang['LANG_BENEFIT_BACK_TO_LIST_TEXT']);?>" onClick="window.location.href='<?=esc_url($backToListURL);?>'" style="background: #EFEFEF; float:right; cursor:pointer;"/>
   <hr style="margin-top:10px;"/>
   <form action="<?=esc_url($formAction);?>" method="POST" class="benefits-add-edit-benefit-form" enctype="multipart/form-data">
        <table cellpadding="5" cellspacing="2" border="0">
            <input type="hidden" name="benefit_id" value="<?=esc_attr($benefitId);?>"/>

<tr>
    <td width="95px"><strong><?=esc_html($lang['LANG_BENEFIT_TITLE_TEXT']);?>:</strong></td>
    <td colspan="2">
        <input type="text" name="benefit_title" maxlength="26" value="<?=esc_attr($benefitTitle);?>" class="benefit-title" style="width:150px;" title="<?=esc_attr($lang['LANG_BENEFIT_TITLE_TEXT']);?>" /><br />
        <em>(<?=esc_html($lang['LANG_BENEFIT_TITLE_OPTIONAL_TEXT']);?>)</em>
    </td>
</tr>
<tr>
    <td><strong><?=esc_html($lang['LANG_BENEFIT_IMAGE_TEXT']);?>:</strong></td>
    <td colspan="2">
        <input type="file" name="benefit_image" style="width:250px;" title="<?=esc_attr($lang['LANG_IMAGE_TEXT']);?>" />
        <?php if($benefitImageURL != ""): ?>
            <span>
                &nbsp;&nbsp;&nbsp;<a rel="collection" href="<?=esc_url($benefitImageURL);?>" target="_blank">
                    <strong><?=$lang[$demoBenefitImage ? 'LANG_IMAGE_VIEW_DEMO_TEXT' : 'LANG_IMAGE_VIEW_TEXT'];?></strong>
                </a>
                &nbsp;&nbsp;&nbsp;&nbsp;<span style="color: navy;">
                    <strong><?=$lang[$demoBenefitImage ? 'LANG_IMAGE_UNSET_DEMO_TEXT' : 'LANG_IMAGE_DELETE_TEXT'];?></strong>
                </span> &nbsp;
                <input type="checkbox" name="delete_benefit_image"
                       title="<?=$lang[$demoBenefitImage ? 'LANG_IMAGE_UNSET_DEMO_TEXT' : 'LANG_IMAGE_DELETE_TEXT'];?>" />
            </span>
        <?php else: ?>
            &nbsp;&nbsp;&nbsp;&nbsp; <strong><?=esc_html($lang['LANG_IMAGE_NONE_TEXT']);?></strong>
        <?php endif; ?>
    </td>
</tr>
<tr>
    <td>
        <strong><?=esc_html($lang['LANG_BENEFIT_DESCRIPTION_TEXT']);?>:</strong><br />
    </td>
    <td colspan="2">
        <textarea name="benefit_description" rows="3" cols="50" class="benefit-description" title="<?=esc_attr($lang['LANG_BENEFIT_DESCRIPTION_TEXT']);?>"><?=esc_textarea($benefitDescription);?></textarea><br />
        <em>(<?=esc_html($lang['LANG_BENEFIT_DESCRIPTION_OPTIONAL_TEXT']);?>)</em>
    </td>
</tr>
<tr>
    <td><strong><?=esc_html($lang['LANG_BENEFIT_ORDER_TEXT']);?>:</strong></td>
    <td>
        <input type="text" name="benefit_order" maxlength="11" value="<?=esc_attr($benefitOrder);?>" class="benefit-order" style="width:40px;" title="<?=esc_attr($lang['LANG_BENEFIT_ORDER_TEXT']);?>" />
    </td>
    <td>
        <em><?=($benefitId > 0 ? '' : '('.esc_html($lang['LANG_BENEFIT_ORDER_OPTIONAL_TEXT']).')');?></em>
    </td>
</tr>
<tr>
    <td></td>
    <td colspan="2"><input type="submit" value="<?=esc_attr($lang['LANG_BENEFIT_SAVE_TEXT']);?>" name="save_benefit" style="cursor:pointer;"/></td>
</tr>

        </table>
    </form>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    // Validator
    jQuery('.benefits-add-edit-benefit-form').validate();
});
</script>
