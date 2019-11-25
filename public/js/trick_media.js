$(document).ready(function() {

    var $container = $('div#trick-media');
    var image_index = $container.find('img').length;
    var video_index = $container.find('iframe').length;

    $('#add-image').click(function(e) {
        addItem('image');
        e.preventDefault();
        return false;
    });

    $('#add-video').click(function(e) {
        addItem('video');
        e.preventDefault();
        return false;
    });

    function addItem(type) {
        if(type == 'image') {
            index = image_index;
            image_index++;
        } else {
            index = video_index;
            video_index++;
        }

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
    });



    $(".image-pop").on("click", function() {
        $('#imagepreview').attr('src', $(this).attr('src'));
        $('#imagemodal').modal('show');
    });
});

function showMainImage()
{
    $('#trick_images_0_comment').val(new Date());
    $('#image_0').removeClass('d-none')
}
function showOtherImage($index)
{
    $('#trick_images_' + $index + '_comment').val(new Date());
    $('#image_' + $index).removeClass('d-none')
}