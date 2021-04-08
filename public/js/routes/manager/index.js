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
// Equipment Request Searchbar
var equipmentReqSearchbar = document.getElementById('js-equipmentReq-searchbar');
var equipmentReqResults = document.querySelectorAll('.js-equipmentReq-result');
searchbar(equipmentReqSearchbar, equipmentReqResults);




var prefTrigger = document.querySelectorAll('.js-preference-trigger');

prefTrigger.forEach(element => {
    element.addEventListener('mouseover', function() {
        index = this.id.split('-'); 
        console.log()
        document.getElementById('js-block-' + index[1] + '-' + index[2]).classList.remove('transparent');
    });

    element.addEventListener('mouseout', function() {
        index = this.id.split('-'); 
        document.getElementById('js-block-' + index[1] + '-' + index[2]).classList.add('transparent');
    });
})

