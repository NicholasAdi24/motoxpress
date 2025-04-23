
document.addEventListener("DOMContentLoaded", function () {


    let sidebar = document.getElementById("sidebar");
    let overlay = document.getElementById("overlay");
    let toggleButton = document.getElementById("sidebarToggle");

    function toggleSidebar() {
        sidebar.classList.toggle("show");
        overlay.classList.toggle("show");
    }

    if (toggleButton) {
        toggleButton.addEventListener("click", function (event) {
            event.stopPropagation();
            toggleSidebar();
        });
    }

    if (overlay) {
        overlay.addEventListener("click", function () {
            toggleSidebar();
        });
    }

    


});
