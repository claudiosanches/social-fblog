/* global social_fblog_params */
(function ( $ ) {
    "use strict";

    $(function () {
        var socialbox = $("#socialfblog-box"),
            offset = socialbox.offset();

        $(window).scroll(function () {
            if ($(window).scrollTop() > offset.top) {
                socialbox.stop();
                if ('1' === social_fblog_params.opacity) {
                    socialbox.css({
                        opacity: '1'
                    });
                }
                if ('0' === social_fblog_params.effect) {
                    socialbox.animate({
                        marginTop: ($(window).scrollTop() - offset.top + 60)
                    });
                } else {
                    socialbox.css({
                        position: 'fixed',
                        top: 60
                    });
                }
            } else {
                socialbox.stop();
                if ('1' === social_fblog_params.opacity) {
                    socialbox.css({
                        opacity: social_fblog_params.opacity_intensity
                    });
                }
                if ('0' === social_fblog_params.effect) {
                    socialbox.animate({
                        marginTop: 0
                    });
                } else {
                    socialbox.css({
                        position: 'absolute',
                        top: social_fblog_params.top_distance + 'px'
                    });
                }
            }
        });
    });
}(jQuery));
