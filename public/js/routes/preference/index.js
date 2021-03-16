// Verification initialization
var preferenceSelected;
var verificationWeekdays = [
    ['lundi', false],
    ['mardi', false],
    ['mercredi', false],
    ['jeudi', false],
    ['vendredi', false]
];
var verificationTimeValues = [
    '8h00',
    '9h30',
    '11h00',
    '12h30',
    '13h30',
    '15h00',
    '16h30',
    '18h00'
]
var verificationTimes = [
    [verificationTimeValues[0], verificationTimeValues[1], false],
    [verificationTimeValues[1], verificationTimeValues[2], false],
    [verificationTimeValues[2], verificationTimeValues[3], false],
    // lunch break
    [verificationTimeValues[4], verificationTimeValues[5], false],
    [verificationTimeValues[5], verificationTimeValues[6], false],
    [verificationTimeValues[6], verificationTimeValues[7], false]
];
var verification = document.getElementById('js-preference-verification');

function createVerificationText(preference, verificationWeekdays, times, verification) {
    var preferenceString;
    var daysString;
    var timeString;
    var string;

    // set preferenceString
    if (preference) {
        preferenceString = 'disponible ';
    } else {
        preferenceString = 'indisponible ';
    }

    // set daysString
    var dayCounter = 0;
    verificationWeekdays.forEach(element => {
        if (element[1]) {
        // if weekday selected
            dayCounter++;

            if (dayCounter == 1) {
            // if first displayed weekday
                daysString = element[0];
                
            } else if (dayCounter < 5) {
            // any other case

                // verify if any other days are selected after this one
                var subCounter = 0;
                var selectedNext = false;
                verificationWeekdays.forEach(value => {
                    if (subCounter > dayCounter){
                    // do this for days ahead
                        if (value[1]){
                        // if other selected days left
                            selectedNext = true;
                        }
                    }
                    subCounter++;
                });

                if (selectedNext) {
                    // if other selected elements ahead
                    daysString += ', ' + element[0];

                } else {
                    daysString += ' et ' + element[0];

                }  

            } else if (dayCounter == 5) {
            // if all days selected
                daysString = 'tous les jours';
            }

            // DE LUNDI A JEUDI
        } else if (dayCounter == 0) {
        // if no days selected
            string = 'Vous n\'avez pas encore renseigné toutes les informations demandées...'
            //return verification.innerHTML = 'no';
        }

        
    });



    //set timesString
    var timeCounter = 0;
    verificationTimes.forEach(element => {
    if (element[2]) {
    // if day selected
        timeCounter++;

        if (timeCounter == 1) {
        // if first displayed day
            timesString = ' de ' + element[0];
            
        } else if (timeCounter <= 5) {
        // any other case

            // verify if any other days are selected after this one
            var subCounter = 0;
            var selectedNext = false;
            verificationTimes.forEach(value => {
                if (subCounter > timeCounter){
                // do this for days ahead
                    if (value[1]){
                    // if other selected days left
                        selectedNext = true;
                    }
                }
                subCounter++;
            });

            if (selectedNext) {
                // if other selected elements ahead
                timesString += ', ' + element[0];

            } else {
                timesString += ' et ' + element[0];

            }  
        }   
    }

        

    if (string === undefined) {
    // If no error message
        string = 'Je suis ' + preferenceString + daysString + ' de 8h00 à 12h30' ;
    }
    
    return verification.innerHTML = string;
    
    });
}



// Preference button check and color change
preferenceBtns = document.querySelectorAll('.js-preference-btn');
var preferenceRadioInput;

preferenceBtns.forEach(clicked => {
    clicked.addEventListener('click', function(){
        // undisable all other buttons
        document.querySelectorAll('.js-preference-onclick-undisable').forEach(element => {
            element.disabled = false;
        });

        preferenceBtns.forEach(element => {
            preferenceRadioInput = element.children[0];
            if (element.id !== clicked.id){
            // not clicked button
                // radio uncheck
                preferenceRadioInput.checked = false;
                // style
                if (element.id == 'js-preference-btn' && element.classList.contains('btn-success')) {
                    element.classList.remove('btn-success');
                    
                    // Send data to verification 
                    preferenceSelected = false;
                } else if (element.id == 'js-unavailability-btn' && element.classList.contains('btn-danger')) {
                    element.classList.remove('btn-danger');

                    // Send data to verification
                    preferenceSelected = true;
                }
                createVerificationText(preferenceSelected, verificationWeekdays, '8h30', verification); 
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
                    // Other: Send data to verification
                    verificationWeekdays.forEach(element => {
                        element[1] = true;
                    });
    
                    } else if (element.classList.contains('btn-primary')) {
                        checkAndReplaceClass(element, choiceCheckbox, false, activeClass, disabledClass);

                        // Other: Send data to verification
                        verificationWeekdays.forEach(element => {
                            element[1] = false;
                        });
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

                        // Other: Send data to verification
                        verificationWeekdays.forEach(element => {
                            element[1] = false;
                        });
            
                    } else {
                    // if any other button was clicked, uncheck 'specific' btn
                        if (element.id == selectSpecificId && element.classList.contains('btn-primary') && choiceCheckbox) {
                            checkAndReplaceClass(element, choiceCheckbox, false, activeClass, disabledClass);

                        }
                    }
                }   
                createVerificationText(preferenceSelected, verificationWeekdays, 'de 8h00 à 5h00', verification);
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
});



// Preference verification
weekdayBtns.forEach(function(element, key) {
// check in btns
    var checkbox = element.children[0];

    if (parseInt(element.id.replace(/\D/g, ""))){
    // only weekdays
        element.addEventListener('click', function(){
        // onclick
            checkbox.checked ? verificationWeekdays[key][1] = true : verificationWeekdays[key][1] = false;
            createVerificationText(preferenceSelected, verificationWeekdays, '8h30', verification);   

        });  
    }
});



// GOAL
/* Display string with selected checkboxes */


