define(['vue', 'jquery', 'Nishant_JsFun/js/jquery-log'], function (Vue, $) {
    'use strict';

    $.log('Testing output to the console');
    
    return function (config, element) {
        return new Vue({
            el: '#' + element.id,
            data: {
                message: config.message
            }
        });
    };
    // However, there's no way of knowing which library loads first, possibly
    // causing the jQuery plugin to be loaded before the jQuery library loads.
    // This can cause unexpected results when executing the following code:
});