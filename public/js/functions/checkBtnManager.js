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