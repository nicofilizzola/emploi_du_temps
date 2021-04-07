// Calendar swiper
const swiper = new Swiper('.swiper-container', {
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    allowTouchMove: false
});

// Searchbar
var userSearchbar = document.getElementById('js-user-searchbar');
var userResults = document.querySelectorAll('.js-user-result');
searchbar(userSearchbar, userResults);