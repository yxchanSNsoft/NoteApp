
$(document).ready(function() {

  var toggler = document.getElementsByClassName("caret");
  var i;
      
  for (i = 0; i < toggler.length; i++) {
    toggler[i].addEventListener("click", function() {
      try {
        this.parentElement.querySelector(".nested").classList.toggle("active");
        this.classList.toggle("caret-down");
      } catch {
        this.classList.toggle("caret-down");
      }
        
      
    });
  }

});


