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
















// endweek disable with checkbox
var endWeekSelector =  document.getElementById('js-preference-endweek');
var endWeekOptions =  endWeekSelector.children;
var endWeekCheckbox = document.getElementById('js-preference-endweek-checkbox');
var startWeek = document.getElementById('js-preference-startweek');
var startWeekAll = startWeek.children[1];

// disable and hide if checkbox checked and hide all btn for first
endWeekCheckbox.addEventListener('change', function(){
    if (endWeekCheckbox.checked) {
        endWeekSelector.disabled = false;
        startWeekAll.hidden = true;
        startWeekAll.disabled = true;
    } else {
        endWeekSelector.disabled = true;
        startWeekAll.hidden = false;
        startWeekAll.disabled = false;
    }
});

// display only higher values in end select than selected value in start selectbox
startWeek.addEventListener('change', function(){
    Array.prototype.forEach.call(endWeekOptions, element => {
        if (parseInt(element.value) <= parseInt(startWeek.value)) {
            element.hidden = true;
            element.disabled = true;
        } else {
            element.hidden = false;
            element.disabled = false;
        }
    });

    // Block endweek checkbox if all weeks selected
    startWeek.value == 'all' ? endWeekCheckbox.disabled = true : endWeekCheckbox.disabled = false;
});


//// BLOCK LAST WEEK IF ENDWEEK CHECKED







