define(
    [
        'ko',
        'Balticode_Billink/js/view/checkout/summary/billink_fee'
    ],
    function (ko, Component) {
        'use strict';

        return Component.extend({
            isDisplayed: function () {
                return this.isFullMode();
            }
        });
    }
);