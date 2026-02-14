"use strict";
$(document).ready(function() {
    // Global AJAX error handler for CSRF token expiration and session issues
    $(document).ajaxError(function(event, xhr, settings, error) {
        if (xhr.status === 403) {
            // CSRF token expired or invalid
            console.warn('CSRF token expired, refreshing page...');
            if (confirm('Your session token has expired. Click OK to refresh the page.')) {
                window.location.reload();
            }
        } else if (xhr.status === 401) {
            // Session expired
            console.warn('Session expired, redirecting to login...');
            window.location.href = 'auth/login';
        }
    });
    
    // Setup AJAX to include fresh CSRF token in all requests
    $.ajaxSetup({
        beforeSend: function(xhr, settings) {
            if (!/^(GET|HEAD|OPTIONS|TRACE)$/i.test(settings.type)) {
                // Refresh CSRF token from cookie for non-GET requests
                var csrfToken = $.cookie('csrf_cookie_name');
                if (csrfToken && settings.data) {
                    if (typeof settings.data === 'string' && settings.data.indexOf('csrf_test_name') === -1) {
                        settings.data += '&csrf_test_name=' + encodeURIComponent(csrfToken);
                    }
                }
            }
        }
    });
});
