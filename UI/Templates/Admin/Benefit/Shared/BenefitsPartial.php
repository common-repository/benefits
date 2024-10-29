<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
?>
<h1>
	<span><?=esc_html($lang['LANG_BENEFIT_LIST_TEXT']);?></span>&nbsp;&nbsp;
	<input class="add-new" type="button" value="<?=esc_attr($lang['LANG_BENEFIT_ADD_NEW_TEXT']);?>" onClick="window.location.href='<?=esc_url($addNewBenefitURL);?>'" />
</h1>
<table id="benefits-datatable" class="display benefits-datatable" border="0" style="width:100%">
	<thead>
        <tr>
            <th><?=esc_html($lang['LANG_ID_TEXT']);?></th>
            <th><?=esc_html($lang['LANG_BENEFIT_TEXT']);?></th>
            <th><?=esc_html($lang['LANG_BENEFIT_DESCRIPTION_TEXT']);?></th>
            <th style="text-align: center"><?=esc_html($lang['LANG_LIST_ORDER_TEXT']);?></th>
            <th><?=esc_html($lang['LANG_ACTIONS_TEXT']);?></th>
        </tr>
	</thead>
	<tbody>
	    <?=$trustedAdminBenefitListHTML;?>
	</tbody>
</table>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery('#benefits-datatable').dataTable( {
		"responsive": true,
		"bJQueryUI": true,
		"iDisplayLength": 25,
		"bSortClasses": false,
		"aaSorting": [[3,'asc'],[0,'asc']],
        "aoColumns": [
            { "width": "1%" },
            { "width": "20%" },
            { "width": "55%" },
            { "width": "4%" },
            { "width": "10%" }
        ],
        "bAutoWidth": true,
		"bInfo": true,
		"sScrollY": "100%",
		"sScrollX": "100%",
		"bScrollCollapse": true,
		"sPaginationType": "full_numbers",
		"bRetrieve": true,
        "language": {
            "url": BenefitsVars['DATATABLES_LANG_URL']
        }
	});
});
</script>