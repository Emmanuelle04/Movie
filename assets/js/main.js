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

// Search Movies by title from Movie Api
$("#searchIcon").on('click', function (e) {
    const searchMovie = $(this).prev('#searchTitle').val();

    $.ajax({
        type: "POST",
        url: "/api",
        data: {title: searchMovie},
        success: function (response) {
            $('.container3').html(response);
            $('.message .close')
                .on('click', function () {
                    $(this)
                        .closest('.message')
                        .transition('fade')
                    ;
                })
            ;
            // console.log(response);
        },
        error: function (response) {
            console.log(response);
        },
    })
})

// Search Movies by id from Movie Api
$("#searchBtn").on('click', function (e) {
    const searchID = $(this).prev('#searchMovie').val();
    console.log(searchID);

    $.ajax({
        type: "POST",
        url: "/search/movie/id",
        data: {search: searchID},
        success: function (response) {
            $('.container3').html(response);
            $('.message .close')
                .on('click', function () {
                    $(this)
                        .closest('.message')
                        .transition('fade')
                    ;
                })
            ;
            // console.log(response);
        },
        error: function (response) {
            console.log(response);
        },
    })
})

//Save movie in database
$(document).on('click', '#saveBtn',  function () {
    // define array genre
let genre = [];
    $( ".meta span.image" ).each(function() {
        // access element, access text inside element, replace \n by "", remove white space
        let value = $(this)
            .text()
            .replace(/[\n]+/g, "")
            .trim();

        // insert value in array
        genre.push(
            value
        );
    })

    const data ={
        poster: $("#movie-poster").attr('src'),
        title: $("#movie-title").html(),
        Genre: genre,
        released: $("#movie-released").html(),
        director: $("#movie-director").html(),
        plot: $("#movie-plot").html(),
        imdbID: $("#searchMovie").val(),
    }

    $.ajax({
        type: "POST",
        url: "/save/movie",
        data: {
            movie: data,
        },
        success: function (response) {
            $('.message .close')
            $('#saveBtn').parent().fadeOut("slow");
        },
        error: function (response) {
            console.log(response);
        },
    })
})
