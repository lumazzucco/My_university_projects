
/* jqury + ajax per richiamare modulo logout.php dal menù */
$(document).ready( function(){
    $("#proponi").click( function() {
        location.replace("share/share.php"); 
    } );
    $("#divertiti").click( function() {
        location.replace("./lnp/quiz.php?q=0"); 
    } );
    $("#esplora").click( function() {
        location.replace("explore.php"); 
    } );
    $("#proponi, .fa-plus-circle").click( function() {
        location.replace("../share/share.php"); 
    } );
    $(".testimonial-col .fa").click( function() {
        $(".testimonial-col .fa").toggleClass("fa-heart-o");
        $(".testimonial-col .fa").toggleClass("fa-heart");
    });
});

/* variabili globali per controllo password e data nascita in sign in */
var okPassword=false;
var okBirth=false;

/* altre funzioni ausiliarie*/
function validaForm(){
    /* controllo data nascita + password */
    return okPassword && okBirth;

}
function checkPassword(){
    var p1= document.signinForm.password1.value;
    var p2= document.signinForm.password2.value;
    if (p1!= null && p2!= null){
        if (p1!=p2){
            document.getElementsByClassName("warning")[1].style.animation="fade 1s";
            document.getElementsByClassName("warning")[1].style.visibility="visible";
            okPassword=false;
            
        }
        else {
            document.getElementsByClassName("warning")[1].style.animation="";
            document.getElementsByClassName("warning")[1].style.visibility="hidden";
            okPassword=true;
        }
    }

}
function checkBirth(){
    var year=document.signinForm.birth.value;
    var arr=year.split("-");
    if (parseInt(arr[0])>2003){
        document.getElementsByClassName("warning")[0].style.animation="fade 1s";
        document.getElementsByClassName("warning")[0].style.visibility="visible";
        okBirth=false;
    }
    else {
        document.getElementsByClassName("warning")[0].style.animation="";
        document.getElementsByClassName("warning")[0].style.visibility="hidden";
        okBirth=true;
    }
    
}
/* script per aggiornare foto profilo in sign in */
function changeProfile(){
    var path= document.getElementById("file").value;
    if (path!=""){
    var arr= path.split("\\");
    var file=arr[2];
    var newPath= "../images/"+file;
    var newPath2="url(\"" + newPath + "\")";
    document.getElementById("profile").style.backgroundImage= newPath2;
    }   
}

