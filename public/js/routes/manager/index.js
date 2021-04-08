// Calendar swiper
const swiper = new Swiper('.swiper-container', {
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
    allowTouchMove: false
});

// User Searchbar
var userSearchbar = document.getElementById('js-user-searchbar');
var userResults = document.querySelectorAll('.js-user-result');
searchbar(userSearchbar, userResults);
// Event Searchbar
var eventSearchbar = document.getElementById('js-event-searchbar');
var eventResults = document.querySelectorAll('.js-event-result');
searchbar(eventSearchbar, eventResults);