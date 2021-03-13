// New session submit alert
var sessionSubmitBtn = document.getElementById('js-session-submit-btn');
var sessionForm = document.getElementById('js-session-form');
var sessionSubmitConfirmMessage = 'Vous en êtes sûr(e) ? Une fois créée, l\'année scolaire ne pourra pas être supprimmée.';
sessionSubmitBtn.addEventListener('click', function(e){
    e.preventDefault();
    if (confirm(sessionSubmitConfirmMessage)) {
        sessionForm.submit();
    }
});