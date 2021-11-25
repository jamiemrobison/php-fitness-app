function setLinkStatus(button) {
    let buttonID = button.id;

    let myButton = document.getElementById(buttonID);
    if(myButton.getAttribute("aria-expanded") === "true") {
        myButton.classList.remove("active");
    } else {
        myButton.classList.add("active");
    }
    
}
