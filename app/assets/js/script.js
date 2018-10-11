jQuery(document).ready(function($) {

    /* Options languages switcher */
    const switcher = $('select[name=options_lang]');

    switcher.on('change', function () {
        const selectLanguage = $(this).val();
        document.location.href = setUrlParam(window.location.href, 'options_lang', selectLanguage);
    });

    const setUrlParam = function(url, key, val) {
        const newParam = key + '=' + val;
        let params = '?' + newParam;

        if (url) {
            params = url.replace(new RegExp('([?&])' + key + '[^&]*'), '$1' + newParam);

            if (params === url) {
                params += '&' + newParam;
            }
        }

        return params;
    };
    /* End Options languages switcher */

});