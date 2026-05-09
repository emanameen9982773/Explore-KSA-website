function addInput(btn) {
            const parent = btn.parentNode;
            const input = parent.querySelector('input').cloneNode(true);
            input.value = ""; // تفريغ القيمة في الحقل الجديد
            parent.insertBefore(input, btn);
        }

window.onload = function() {
    const alertMsg = document.getElementsByClassName('alert');
    
    if (alertMsg) {
        setTimeout(function() {
            alertMsg.style.transition = "opacity 1s ease";
            alertMsg.style.opacity = "0";
            
            setTimeout(() => alertMsg.remove(), 1000);
        }, 7000);
    }
};