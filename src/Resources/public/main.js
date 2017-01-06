$( document ).ready(function() {
    $('.textarea-frame').each(function (idx, item) {
        var $item = $(item);
        var $iframe = $('<iframe style="width:100%; height: 200px; border:0px;">');

        var content = $item.html();

        $item.html($iframe);

        setTimeout( function() {
            var doc = $iframe[0].contentWindow.document;
            var $body = $('body', doc);
            $body.addClass('tinymce');
            $body.html(content);

            var $head = $('head', doc);
            $.each($item.data('stylesheets').split(','), function (i, stylesheet) {
                var s = $('<link rel="stylesheet" href="'+stylesheet+'">');
                $head.append(s);
            });
        }, 1 );

    });

    $('input[type=text][data-limits], textarea[data-limits]').each(function(idx, item) {
        var limits = $(item).data('limits').split('|');

        var $helper = $('<span>')
            .addClass('label')
            .insertAfter($(item))
            .data('widget', $(item))
            .data('limits', limits)
            ;

        updateLimits($helper);

        $(item).on('keyup keydown change', function(){
            updateLimits($helper);
        });
    });

    function updateLimits(helper)
    {
        var limits = helper.data('limits');
        var cs = helper.data('widget').val().length;
        helper.html(cs);
        helper.removeClass('label-success label-warning label-danger');

        if (limits[1] !== undefined && cs > limits[1]) {
            helper.addClass('label-danger');
        } else if (cs > limits[0]) {
            helper.addClass('label-warning');
        } else {
            helper.addClass('label-success');
        }
    }
});
