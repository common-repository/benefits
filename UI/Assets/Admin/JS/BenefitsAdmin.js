/**
 * Plugin Admin JS
 * License: Licensed under the AGPL license.
 */

// Dynamic variables
if(typeof BenefitsVars === "undefined")
{
    // The values here will come from WordPress script localizations,
    // but in case if they wouldn't, we have a backup initializer below
    var BenefitsVars = {};
}

// Dynamic language
if(typeof BenefitsLang === "undefined")
{
    // The values here will come from WordPress script localizations,
    // but in case if they wouldn't, we have a backup initializer below
    var BenefitsLang = {};
}

// NOTE: For object-oriented language experience, this variable name should always match current file name
var BenefitsAdmin = {
    vars: BenefitsVars,
    lang: BenefitsLang,

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

    deleteBenefit: function(paramBenefitId)
    {
        var approved = confirm(this.lang['LANG_BENEFIT_DELETING_DIALOG_TEXT']);
        if (approved)
        {
            window.location = 'admin.php?page=benefits-add-edit-benefit&noheader=true&delete_benefit=' + paramBenefitId;
        }
    }
};