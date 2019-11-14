$(document).ready(function() {

    var $container = $('div#trick-media');
    var video_index = $container.find('iframe').length;

    $('#add-video').click(function(e) {
        addItem('video');
        e.preventDefault();
        return false;
    });

    function addItem(type) {

        index = video_index;
        video_index++;

        var $container = $('#media-add-'+type);
        var template = $container.attr('data-prototype')
            .replace(/__name__label__/g, type+' n°' + (index+1))
            .replace(/__name__/g,        index)
        ;
        var $prototype = $(template);

        addDeleteLink($prototype);
        $container.append($prototype);
    }

    function addDeleteLink($prototype) {
        var $deleteLink = $('<a href="#" class="btn btn-sm btn-danger">Supprimer</a>');
        $prototype.append($deleteLink);

        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault();
            return false;
        });
    }

    $('.media-delete').click(function (e) {
        e.preventDefault();
        var media_url = $(this).attr('data-media');
        var media_id = $(this).attr('data-msg');
        $('#media-'+media_url).remove();
        $('#'+media_id).remove();
    })

    $('.media-edit').click(function (e) {
        e.preventDefault();
        var media_id = $(this).attr('data-msg');
        $('#'+media_id).get(0).type = 'text';
    })


});