function isInArray(value, array) {
	return array.indexOf(value) > -1;
}

function capitalize(string) {
	return string.charAt(0).toUpperCase() + string.slice(1).toLowerCase();
}

function removeElementFromArray(array, element) {
	const index = array.indexOf(element);

	if (index !== -1) {
		array.splice(index, 1);
	}
}