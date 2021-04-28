var timeoutInMiliseconds = 60000;
var timeoutId; 
  
function startTimer() { 
    // window.setTimeout returns an Id that can be used to start and stop a timer
    timeoutId = window.setTimeout(doInactive, timeoutInMiliseconds);
}
  
function doInactive() {
  window.location = "/logout";
}
 

function resetTimer() { 
    window.clearTimeout(timeoutId);
    startTimer();
}


function setupTimers () {
    document.addEventListener("mousemove", resetTimer, false);
    document.addEventListener("mousedown", resetTimer, false);
    document.addEventListener("keypress", resetTimer, false);
    document.addEventListener("touchmove", resetTimer, false);
     
    startTimer();
}
 
$(document).ready(function(){
    // do some other initialization
     
    setupTimers();
});