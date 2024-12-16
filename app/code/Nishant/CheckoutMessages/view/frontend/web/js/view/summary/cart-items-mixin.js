define([], function () {
    'use strict';
    return function (Component) {
        return Component.extend({
            isItemsBlockExpanded: function () {
                // this._super();
                return true;
            }
        });
    };
});