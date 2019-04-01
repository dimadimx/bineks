(function ($) {
    $(document).on('click', '#ajax-pagination a', function (e) {
        e.preventDefault();
        $.ajax({
            url: ajaxPagination.ajaxUrl,
            type: 'post',
            data: {
                action: 'ajax_pagination',
                query_vars: ajaxPagination.queryVars,
            },
            success: function (result) {
                $('#events').html(result);
            }
        })
    });
})(jQuery);