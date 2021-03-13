function displayFlexClassManager(displayFlexClass = null, element, action) {
    // action == true ? show : hide
    if (action) {
        if (displayFlexClass && !element.classList.contains('d-flex')) {
            element.classList.add('d-flex');
            displayFlexClassManager = true;
        }
    } else {
        if (element.classList.contains('d-flex')) {
            displayFlexClass = true;
            element.classList.remove('d-flex');
        }
    }
}

function searchbar(input, results){
    var displayFlexClass;
    input.addEventListener('input', function(){
        results.forEach(element => {
            if (element.textContent.toLowerCase().includes(input.value.toLowerCase())) {
                if (element.hidden){
                    element.hidden = false;
                }
                // optional (if using bootstrap's d-flex for the result)
                displayFlexClassManager(displayFlexClass, element, true);
                
            } else {
                if (!element.hidden){
                    element.hidden = true;
                }
                // optional (if using bootstrap's d-flex for the result)
                displayFlexClassManager(displayFlexClass, element, false);
                
            }
        });
    });
}

