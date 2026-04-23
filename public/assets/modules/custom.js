/**
 * Shared tenant UI helpers loaded before module scripts (color.js, size.js, user.js, …).
 * Defines: currentPaginationUrl, fetchdata, showPreloader, hidePreloader, before_submit,
 * after_submit, show_notification.
 */
/* global jQuery, toastr */
var currentPaginationUrl = window.location.href || '';

var __ajaxPreloaderDepth = 0;

function showPreloader() {
    __ajaxPreloaderDepth++;
    var $ = window.jQuery;
    if (!$) {
        return;
    }
    var $el = $('#ajax-preloader-overlay');
    if (!$el.length) {
        $('body').append(
            '<div id="ajax-preloader-overlay" class="fixed inset-0 z-[10050] flex items-center justify-center bg-black/40" style="display:none;">' +
                '<div class="h-12 w-12 animate-spin rounded-full border-4 border-pink-200 border-t-pink-500" role="status" aria-label="Loading"></div>' +
                '</div>'
        );
        $el = $('#ajax-preloader-overlay');
    }
    $el.show();
}

function hidePreloader() {
    __ajaxPreloaderDepth = Math.max(0, __ajaxPreloaderDepth - 1);
    if (__ajaxPreloaderDepth === 0 && window.jQuery) {
        window.jQuery('#ajax-preloader-overlay').hide();
    }
}

function before_submit() {
    if (window.jQuery) {
        window.jQuery('.submit_form').prop('disabled', true);
    }
}

function after_submit() {
    if (window.jQuery) {
        window.jQuery('.submit_form').prop('disabled', false);
    }
}

function fetchdata(url) {
    var $ = window.jQuery;
    if (!$) {
        window.location.href = url || window.location.href;
        return;
    }

    var target = url || currentPaginationUrl || window.location.href;

    $.ajax({
        url: target,
        type: 'GET',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        dataType: 'html',
        success: function (html) {
            try {
                var doc = new DOMParser().parseFromString(html, 'text/html');
                var newTable = doc.getElementById('data-table');
                var newPag = doc.getElementById('data-pagination');
                if (newTable) {
                    $('#data-table').html(newTable.innerHTML);
                }
                if (newPag) {
                    $('#data-pagination').html(newPag.innerHTML);
                }
                currentPaginationUrl = target;
                bindDressPaginationAjax();
            } catch (e) {
                window.location.assign(target);
            }
        },
        error: function () {
            window.location.assign(target);
        },
    });
}

function bindDressPaginationAjax() {
    var $ = window.jQuery;
    if (!$) {
        return;
    }
    $('#data-pagination')
        .off('click.dressPag')
        .on('click.dressPag', 'a', function (e) {
            var href = $(this).attr('href');
            if (!href || href === '#' || $(this).parent().hasClass('disabled')) {
                return;
            }
            e.preventDefault();
            fetchdata(href);
        });
}

jQuery(function () {
    currentPaginationUrl = window.location.href;
    bindDressPaginationAjax();
});

function show_notification(type, message) {
    var msg = message || '';
    if (typeof toastr === 'undefined') {
        if (msg) {
            window.alert(msg);
        }
        return;
    }
    if (type === 'success') {
        toastr.success(msg);
    } else if (type === 'error') {
        toastr.error(msg);
    } else if (type === 'warning') {
        toastr.warning(msg);
    } else {
        toastr.info(msg);
    }
}
