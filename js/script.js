// Auto-hide success/info alert after 4 seconds
document.addEventListener("DOMContentLoaded", function () {
    const alertBox = document.querySelector(".alert:not(.alert-error)");
    if (alertBox) {
        setTimeout(() => {
            alertBox.style.transition = "opacity 0.4s ease";
            alertBox.style.opacity = "0";
            setTimeout(() => alertBox.remove(), 400);
        }, 4000);
    }
});
