$(document).ready(() => {

    let url = window.location.pathname

    $('.nav-link').each((i, item) => {
        if ($(item).attr('href') === url) {
            $(item).addClass('active');
        }
    })

})