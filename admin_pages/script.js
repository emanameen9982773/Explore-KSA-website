   //script.js  |  Explore KSA – Admin Pages


   // 1. ADD INPUT  (addContent.php)
function addInput(btn) {
    const parent = btn.parentNode;
    const input  = parent.querySelector('input').cloneNode(true);
    input.value  = ''; // تفريغ القيمة في الحقل الجديد
    parent.insertBefore(input, btn);
}


   //2. LOGIN FORM  (AdminLogin.php only)
function initLoginForm() {
    const form = document.getElementById('loginForm');
    if (!form) return; // not on the login page, stop here

    const usernameIn  = document.getElementById('username');
    const passwordIn  = document.getElementById('password');
    const usernameErr = document.getElementById('usernameErr');
    const passwordErr = document.getElementById('passwordErr');
    const toggleBtn   = document.getElementById('togglePass');
    const submitBtn   = document.getElementById('submitBtn');

    /* Show / hide password */
    toggleBtn.addEventListener('click', function () {
        const isHidden        = passwordIn.type === 'password';
        passwordIn.type       = isHidden ? 'text' : 'password';
        toggleBtn.textContent = isHidden ? '🙈' : '👁️';
    });

    /* Clear errors while typing */
    usernameIn.addEventListener('input', function () { clearErr(usernameIn, usernameErr); });
    passwordIn.addEventListener('input', function () { clearErr(passwordIn, passwordErr); });

    /* Client-side validation before PHP runs */
    form.addEventListener('submit', function (e) {
        let valid = true;

        if (usernameIn.value.trim() === '') {
            showErr(usernameIn, usernameErr, 'يرجى إدخال اسم المستخدم.');
            valid = false;
        }

        if (passwordIn.value.trim() === '') {
            showErr(passwordIn, passwordErr, 'يرجى إدخال كلمة المرور.');
            valid = false;
        }

        if (!valid) { e.preventDefault(); return; }

        // Loading state while PHP checks the DB
        submitBtn.textContent = 'جارٍ التحقق...';
        submitBtn.disabled    = true;
    });
}


   //HELPERS
function showErr(input, span, msg) {
    input.classList.add('invalid');
    span.textContent = msg;
}

function clearErr(input, span) {
    input.classList.remove('invalid');
    span.textContent = '';
}


   //INIT – single window.onload, runs everything
window.onload = function () {

    // Auto-hide alerts (dashboard, add, update pages)
    const alerts = document.querySelectorAll('.alert'); // fix: querySelectorAll returns a list
    alerts.forEach(function (alertMsg) {
        setTimeout(function () {
            alertMsg.style.transition = 'opacity 1s ease';
            alertMsg.style.opacity    = '0';
            setTimeout(function () { alertMsg.remove(); }, 1000);
        }, 7000);
    });

    // Login form setup
    initLoginForm();

};