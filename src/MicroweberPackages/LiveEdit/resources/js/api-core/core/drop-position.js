import {DomService} from './classes/dom.js';


let prevY = -1;
let prev = null;

const _dropPositionExceptions = (target, res, e, conf, rect) => {

    if(target.classList.contains('mw-empty-element')) {
        if(res.action === 'after') {
            res.action = 'append'
        }
        if(res.action === 'before') {
            res.action = 'prepend'
        }
    }
    
    return res;
}

export const DropPosition = (e, conf) => {
    if(!e || !conf) {
        return false
    }
 
 
    const target = conf.target;
    if( !target || target.nodeType !== 1) return false;
    const x = e.pageX;
    const y = e.pageY;

     

    /*
    *  conf { canInsert: boolean,  beforeAfter: boolean }
    * */


    //  if(x%2 !== 0) return false;
    const rect = DomService.offset(target);
    const res = {};
    const distance = 15;
    if( prevY  === y || !conf || (!conf.canInsert && !conf.beforeAfter)) return false;
    if(conf.canInsert && conf.beforeAfter) {
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
    } else if(conf.beforeAfter) {
        if ( y >= (rect.top - distance) && y <= (rect.top  + (rect.height/2))) {
            res.position = 'top';
            res.action = 'before';
        } else if ( y >= (rect.top + (rect.height/2)) && y <= (rect.bottom + distance)) {
            res.position = 'bottom';
            res.action = 'after';
        } else {
            return false;
        }
    }  else if(conf.canInsert) {
        if ( y >= (rect.top - distance) && y <= (rect.top  + (rect.height/2))) {
            res.position = 'top';
            res.action = 'prepend';
        } else if ( y >= (rect.top + (rect.height/2)) && y <= (rect.bottom + distance)) {
            res.position = 'bottom';
            res.action = 'append';
        } else {
            return false;
        }
    }


    return _dropPositionExceptions(target, res, e, conf, rect);
};
