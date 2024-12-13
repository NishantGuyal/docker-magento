define(['vue'], function (Vue) {
    'use strict'

    return function (config, element) {
        new Vue({
            el: '#' + element.id,
            data: {
                message: config.message
            }
        })
    }
});