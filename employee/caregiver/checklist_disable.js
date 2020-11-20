let box = document.querySelectorAll('.checkbox')

// Greys and diables check box after check is entered
for(let i = 0; i < box.length; i++){
  if(box[i].value == 1){
    box[i].setAttribute("disabled", "");
    box[i].style.backgroundColor = "lightgrey";
    box[i].checked = true;
  }
}
