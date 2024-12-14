/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery'
], function ($) {
    'use strict';

    return function (config, element) {
        $(element).on('submit', function (e) {
            if ($(this).valid()) {
                if (confirm('Are you sure to submit a review?')) {
                    $(this).find('.submit').attr('disabled', true);
                }
                else {
                    e.preventDefault();
                }

            }
        });
    };
});
