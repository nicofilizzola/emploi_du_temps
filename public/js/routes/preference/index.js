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
                if (element.id == 'js-preference-btn') {
                    element.classList.remove('btn-success');
                } else if (element.id == 'js-unavailability-btn') {
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

// Weekday buttons check and color change
var preferenceWeekdayCheckbox;
var weekdayBtnIndex;
weekdayBtns = document.querySelectorAll('.js-preference-weekday-btn');
weekdayBtns.forEach(clicked => {
    clicked.addEventListener('click', function() {
        weekdayBtns.forEach(element => {
            preferenceWeekdayCheckbox = element.children[0];
            weekdayBtnIndex = parseInt(element.id.replace(/\D/g, ""));
            
            // For regular week buttons:
            if (element.id == clicked.id) {
            // clicked button
                if (element.classList.contains('btn-dark') && !preferenceWeekdayCheckbox.checked) {
                    // checkbox
                    preferenceWeekdayCheckbox.checked = true;
                    // style
                    element.classList.remove('btn-dark');
                    element.classList.add('btn-primary');
                } else if (element.classList.contains('btn-primary') && preferenceWeekdayCheckbox.checked) {
                    // checkbox
                    preferenceWeekdayCheckbox.checked = false;
                    // style
                    element.classList.remove('btn-primary');
                    element.classList.add('btn-dark'); 


                    // check if all buttons is checked and uncheck it if that's the case


                }
            } 

            // For 'all' button
            if (clicked.id == 'js-preference-weekday-btn-all' && !isNaN(weekdayBtnIndex)) {
            // If it's been clicked and current loop element is a regular button
                if (clicked.classList.contains('btn-dark')) {
                // 'all' button doesn't have a checkbox
                    if (element.classList.contains('btn-dark') && !preferenceWeekdayCheckbox.checked) {
                        // checkbox
                        preferenceWeekdayCheckbox.checked = true;
                        // style
                        element.classList.remove('btn-dark');
                        element.classList.add('btn-primary');
                    }

                } else if (element.classList.contains('btn-primary')) {
                    // checkbox
                    preferenceWeekdayCheckbox.checked = false;
                    // style
                    element.classList.remove('btn-primary');
                    element.classList.add('btn-dark'); 
                }
                    
            }

            // For 'specific' button
            if (clicked.id == 'js-preference-weekday-btn-specific' && clicked.id !== element.id) {
            // if it's been clicked and current loop element is another button
                // checkbox
                preferenceWeekdayCheckbox.checked = false;
                // style
                element.classList.remove('btn-primary');
                element.classList.add('btn-dark'); 

            } else if (clicked.id !== 'js-preference-weekday-btn-specific'){
            // if any other button was clicked
                if (element.id == 'js-preference-weekday-btn-specific' && element.classList.contains('btn-primary') && preferenceWeekdayCheckbox) {
                // if 'specific' button is the current loop element
                    // checkbox
                    preferenceWeekdayCheckbox.checked = false;
                    // style
                    element.classList.remove('btn-primary');
                    element.classList.add('btn-dark'); 
                }
            }
        });
    });

    // if clicked id == specific then display specific menu

});
//console.log(weekdayBtns);