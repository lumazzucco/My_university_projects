
//menu bar on the side 
 var navLinks = document.getElementById("navLinks");

 function showMenu(){
     navLinks.style.right = "0";
 }
 function hideMenu(){
     navLinks.style.right = "-200px";
 }

//light mode changing theme
const changeMode = document.querySelector(".light-btn");
var element = document.body;

changeMode.addEventListener("click", () => {
    element.classList.toggle("light-mode");
});




