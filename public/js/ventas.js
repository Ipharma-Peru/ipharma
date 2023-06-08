document.addEventListener('DOMContentLoaded', () => {
    removeSilderActive()
    SliderMouseEvent()
})

const removeSilderActive = () => {
    var currentURL = window.location.pathname
    // Verifica si la URL coincide con la URL específica
    if (currentURL === '/caja/ventas') {
        // Selecciona el elemento "sidebar" y quita la clase "active"
        var sidebarElement = document.getElementById('sidebar')
        sidebarElement.classList.remove('active')
    }
}
const SliderMouseEvent = () => {
    const products = document.getElementById("ventasProducts");
    const detail = document.getElementById("ventasDetail");

    const toggleClasses = (element, class1, class2) => {
        element.classList.toggle(class1);
        element.classList.toggle(class2);
    };

    const applyTransition = (element) => {
        element.style.transition = "width 0.3s";
    };

    products.addEventListener("mouseenter", () => {
        applyTransition(products);
        applyTransition(detail);
        toggleClasses(products, "col-md-6", "col-md-8");
        toggleClasses(detail, "col-md-6", "col-md-4");
    });

    products.addEventListener("mouseleave", () => {
        applyTransition(products);
        applyTransition(detail);
        toggleClasses(products, "col-md-8", "col-md-6");
        toggleClasses(detail, "col-md-4", "col-md-6");
    });
}



