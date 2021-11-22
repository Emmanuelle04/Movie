// Close flash message
$('.message .close')
    .on('click', function () {
        $(this)
            .closest('.message')
            .transition('fade')
        ;
    })
;

// Hover on cards in home page
$('.special.cards .image').dimmer({
    on: 'hover'
});

// Search Movies in database
$("#searchbtn").on('click', function (e) {
    const searchvalue = $(this).prev('#search').val();

    $.ajax({
        type: "POST",
        url: "/search/movie",
        data: {search: searchvalue},
        success: function (response) {
            $('.container1').html(response.searchMovie);
            $('.special.cards .image').dimmer({
                on: 'hover'
            });
        },
        error: function (response) {
            console.log(response);
        }
    });
})

// Search Movies from Movie Api
$("#searchIcon").on('click', function (e) {
    const searchMovie = $(this).prev('#searchTitle').val();
    // var searchMovie = "hitman";
    console.log(searchMovie);

    $.ajax({
        type: "POST",
        url: "/api",
        data: {title: searchMovie},
        success: function (response) {
            $('.container3').html(response);
            // console.log(response);
        },
        error: function (response) {
            console.log(response);
        },
    })
})


