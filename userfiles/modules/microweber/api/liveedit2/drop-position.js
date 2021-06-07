

let prevY = -1;

export const DropPosition = (e, target) => {
    if(!e || !target || target.nodeType !== 1) return false;
    const x = e.pageX;
    const y = e.pageY;
    // if(x%2 !== 0) return false;
    const rect = target.getBoundingClientRect();
    const res = {};
    const distance = 10;

    if(prevY  === y ) return  false

    console.log(y, rect.top)

    if (y >= (rect.top - distance) && y <= (rect.top + distance)) {
        res.position = 'top';
        res.action = 'before';
    } else if ( y >= rect.top + distance && y <= rect.top + (rect.height/2)) {
        res.position = 'top';
        res.action = 'prepend';
    } else if ( y >= rect.top + (rect.height/2) && y <= rect.bottom - distance) {
        res.position = 'bottom';
        res.action = 'append';
    }  else if ( y >= rect.top + (rect.height/2) && y >= rect.bottom - distance) {
        res.position = 'bottom';
        res.action = 'after';
    }
    return res
};
