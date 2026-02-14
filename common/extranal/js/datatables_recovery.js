(function ($) {
    "use strict";

    // Override DataTables default error handling
    if ($.fn.dataTable) {
        // 'none' suppresses the alert() call
        // We will handle errors via the 'error.dt' event below
        $.fn.dataTable.ext.errMode = 'none';

        $(document).on('error.dt', function (e, settings, techNote, message) {
            console.error('DataTables error:', message);

            // TechNote 7 usually means Invalid JSON (e.g. 403 HTML response instead of JSON)
            // TechNote 1 means Invalid JSON
            // If message mentions JSON or token
            if (techNote === 7 || (message && (message.indexOf('JSON') !== -1 || message.indexOf('token') !== -1))) {
                
                // Use SweetAlert if available (from global scope)
                if (typeof Swal !== 'undefined') {
                    Swal.fire({
                        title: 'Connection Issue',
                        text: 'We could not load the data. Your session may have expired.',
                        icon: 'warning',
                        confirmButtonText: 'Refresh Page',
                        showCancelButton: true,
                        cancelButtonText: 'Dismiss'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            location.reload();
                        }
                    });
                } else if (typeof alert !== 'undefined') {
                    // Fallback if Swal not loaded
                    if (confirm('Session might be expired. Refresh page?')) {
                        location.reload();
                    }
                }
            } else {
                // For other non-session errors, we might want to be less intrusive
                // or just log to console (already done).
                // Optionally show toastr if it's a real server error
                // if (typeof toastr !== 'undefined') {
                //     toastr.error('Error loading table data: ' + message);
                // }
            }
        });
    }

})(jQuery);
