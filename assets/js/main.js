// close flash message
$('.message .close')
    .on('click', function () {
        $(this)
            .closest('.message')
            .transition('fade')
        ;
    })
;

// hover on cards in home page
$('.special.cards .image').dimmer({
    on: 'hover'
});

// function searchMovie(){
$("#searchbtn").on('click', function (e) {
    const searchvalue = $(this).prev('#search').val();

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
})

// function searchApi(){
//     $("#searchIcon").on('click', function (e) {
//     const searchMovie = $(this).prev('#searchTitle').val();
//     // console.log(searchMovie);
//
//     $.ajax({
//         type: "POST",
//         url: "/api",
//         data: {title: searchMovie},
//         success: function (response) {
//             $('.container3').html(response);
//             // $('.container3').append(response.data);
//             // console.log(response);
//         },
//         error: function (response) {
//             console.log(response);
//         },
//     })
// })
//
// }

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
            // $('.container3').append(response.data);
            // console.log(response);
        },
        error: function (response) {
            console.log(response);
        },
    })
})


