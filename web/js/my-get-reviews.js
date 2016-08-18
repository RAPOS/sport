$(document).ready(function() {
    var id;
    var uid;
    var ajax;
    var selector;

    $('.reviews').hover(function() {
        id = $(this).data('id');
        uid = $(this).data('uid');
        selector = $('#review-'+id+'-uid-'+uid).find('.last-review-user');
        var selector_if = selector.find('.block');

        if (selector_if.length) {
            selector.show();
        } else {
            if (ajax)
                ajax.abort();

            ajax = $.ajax({
                url: '/ajax/get-last-review-user',
                method: 'POST',
                dataType: 'html',
                data: {'uid': uid},
                success: function(result) {
                    selector.append(result).show();
                }
            });
        }
    }, function() {
        id = $(this).data('id');
        uid = $(this).data('uid');
        selector = $('#review-'+id+'-uid-'+uid).find('.last-review-user');

        selector.fadeOut(500, function() {
            selector.mouseenter(function() {
                $(this).stop();
            });
        });
    });
});