(function ($) {
    'use strict';

    $(document).on('ready', function () {
        // -----------------------------
        //  Nice Select
        // -----------------------------
        $('select').niceSelect();

        // Init Archive Filter Widget Component
        window?.archiveFilterComponentModule?.init();

        // Init Sort Article Listing Widget Component
        window?.articleListingSortComponentModule?.init();

        // Init Tah Filter Component
        window?.tagFilterComponentModule?.init();

        // Init Alerts
        window?.alertModule?.init();

        // Init Update Article Views Component
        window?.updateArticleViewsModule?.init();

        // Init Category Sidebar Menu Component
        window?.categorySidebarMenuComponentModule?.init();

        /***ON-LOAD***/
        jQuery(window).on('load', function () {
            
        });

    });

})(jQuery);

$(document).ready(function() {
    $('select:not(.ignore)').niceSelect();
});
