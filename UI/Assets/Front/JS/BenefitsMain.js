/**
 * Plugin Front End JS
 * License: Licensed under the AGPL license.
 */

// NOTE: For object-oriented language experience, this variable name should always match current file name
var BenefitsMain = {
    getValidCode: function(paramCode, paramDefaultValue, paramToUppercase, paramSpacesAllowed, paramDotsAllowed)
    {
        var regexp = '';
        if(paramDotsAllowed)
        {
            regexp = paramSpacesAllowed ? /[^-_0-9a-zA-Z. ]/g : /[^-_0-9a-zA-Z.]/g; // There is no need to escape dot char
        } else
        {
            regexp = paramSpacesAllowed ?  /[^-_0-9a-zA-Z ]/g : /[^-_0-9a-zA-Z]/g;
        }
        var rawData = Array.isArray(paramCode) === false ? paramCode : paramDefaultValue;
        var validCode = rawData.replace(regexp, '');

        if(paramToUppercase)
        {
            validCode = validCode.toUpperCase();
        }

        return validCode;
    },

    getValidPrefix: function(paramPrefix, paramDefaultValue)
    {
        var rawData = Array.isArray(paramPrefix) === false ? paramPrefix : paramDefaultValue;
        return rawData.replace(/[^-_0-9a-z]/g, '');
    },

    changeThumbnailAndDescription: function(thumbContainer, benefitsNamesContainer, benefitName, benefitThumb, descriptionsContainer, description)
    {
        this.changeSlideThumb(thumbContainer, benefitsNamesContainer, benefitName, benefitThumb);
        this.showDescription(descriptionsContainer, description);
    },

    changeSlideThumb: function (thumbContainer, benefitsNamesContainer, benefitName, benefitThumb)
    {
        var objBenefitsThumbContainer = jQuery('.responsive-benefits-slider .' + thumbContainer + ' .benefit-thumbnail-container');
        var objBenefitsNameContainer = jQuery('.responsive-benefits-slider .' + benefitsNamesContainer + ' .single-benefit');
        var objBenefitName = jQuery('.responsive-benefits-slider .' + benefitName);
        var objBenefitThumb = jQuery('.responsive-benefits-slider .' + benefitThumb);

        // Reset 'is-current' class to current slides thumbnail
        objBenefitsThumbContainer.removeClass('is-current');
        objBenefitThumb.addClass('is-current');

        // Reset 'selected' class to current slides thumbnail
        objBenefitsNameContainer.removeClass('selected');
        objBenefitName.addClass('selected');
    },

    leftAlignAllStripes: function (benefitsContainers)
    {
        var arrBenefitsContainers = benefitsContainers;

        // Timeout required for the browser to have time to adjust
        setTimeout(function()
        {
            arrBenefitsContainers.forEach(function(benefitContainer)
                {
                    var objBenefitStripes = jQuery('.' + benefitContainer + ' .benefits-stripe');
                    var offsetLeft = -Math.round(document.querySelector('.' + benefitContainer + ' .single-benefit').offsetLeft);

                    objBenefitStripes.css({
                            left : offsetLeft
                        }
                    )
                }
            )
        },150);
    },

    showDescription: function(descriptionsContainer, description)
    {
        var objDescriptionsContainer = jQuery('.' + descriptionsContainer + ' .benefit-description');
        var objDescription = jQuery('.' + description);

        objDescriptionsContainer.removeClass('is-current');
        objDescription.addClass('is-current')
    },

    hideDescriptions: function(descriptionsContainer)
    {
        var objDescriptions = jQuery('.' + descriptionsContainer + ' .benefit-description');
        objDescriptions.removeClass('is-current');
    }
};