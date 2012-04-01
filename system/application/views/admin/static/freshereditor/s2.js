var dragObj;

//document.addEventListener("mousedown", down, false);

function down(event) {
	if(~event.target.className.search(/drag/)) {
		dragObj = makeObj(event);
		dragObj.element.style.zIndex="100";
		document.addEventListener("mousemove", freeMovement, false);
	}
}

function freeMovement(event) {

	if (typeof(dragObj.element.mouseup) == "undefined")
		document.addEventListener("mouseup", drop, false);
	//Prevents redundantly adding the same event handler repeatedly

	dragObj.element.style.left = Math.max(dragObj.maxBoundX, Math.min(dragObj.minBoundX, event.clientX - dragObj.posX)) + "px";
    dragObj.element.style.top = Math.max(dragObj.maxBoundY, Math.min(dragObj.minBoundY, event.clientY - dragObj.posY)) + "px";
}

function drop() {
	dragObj.element.style.zIndex="1";

	document.removeEventListener("mousemove", freeMovement, false);
	document.removeEventListener("mouseup", drop, false);
	//alert("DEBUG_DROP");
}


function makeObj(event) {
	var obj = new Object();
	var e = event.target;

	obj.element = e;
	obj.boundElement = null;

	while(e = e.parentNode) {
		if(e.className != undefined) {  
		if(e.className.search(/bound/)) { //if(/bound/.test(e.className)) {
			obj.boundElement = e;
			break;
		}
		}
	}

    if(obj.boundElement == null)
		obj.boundElement = document.body;

	obj.minBoundX = obj.boundElement.offsetLeft + obj.boundElement.offsetWidth - obj.element.offsetWidth;
	obj.minBoundY = obj.boundElement.offsetTop + obj.boundElement.offsetHeight - obj.element.offsetHeight;

	obj.maxBoundX = obj.boundElement.offsetLeft;
	obj.maxBoundY = obj.boundElement.offsetTop;

	setHelperBoxPos(obj);

	obj.posX = event.clientX - obj.element.offsetLeft;
	obj.posY = event.clientY - obj.element.offsetTop;

	return obj;
}

function findPos(obj) { // Donated by `lwburk` on StackOverflow
    var curleft = curtop = 0;
    if (obj.offsetParent) {
        do {
            curleft += obj.offsetLeft;
            curtop += obj.offsetTop;
        } while (obj = obj.offsetParent);
        return { x: curleft, y: curtop };
    }
}

function setHelperBoxPos(obj) {
    var minBox = document.getElementById('min');
    minBox.style.left = obj.minBoundX + 'px';
    minBox.style.top = obj.minBoundY + 'px';
    
    var maxBox = document.getElementById('max');
    maxBox.style.left = obj.maxBoundX + 'px';
    maxBox.style.top = obj.maxBoundY + 'px';
}