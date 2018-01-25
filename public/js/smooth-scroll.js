$(document).ready(function () {
    $('a.nav-links').on('click', function (event) {
        var target = $( $(this).attr('href') );

        if( target.length ) {
            event.preventDefault();
            $('html, body').animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
});


//# sourceMappingURL=smooth-scroll.js.map
