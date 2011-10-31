
function init(){SXE.initElementDialog('cite');if(SXE.currentAction=="update"){SXE.showRemoveButton();}}
function insertCite(){SXE.insertElement('cite');tinyMCEPopup.close();}
function removeCite(){SXE.removeElement('cite');tinyMCEPopup.close();}
tinyMCEPopup.onInit.add(init);