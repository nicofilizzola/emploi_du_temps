var subjectSelect = document.getElementById('js-subject-select');
var classes = document.querySelectorAll('.js-classes');

subjectSelect.addEventListener('change', function() {
    classes.forEach(element => {
        console.log(element);
        if (element.id == 'js-classes-' + subjectSelect.value) {
            console.log(element);
            element.hidden = false;
        } else {
            element.hidden = true;
        }
    });
});