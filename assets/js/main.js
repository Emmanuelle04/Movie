$('.message .close')
    .on('click', function () {
        $(this)
            .closest('.message')
            .transition('fade')
        ;
    })
;

$(document).ready(function () {
    $('.ui simple dropdown item').dropdown();
});

$('.special.cards .image').dimmer({
    on: 'hover'
});

$('#searchbtn')
    .on('click', function () {
        var searchvalue = $(this).prev('#search').val();

        $.ajax({
            type: "POST",
            url: "/search/movie",
            data: {search: searchvalue},
            success: function (response) {
                $('.container1').html(response.searchMovie);
                console.log(response);
            },
            error: function (response) {
                console.log(response);
            }
        });

    });



