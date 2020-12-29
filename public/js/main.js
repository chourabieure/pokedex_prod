$(document).ready(function () {
    let bg_dark = document.createElement('div')
    bg_dark.classList.add('bg_dropdown');
    document.querySelector('body').appendChild(bg_dark);


    $('[data-toggle="tooltip"]').tooltip();

    $(".bg_dropdown").click(function () {
        $(".menu-elements").toggleClass("active");
        $(".bg_dropdown").toggleClass("bg_dropdown_toggle")
        $(".bg-background").toggleClass('triggered')
        $('.menu-trigger').toggleClass('menu-trigger-active')
        $('body').toggleClass('overflow-hidden');

    })
});
