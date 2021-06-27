$(document).ready(() => {

    let url = window.location.pathname

    $('.nav-link').each((i, item) => {
        console.log($(item).attr('href'))
        if ($(item).attr('href') === url) {
            $(item).addClass('active');
        }
    })

})