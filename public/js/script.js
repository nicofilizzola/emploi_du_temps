/*

SESSION

*/

// Submit form
var sessionForm = document.getElementById('js-session-form');
var sessionFormSubmit = document.getElementById('js-session-form-submit');

sessionFormSubmit.addEventListener('click', function(){
    sessionForm.submit();
});

// Event searchbar
var sessionSearchbar = document.getElementById('js-session-searchbar');
var sessionEvents = document.getElementById('js-session-events');
var eventButtons = document.querySelectorAll('.js-event-button');

sessionSearchbar.addEventListener('input', function(){
    eventButtons.forEach(element => {
        if (element.textContent.toLowerCase().includes(sessionSearchbar.value.toLowerCase())) {
            if (element.classList.contains('d-none')){
                element.classList.remove('d-none');
                element.classList.add('d-flex');
            }
        } else {
            if (element.classList.contains('d-flex')){
                element.classList.remove('d-flex');
                element.classList.add('d-none');
            }
        }
    });
});

// eventMode
var eraserModeBtn = document.getElementById('js-eraser-mode-btn');
var dayBlocks = document.querySelectorAll('.day');
var eventMode, eventSelected, eventModeCounter, startDate, endDate;

function getEventAmount(){
    return eventButtons.length;
}

function checkInBetween(startDate, endDate, eventMode, eventSelected = null) {
    // eventMode == true ? assignEventMode : eraserMode
    var check, indicator, uncheck;

    for (var i = startDate; i <= endDate; i++) {
        if (eventMode) {
            check = document.getElementById('day-' + i + '-event-' + eventSelected);
            indicator = check.nextElementSibling.nextElementSibling;

            check.checked = true;
            if (indicator.classList.contains('d-none')){
                indicator.classList.remove('d-none');
            }
        } else {
            for (let event = 1; event <= getEventAmount(); event++){
                check = document.getElementById('day-' + i + '-event-' + event + '-delete');
                indicator = check.nextElementSibling;
                uncheck = check.previousElementSibling;

                check.checked = true;
                uncheck.checked = false;
                if (!indicator.classList.contains('d-none')){
                    indicator.classList.add('d-none');
                }                
            }
        }   
    }
}

eraserModeBtn.addEventListener('click', function(){
    eventMode = false;
    eventModeCounter = 0;
});

eventButtons.forEach(element => {
    element.addEventListener('click', function(){
        eventMode = true;
        eventModeCounter = 0;
        eventSelected = parseInt(element.id.replace(/\D+/g, ''));
    });
});

dayBlocks.forEach(element => {
    if (element.id){
    // only weekdays have id
        element.addEventListener('click', function(){
            if (eventModeCounter == 0) {
                startDate = parseInt(element.id.replace(/\D+/g, ''));
                eventModeCounter++;
            } else if (eventModeCounter == 1) {
                endDate = parseInt(element.id.replace(/\D+/g, ''));
                startDate < endDate ? checkInBetween(startDate, endDate, eventMode, eventSelected) : checkInBetween(endDate, startDate, eventMode, eventSelected);
                eventModeCounter = 0;
            }
        }); 
    }
});

