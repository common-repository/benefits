<?php
defined( 'ABSPATH' ) or die( 'No script kiddies, please!' );
// Scripts
wp_enqueue_script('jquery');
wp_enqueue_script('benefits-main');
if($settings['conf_load_slick_slider_from_plugin'] == 1):
    wp_enqueue_script('slick-slider');
endif;

// Styles
if($settings['conf_load_slick_slider_from_plugin'] == 1):
    wp_enqueue_style('slick-slider');
    wp_enqueue_style('slick-theme');
endif;
wp_enqueue_style('benefits-main');
?>
<div class="benefits-wrapper benefits-benefits-slider">
    <?php if(sizeof($benefits) > 0): ?>
        <div class="responsive-benefits-slider">
            <?php foreach($benefits AS $benefit): ?>
                <div>
                    <div class="benefit-image">
                        <?php if($benefit['benefit_thumb_url'] != ""): ?>
                            <img src="<?=$benefit['benefit_thumb_url'];?>" title="<?=esc_attr($benefit['translated_dynamic_benefit_caption']);?>" alt="<?=esc_attr($lang['LANG_IMAGE_TEXT']);?>">
                        <?php endif; ?>
                    </div>
                    <?php if($benefit['translated_benefit_title'] != ""): ?>
                        <div class="benefit-title">
                            <?=esc_html($benefit['translated_benefit_title']);?>
                        </div>
                    <?php endif; ?>
                    <?php if($benefit['translated_benefit_description'] != ""): ?>
                        <div class="benefit-description">
                            <?=esc_br_html($benefit['translated_benefit_description']);?>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-benefits-available"><?=esc_html($lang['LANG_BENEFITS_NONE_AVAILABLE_TEXT']);?></div>
    <?php endif; ?>
</div>
<script type="text/javascript">
jQuery(document).ready(function() {
    jQuery('.responsive-benefits-slider').slick({
        dots: false,
        infinite: false,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 5,
        prevArrow: '<button type="button" class="benefits-slider-prev"><?=esc_html($lang['LANG_PREVIOUS_TEXT']);?></button>',
        nextArrow: '<button type="button" class="benefits-slider-next"><?=esc_html($lang['LANG_NEXT_TEXT']);?></button>',
        responsive: [
            {
                breakpoint: 1280,
                settings: {
                    slidesToShow: 4,
                    slidesToScroll: 4,
                    prevArrow: '<button type="button" class="benefits-slider-prev"><?=esc_html($lang['LANG_PREVIOUS_TEXT']);?></button>',
                    nextArrow: '<button type="button" class="benefits-slider-next"><?=esc_html($lang['LANG_NEXT_TEXT']);?></button>'
                }
            },
            {
                breakpoint: 768,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: true
                }
            },
            {
                breakpoint: 420,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2,
                    infinite: true,
                    dots: true
                }
            }
            // You can unslick at a given breakpoint now by adding:
            // settings: "unslick"
            // instead of a settings object
        ]
    });
});
</script>