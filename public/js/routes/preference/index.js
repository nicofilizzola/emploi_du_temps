// Preference button check and color change
preferenceBtns = document.querySelectorAll('.js-preference-btn');
var preferenceRadioInput;

preferenceBtns.forEach(clicked => {
    clicked.addEventListener('click', function(){
        preferenceBtns.forEach(element => {
            preferenceRadioInput = element.children[0];
            if (element.id !== clicked.id){
            // not clicked button
                // radio uncheck
                preferenceRadioInput.checked = false;
                // style
                if (element.id == 'js-preference-btn' && element.classList.contains('btn-success')) {
                    element.classList.remove('btn-success');
                } else if (element.id == 'js-unavailability-btn' && element.classList.contains('btn-danger')) {
                    element.classList.remove('btn-danger');
                }
                element.classList.add('btn-dark');
            } else {
            // clicked button 
                // radio check
                preferenceRadioInput.checked = true;
                // style
                if (element.id == 'js-preference-btn') {
                    element.classList.add('btn-success');
                } else if (element.id == 'js-unavailability-btn') {
                    element.classList.add('btn-danger');
                }
                element.classList.remove('btn-dark');
            }
        });
    });
});



// Buttons check and color change setup
function checkAndReplaceClass(element, checkbox, checkboxState, oldClass, newClass) {
    checkbox.checked = checkboxState;
    element.classList.replace(oldClass, newClass);
}

function checkBtnManager(btns, idPrefix) {
    var choiceCheckbox;
    var choiceBtnIndex;
    var selectAllId = idPrefix + 'all';
    var selectSpecificId = idPrefix + 'specific';
    var disabledClass = 'btn-dark'
    var activeClass = 'btn-primary'

    btns.forEach(clicked => {
        clicked.addEventListener('click', function() {
            btns.forEach(element => {
                choiceCheckbox = element.children[0];
                choiceBtnIndex = parseInt(element.id.replace(/\D/g, ""));
                
                // For all buttons:
                if (element.id == clicked.id) {
                // for clicked button
                    if (element.classList.contains(disabledClass) && !choiceCheckbox.checked) {
                        checkAndReplaceClass(element, choiceCheckbox, true, disabledClass, activeClass);
                
                    } else if (element.classList.contains(activeClass) && choiceCheckbox.checked) {
                        checkAndReplaceClass(element, choiceCheckbox, false, activeClass, disabledClass);
                
                    }
                }
    
                if (clicked.id != selectAllId && element.id == selectAllId && element.classList.contains('btn-primary')) {
                // check if 'all' buttons is checked (and not clicked) and uncheck it if that's the case   
                    element.classList.replace('btn-primary', 'btn-dark');
                }
    
                // For 'all' button
                if (clicked.id == selectAllId && !isNaN(choiceBtnIndex)) {
                // If it's been clicked and current loop element is a regular button
                    if (clicked.classList.contains('btn-dark')) {
                    // 'all' button doesn't have a checkbox
                        if (element.classList.contains('btn-dark') && !choiceCheckbox.checked) {
                            checkAndReplaceClass(element, choiceCheckbox, true, disabledClass, activeClass);

                        }
    
                    } else if (element.classList.contains('btn-primary')) {
                        checkAndReplaceClass(element, choiceCheckbox, false, activeClass, disabledClass);
        
                    }
                }
    
                // If 'specific' button exists in form
                if (document.getElementById(selectSpecificId) !== undefined) {
                    // onclick
                    if (clicked.id == selectSpecificId) {
                        // display 'specific' inputs

                        
                        // uncheck all other btns
                        if (clicked.id !== element.id) {
                            checkAndReplaceClass(element, choiceCheckbox, false, activeClass, disabledClass);
                        }
            
                    } else {
                    // if any other button was clicked, uncheck 'specific' btn
                        if (element.id == selectSpecificId && element.classList.contains('btn-primary') && choiceCheckbox) {
                            checkAndReplaceClass(element, choiceCheckbox, false, activeClass, disabledClass);

                        }
                    }
                }   
            });     
        });
    });
}



// Weekday buttons check and color change
var weekdayBtns = document.querySelectorAll('.js-preference-weekday-btn');
checkBtnManager(weekdayBtns, 'js-preference-weekday-btn-');

// Time buttons check and color change
var timeBtns = document.querySelectorAll('.js-preference-time-btn');
checkBtnManager(timeBtns, 'js-preference-time-btn-');



// Note character counter
var noteTextbox = document.getElementById('js-preference-note');
var characterCounter = document.getElementById('js-preference-note-counter');
var characterCounterLimit = 150;

noteTextbox.addEventListener('input', function(){
    characterCounter.textContent = noteTextbox.value.length + '/' + characterCounterLimit;
})


