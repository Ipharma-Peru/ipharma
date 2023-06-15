document.addEventListener('DOMContentLoaded', () => {
    removeSilderActive()
    isActiveSwitch()
    
})

const removeSilderActive = () => {
    let currentURL = window.location.pathname
    if (currentURL === '/caja/ventas') {
        const sidebarElement = document.getElementById('sidebar')
        sidebarElement.classList.remove('active')
    }
}
const isActiveSwitch=()=> {
    const customSwitch = document.querySelector('.custom-switch')
    const btnCustom = document.querySelector('.btn-custom')
    customSwitch.addEventListener('click', function (event) {
        let target = event.target
        if (target.classList.contains('toggle-btn')) {
            const toggleButtons = this.querySelectorAll('.toggle-btn')
            toggleButtons.forEach(function (button) {
                button.classList.toggle('active');
            });

            // Mover el elemento .btn-custom según el botón clickeado
            if (target.textContent === 'Boleta') {
                btnCustom.classList.toggle('right');
            } else {
                btnCustom.classList.toggle('right');
            }
        }
    });
}
