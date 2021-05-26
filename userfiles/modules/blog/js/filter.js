function numericMonth(dt)
{
    return (dt.getMonth() < 9 ? '0' : '') + (dt.getMonth() + 1);
}

function findOrReplaceInObject(object, key, value) {
    var findKey = false;
    for (var i=0; i< object.length; i++) {
        if (object[i].key == key) {
            object[i].value = value;
            findKey = true;
            break;
        }
    }
    if (findKey === false) {
        object.push({key: key, value: value});
    }
    return object;
}
