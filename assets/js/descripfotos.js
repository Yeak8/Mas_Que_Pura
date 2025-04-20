document.addEventListener("DOMContentLoaded", function() {
    const gridItems = document.querySelectorAll(".grid-item");
    
    gridItems.forEach(item => {
        item.addEventListener("mouseover", () => {
            item.querySelector(".overlay").style.opacity = "1";
        });
        item.addEventListener("mouseout", () => {
            item.querySelector(".overlay").style.opacity = "0";
        });
    });
});