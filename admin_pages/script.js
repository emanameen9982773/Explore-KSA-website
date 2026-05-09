// script.js  |  Explore KSA – Admin Pages

// ==========================================
// 1. NIGHT MODE (Persists across all pages)
// ==========================================
function initNightMode() {
    const body = document.body;
    const nightModeBtn = document.querySelector('.night-mode-btn'); // Make sure your button has this class

    // 1. Check local storage when ANY page loads
    if (localStorage.getItem('explore_ksa_theme') === 'dark') {
        body.classList.add('night-mode');
    }

    // 2. Listen for button clicks (if the button exists on the current page)
    if (nightModeBtn) {
        nightModeBtn.addEventListener('click', function () {
            // Toggle the class on the body
            body.classList.toggle('night-mode');
            
            // Save the new state to localStorage
            if (body.classList.contains('night-mode')) {
                localStorage.setItem('explore_ksa_theme', 'dark');
            } else {
                localStorage.setItem('explore_ksa_theme', 'light');
            }
        });
    }
}

// ==========================================
// 2. ADD INPUT (addContent.php)
// ==========================================
function addInput(btn) {
    const parent = btn.parentNode;
    const input  = parent.querySelector('input').cloneNode(true);
    input.value  = ''; // تفريغ القيمة في الحقل الجديد
    parent.insertBefore(input, btn);
}

// ==========================================
// 3. LOGIN FORM (AdminLogin.php only)
// ==========================================
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
    if(toggleBtn) {
        toggleBtn.addEventListener('click', function () {
            const isHidden        = passwordIn.type === 'password';
            passwordIn.type       = isHidden ? 'text' : 'password';
            toggleBtn.textContent = isHidden ? '🙈' : '👁️';
        });
    }

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

// HELPERS FOR LOGIN
function showErr(input, span, msg) {
    input.classList.add('invalid');
    span.textContent = msg;
}

function clearErr(input, span) {
    input.classList.remove('invalid');
    span.textContent = '';
}

// ==========================================
// 4. ALERTS (Auto-hide success/error messages)
// ==========================================
function initAlerts() {
    const alertMsg = document.getElementById('alert-msg');
    
    if (alertMsg) {
        setTimeout(function() {
            // تأثير الاختفاء
            alertMsg.style.transition = "opacity 1s ease";
            alertMsg.style.opacity = "0";
            
            setTimeout(() => {
                alertMsg.remove();
            }, 1000);
        }, 5000); 
    }
}

// ==========================================
// INIT – Runs everything safely when the DOM loads
// ==========================================
document.addEventListener('DOMContentLoaded', function() {
    initNightMode();
    initLoginForm();
    initAlerts();
});