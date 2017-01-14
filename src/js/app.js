//=include lib/jquery.min.js
//=include lib/slick.min.js
//=include lib/svgxuse.min.js
//=include lib/iframeResizer.min.js
//=include lib/popup.js

$(document).ready(function() {
    var popup = new Popup();
    var callbackForm  = $('#callback-form');

    $('[data-popup]').each(function() {
        var $this = $(this);
        $this.on('click', function(e) {
            popup.open($this.data('popup'));
            return false;
        });
    });

    $('.main-slider__img').slick({
        slidesToShow: 1,
        slidesToScroll: 1,
        fade: true,
        arrows: false,
        asNavFor: '.main-slider__sync',
        draggable: false,
    });

    $('.main-slider__sync').slick({
        slidesToShow: 4,
        slidesToScroll: 1,
        asNavFor: '.main-slider__img',
        centerMode: false,
        focusOnSelect: true,
        infinite: false,
        prevArrow: false,
        nextArrow: false,

    })

    $('#iFrame1').iFrameResize({
        log: false
    });

    $('#callback form, .tour__form').on('submit', function(e) {
        e.preventDefault();

        var post_data = {
            custom_U5736: e.target[0].value,
            custom_U5746: e.target[1].value
        }

        $.post('scripts/form-u5734.php', post_data, function(response) {
            if (response.FormResponse.success) {
                $(e.target).append('<p class="text-center btn-primary">Сообщение отправлено</p>');
                setTimeout(function() {
                    $(e.target).find('p').remove();
                }, 2000);
            } else {
                $(e.target).append('<p class="text-center btn-danger">Произошла ошибка</p>');
                setTimeout(function() {
                    $(e.target).find('p').remove();
                }, 2000);
            }
        }, 'json');

    });

    $('#form-header').on('submit', function(e) {
        e.preventDefault();

        var post_data = {
            custom_U5746: e.target[0].value,
        }


        $.post('scripts/form-header.php', post_data, function(response) {
            if (response.FormResponse.success) {
                $(e.target).append('<p class="text-center btn-primary">Сообщение отправлено</p>');
                setTimeout(function() {
                    $(e.target).find('p').remove();
                }, 2000);
            } else {
                $(e.target).append('<p class="text-center btn-danger">Произошла ошибка</p>');
                setTimeout(function() {
                    $(e.target).find('p').remove();
                }, 2000);
            }
        }, 'json');

    });


});
