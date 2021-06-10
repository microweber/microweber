import {DomService} from "./dom";


let prevY = -1;
let prev = null;

export const DropPosition = (e, target, canInsert) => {
    if(!e || !target || target.nodeType !== 1) return false;
    const x = e.pageX;
    const y = e.pageY;
    //  if(x%2 !== 0) return false;
    const rect = DomService.offset(target);
    const res = {};
    const distance = 15;
    if( prevY  === y ) return  false;
    if(canInsert) {
        if (y >= (rect.top - distance) && y <= (rect.top + distance)) {
            res.position = 'top';
            res.action = 'before';
        } else if ( y >= (rect.top + distance) && y <= (rect.top  + (rect.height/2))) {
            res.position = 'top';
            res.action = 'prepend';
        } else if ( y >= (rect.top + (rect.height/2)) && y <= (rect.bottom - distance)) {
            res.position = 'bottom';
            res.action = 'append';
        }  else if ( y >= (rect.top + (rect.height/2)) && y >= (rect.bottom - distance)) {
            res.position = 'bottom';
            res.action = 'after';
        } else {
            return false;
        }
    } else {
        if ( y >= (rect.top - distance) && y <= (rect.top  + (rect.height/2))) {
            res.position = 'top';
            res.action = 'before';
        } else if ( y >= (rect.top + (rect.height/2)) && y <= (rect.bottom + distance)) {
            res.position = 'bottom';
            res.action = 'after';
        } else {
            return false;
        }
    }

    return res
};
