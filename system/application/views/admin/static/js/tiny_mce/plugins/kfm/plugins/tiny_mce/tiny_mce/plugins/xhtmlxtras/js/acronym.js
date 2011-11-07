
function init(){SXE.initElementDialog('acronym');if(SXE.currentAction=="update"){SXE.showRemoveButton();}}
function insertAcronym(){SXE.insertElement('acronym');tinyMCEPopup.close();}
function removeAcronym(){SXE.removeElement('acronym');tinyMCEPopup.close();}
tinyMCEPopup.onInit.add(init);