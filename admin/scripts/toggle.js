document.addEventListener("DOMContentLoaded", ()=>{
    var toggleElements = document.querySelectorAll('.toggle');
    toggleElements.forEach(toggle => {
        toggle.addEventListener('click', (e) => {
            e.preventDefault();
            toggle.classList.toggle('toggle-active');
        });
    });
});