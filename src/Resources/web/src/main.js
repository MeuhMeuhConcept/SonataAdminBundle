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
});