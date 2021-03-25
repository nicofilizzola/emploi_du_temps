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
    '18h00',
    '19h30'
]
var verificationTimes = [
    [verificationTimeValues[0], verificationTimeValues[1], false],
    [verificationTimeValues[1], verificationTimeValues[2], false],
    [verificationTimeValues[2], verificationTimeValues[3], false],
    // lunch break
    [verificationTimeValues[4], verificationTimeValues[5], false],
    [verificationTimeValues[5], verificationTimeValues[6], false],
    [verificationTimeValues[6], verificationTimeValues[7], false],
    [verificationTimeValues[7], verificationTimeValues[8], false],
];
var verification = document.getElementById('js-preference-verification');

function createVerificationText(preference, verificationWeekdays, verificationTimes, verification) {
    var prefixString = '';
    var preferenceString;
    var daysString;
    var timesString;
    var string;

    // set preferenceString
    if (preference) {
        preferenceString = 'disponible ';
    } else {
        preferenceString = 'indisponible ';
    }

    // set daysString
    var dayCounter = 0;
    var selectedNext;
    verificationWeekdays.forEach(element => {
        if (element[1]) {
        // if weekday selected
            selectedNext = 0;
            dayCounter++;

            if (dayCounter == 1) {
            // if first displayed weekday
                daysString = element[0];
                
            } else if (dayCounter < verificationWeekdays.length) {
            // any other case

                // verify if any other days are selected after this one
                var subCounter = 0;
                verificationWeekdays.forEach(value => {
                    subCounter++;
                    if (subCounter > dayCounter){
                    // do this for days ahead
                        if (value[1]){
                        // if other selected days left
                            selectedNext++;
                        }
                    }
                    
                });

                if (selectedNext >= 1) {
                    // if other selected elements ahead
                    daysString += ', ' + element[0];
                    

                } else {
                    daysString += ' et ' + element[0];
              
                }  
            } 

            // DE LUNDI A JEUDI
        }
    });

    if (dayCounter == verificationWeekdays.length) {
    // if all days selected
        daysString = 'toute la semaine';
        

    } else if (dayCounter == 0) {
    // if no days selected
        string = 'Vous n\'avez pas encore renseigné toutes les informations demandées...';

    }




    //set timesString
    var timeCounter = 0;
    var selectedNextTime;
    verificationTimes.forEach(element => {
        if (element[2]) {
        // if day selected
        
            selectedNextTime = false;
            timeCounter++;

            if (timeCounter == 1) {
            // if first displayed day
                timesString = ' de ' + element[0] + ' à ' + element[1];
                
            } else if (timeCounter < verificationTimes.length + 1) {
            // more than one but less than 5

                // verify if any other days are selected after this one
                var subCounter = 0;
                verificationTimes.forEach(value => {
                    if (subCounter > timeCounter){
                    // do this for days ahead
                        if (value[1]){
                        // if other selected days left
                            selectedNextTime = true;
                        }
                    }
                    subCounter++;
                });

                if (selectedNextTime) {
                    // if other selected elements ahead
                    timesString += ', de ' + element[0] + ' à ' + element[1];

                } else {
                    timesString += ' et de ' + element[0] + ' à ' + element[1];

                }

            } 
        }
    
    });

    if (timeCounter == verificationTimes.length) {
    // all selected
        timesString = ' toute la journée';

    } else if (timeCounter == 0) {
    // none selected
        string = 'Vous n\'avez pas encore renseigné toutes les informations demandées...';
    }






    

    // MASTER STRING
    if (string === undefined) {
    // If no error message
        string = prefixString + 'Je suis ' + preferenceString + daysString + timesString;
    }
    
    return verification.innerHTML = string;
}






















// Preference button check and color change
preferenceBtns = document.querySelectorAll('.js-preference-btn');
var preferenceRadioInput;

preferenceBtns.forEach(clicked => {
    clicked.addEventListener('click', function(){
        // undisable next buttons
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
                createVerificationText(preferenceSelected, verificationWeekdays, verificationTimes, verification); 
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
            });   
            createVerificationText(preferenceSelected, verificationWeekdays, verificationTimes, verification);  
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
var characterCounterLimit = noteTextbox.maxLength;

noteTextbox.addEventListener('input', function(){
    characterCounter.textContent = noteTextbox.value.length + '/' + characterCounterLimit;
});























// Preference verification for weekdays
weekdayBtns.forEach(function(element, key) {
// check in btns
    var checkbox = element.children[0];

    if (parseInt(element.id.replace(/\D/g, ""))){
    // only weekdays
        element.addEventListener('click', function(){
        // onclick
            checkbox.checked ? verificationWeekdays[key][1] = true : verificationWeekdays[key][1] = false;
            createVerificationText(preferenceSelected, verificationWeekdays, verificationTimes, verification);   

        });  
    }
});

// All btn
var weekdayAllBtn = document.getElementById('js-preference-weekday-btn-all');

weekdayAllBtn.addEventListener('click', function() {
    if (weekdayAllBtn.classList.contains('btn-dark')){
        verificationWeekdays.forEach(element => {
            element[1] = false;
        }); 
    } else if (weekdayAllBtn.classList.contains('btn-primary')){
        verificationWeekdays.forEach(element => {
            element[1] = true;
        });   
    }
    createVerificationText(preferenceSelected, verificationWeekdays, verificationTimes, verification);    
});




// Preference verification for times
timeBtns.forEach(function(element, key) {
    // check in btns
    var checkbox = element.children[0];

    if (parseInt(element.id.replace(/\D/g, ""))){
    // only weekdays
        element.addEventListener('click', function(){
        // onclick
            checkbox.checked ? verificationTimes[key - 1][2] = true : verificationTimes[key - 1][2] = false;
            createVerificationText(preferenceSelected, verificationWeekdays, verificationTimes, verification);   
        });  
    }
});

// All btn
var timesAllBtn = document.getElementById('js-preference-time-btn-all');

timesAllBtn.addEventListener('click', function() {
    if (timesAllBtn.classList.contains('btn-dark')){
        verificationTimes.forEach(element => {
            element[2] = false;
        }); 
    } else if (timesAllBtn.classList.contains('btn-primary')){
        verificationTimes.forEach(element => {
            element[2] = true;
        });   
    }
    createVerificationText(preferenceSelected, verificationWeekdays, verificationTimes, verification);  
});
















// endweek and startweek
var endWeekSelector =  document.getElementById('js-preference-endweek');
var startWeekSelector = document.getElementById('js-preference-startweek');
var endWeekCheckbox = document.getElementById('js-preference-endweek-checkbox');
var endWeekOptions =  endWeekSelector.children;
var startWeekAll = startWeekSelector.children[1];
var startWeekOptions =  startWeekSelector.children;

// Except and exceptend 
var exceptWeekCheckbox = document.getElementById('js-preference-exceptweek-checkbox');
var exceptEndWeekCheckbox = document.getElementById('js-preference-exceptendweek-checkbox');
var exceptWeekSelector = document.getElementById('js-preference-exceptweek');
var exceptEndWeekSelector = document.getElementById('js-preference-exceptendweek');
var exceptWeekOptions = exceptWeekSelector.children;
var exceptEndWeekOptions = exceptEndWeekSelector.children;

// functions
function checkboxSelectorManager(checkbox, selector) {
    if (checkbox.checked) {
        selector.disabled = false;
    } else {
        selector.disabled = true;
        selector.value = '';
    }
}

function exceptWeekCheckboxManager(startWeekSelector, endWeekCheckbox, endWeekSelector, exceptWeekCheckbox) {
    if (startWeekSelector.value == 'all' || startWeekSelector.value !== '' && endWeekCheckbox.checked && endWeekSelector.value !== '') {
        exceptWeekCheckbox.disabled = false;
    } else {
        exceptWeekCheckbox.disabled = true;
    }
}

function exceptEndWeekCheckboxManager(exceptEndWeekCheckbox, exceptWeekSelector) {
    console.log();
    if (exceptWeekCheckbox.checked && exceptWeekSelector.value !== '') {
        exceptEndWeekCheckbox.disabled = false;
    } else {
        exceptEndWeekCheckbox.disabled = true;
    }  
}

function endWeekOptionsManager(endWeekOptions, startWeekSelector) {
    Array.prototype.forEach.call(endWeekOptions, element => {
        if (parseInt(element.value) <= parseInt(startWeekSelector.value)) {
            element.hidden = true;
            element.disabled = true;
        } else {
            element.hidden = false;
            element.disabled = false;
        }
    });
}

function exceptOptionsManager(exceptWeekOptions, startWeekSelector, endWeekSelector, endWeekCheckbox) {
    Array.prototype.forEach.call(exceptWeekOptions, element => {
        if (parseInt(element.value) <= parseInt(startWeekSelector.value) || parseInt(element.value) >= parseInt(endWeekSelector.value)) {
            element.hidden = true;
            element.disabled = true;
        } else {
            element.hidden = false;
            element.disabled = false;
        }
    });
}

function exceptEndOptionsManager(exceptEndWeekOptions, exceptWeekSelector, endWeekSelector, endWeekCheckbox) {
    Array.prototype.forEach.call(exceptEndWeekOptions, element => {
        if (parseInt(element.value) <= parseInt(exceptWeekSelector.value) || parseInt(element.value) >= parseInt(endWeekSelector.value)) {
            element.hidden = true;
            element.disabled = true;
        } else {
            element.hidden = false;
            element.disabled = false;
        }
    });
}




// checkboxes

endWeekCheckbox.addEventListener('change', function(){

    // disable/undisable selector depending on checkbox's state
    checkboxSelectorManager(endWeekCheckbox, endWeekSelector);

    // toggle 'all' option in start selector
    // toggle last week option in start selector
    if (endWeekCheckbox.checked) {

        startWeekAll.hidden = true;
        startWeekAll.disabled = true;
        startWeekOptions[startWeekOptions.length - 1].hidden = true;
        startWeekOptions[startWeekOptions.length - 1].disabled = true;
    } else {
        startWeekAll.hidden = false;
        startWeekAll.disabled = false;
        startWeekOptions[startWeekOptions.length - 1].hidden = false;
        startWeekOptions[startWeekOptions.length - 1].disabled = false;

        // Uncheck except and exceptEnd if unchecked
        exceptWeekCheckbox.checked = false;
        exceptEndWeekCheckbox.checked = false;

        // Manage except and exceptEnd checkboxes and selectors's disabled state (activate)
        exceptWeekCheckboxManager(startWeekSelector, endWeekCheckbox, endWeekSelector, exceptWeekCheckbox);
        checkboxSelectorManager(exceptWeekCheckbox, exceptWeekSelector);
        exceptEndWeekCheckboxManager(exceptEndWeekCheckbox, exceptWeekCheckbox, exceptWeekOptions);
        checkboxSelectorManager(exceptEndWeekCheckbox, exceptEndWeekSelector);
    } 
});


exceptWeekCheckbox.addEventListener('change', function(){

    // manage selector's disabled state
    checkboxSelectorManager(exceptWeekCheckbox, exceptWeekSelector);
    console.log (exceptWeekSelector.value);
    // manage exceptWeek input
    if (!exceptWeekCheckbox.checked) {
        exceptEndWeekCheckbox.checked = false;
        checkboxSelectorManager(exceptEndWeekCheckbox, exceptEndWeekSelector);
        exceptEndWeekCheckboxManager(exceptEndWeekCheckbox, exceptWeekCheckbox, exceptWeekOptions);
    }
});


exceptEndWeekCheckbox.addEventListener('change', function(){
    // manage selector's disabled state
    checkboxSelectorManager(exceptEndWeekCheckbox, exceptEndWeekSelector);
});




// selectors

startWeekSelector.addEventListener('change', function(){
    // Hide and disable everything inside endWeekOptions that's under the selected value
    endWeekOptionsManager(endWeekOptions, startWeekSelector)


    // Disable endweek if selected 'all' or the last option
    if (startWeekSelector.value == 'all' || startWeekSelector.value == startWeekOptions.length -  2) {
        endWeekCheckbox.disabled = true;

    } else {
        endWeekCheckbox.disabled = false;

    }
    

    // Check conditions for except input activation, and hide except options lower than startweek value
    exceptWeekCheckboxManager(startWeekSelector, endWeekCheckbox, endWeekSelector, exceptWeekCheckbox);
    exceptOptionsManager(exceptWeekOptions, startWeekSelector, endWeekSelector, endWeekCheckbox);

});


endWeekSelector.addEventListener('change', function() {
    // Hide except options higher than selected endweek
    exceptOptionsManager(exceptWeekOptions, startWeekSelector, endWeekSelector, endWeekCheckbox);

    // Check conditions for except input activation
    exceptWeekCheckboxManager(startWeekSelector, endWeekCheckbox, endWeekSelector, exceptWeekCheckbox);
});


exceptWeekSelector.addEventListener('change', function() {
    // Check conditions for exceptEnd input activation
    exceptEndWeekCheckboxManager(exceptEndWeekCheckbox, exceptWeekCheckbox, exceptWeekOptions);

    // Hide exceptEnd options lower than except
    exceptEndOptionsManager(exceptEndWeekOptions, exceptWeekSelector, endWeekSelector, endWeekCheckbox)

});






























// Undisable save btn once everything is checked (week, day, time and prf state)
var inputs = document.querySelectorAll('button, select');
var preferencesSaveBtn = document.getElementById('js-preference-save-btn');

function areInputsSelected() {
    var state = false;
    var week = false;
    var weekday = false;
    var time = false;

    preferenceBtns.forEach(element => {
        if (!state) {
            if (element.children[0].checked) {
                state = true;
            }
        }
    });



    verificationWeekdays.forEach(element => {
        if (!weekday) {
            if (element[1]){
                weekday = true 
            }
        }
    });

    verificationTimes.forEach(element => {
        if (!time) {
            if (element[2]){
                time = true 
            }
        }
    });

    if (state && weekday && time) {
        return true;
    } else {
        return false;
    }
}

inputs.forEach(element => {
    element.addEventListener('click', function() {
        if (areInputsSelected(preferenceBtns, verificationWeekdays, verificationTimes)) {
            preferencesSaveBtn.disabled = false;
        } else {
            preferencesSaveBtn.disabled = true;
        } 
    });
})


















