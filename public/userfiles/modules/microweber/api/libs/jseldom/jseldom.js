/**
 Copyright (c) 2010, All Right Reserved, Wong Shek Hei @ shekhei@gmail.com
 License: GNU Lesser General Public License (http://www.gnu.org/licenses/lgpl.html)
 **/
(function(jQuery) {
    expr = /[.#\w].([\S]*)/g,
        classexpr = /(\.)([^.#[]+)/g,
        idexpr = /(#)[^.#[]+/,
        tagexpr = /^[\w]+/,
        varexpr = /(\w+?)=(['"])([^\2$]*?)\2/,
        simpleselector = /^[\w]+$/,
        rTrimmer = /^[\s]*[\"\']?|[\"\']?[\s]*$/g,
        rSpaceTrim = /^[\s]+|[\s]+$/g;
    if ( !String.prototype.trim) {
        String.prototype.trim = function() {
            return this.replace(rSpaceTrim, '');
        }
    }
    function parseSelector( selector ) {
        //var buffer = [];
        var buffer = {sel:"",val:[]},
            arr = [],
            isVar = false,
            quote,
            varbuff = "",
            pc = '', c = '', i,
            selLen = selector.length;
        for ( i = 0; i < selLen; i++ ) {
            pc = c;
            c = selector.charAt(i);
            if ( isVar ) {
                if ( c === '\\' && i+1 < selLen ) {
                    varbuff += selector.charAt(++i);
                }
                else if ( quote === c ) {
                    quote = undefined;
                    varbuff += c;
                }
                else if ((c === '\'' || c === '\"') && quote === undefined ) {
                    quote = c;
                    varbuff += c;
                }
                else if ( c === ']' && quote === undefined ) {
                    buffer.val.push(varbuff);
                    varbuff = "";
                    isVar = false;
                }
                else if ( c !== ']' || quote !== undefined ) {
                    if ( quote === undefined && c === ',' ) {
                        buffer.val.push(varbuff);
                        varbuff = "";
                    }
                    else {
                        varbuff += c;
                    }
                }
            }
            else if ( c === '\\' && i+1 < selLen ) {
                if ( isVar ) {
                    varbuff += selector.charAt(++i);
                }
            }
            else if ( c === '[' && quote === undefined) {
                isVar = true;
            }
            else if ( c === ' ' || c === '+' || c === "<" ) {   // end of a tag, or as a sibling element
                // now lets peek forward
                if ( c === " " ) {
                    for ( ; c === " " && i < selLen; i++ ) {
                        c = selector.charAt(i);
                    }
                    if ( i < selLen ) {
                        --i;
                        c = selector.charAt(i);
                    }
                }
                arr.push(buffer);
                if ( c === '+' || c === '<') {
                    arr.push({sel:c, val:''});
                    while ( i < selLen && ( c = selector.charAt(++i) ) === " " ) {}
                    if ( i < selLen ) {
                        c = selector.charAt(--i);
                    }
                }
                buffer = {sel:"",val:[]};
            }
            else if ( c !== ' ' && c !== ']' ) {
                buffer.sel+= c;
            }
        }
        if ( buffer.sel.length != 0 || buffer.val.length != 0 ) {
            arr.push(buffer);
        }
        var len = arr.length
        for ( i = 0; i < len; i++ ) {
            var sel = arr[i].sel, temp ={};

            if ( sel === '+' || sel ==="<") {
                temp.tag = sel;
            }
            else {
                temp.tag = tagexpr.exec(sel);
                temp.id = idexpr.exec(sel);
                if ( temp.id && $.isArray(temp.id) ) {
                    temp.id = temp.id[0].substr(1);
                }
                if ( !temp.tag ) {
                    temp.tag = 'div';
                }
                temp.vars = [];
                var classes = [];
                for ( var j = 0; j < arr[i].val.length; j++ ) {
                    var index = arr[i].val[j].indexOf('=');
                    var key = arr[i].val[j].substr(0, index).trim();
                    var val = arr[i].val[j].substr(index+1);
                    val = val.replace(rTrimmer, '');
                    if ( key === "text" ) {
                        temp.text = val;
                    } else if ( key === "class" && val.trim().length ) {
                        classes.push(val);
                    }else {
                        temp.vars.push([key,val]);
                    }
                }
                var arr2 = sel.match(classexpr);
                if ( arr2 ) {
                    for ( var j = 0; j < arr2.length; j++ ) {
                        classes.push(arr2[j].substr(1));
                    }
                    temp.className = classes.join(" ");
                }
            }

            arr[i] = temp;
        }
        return arr;
    };
    function nonArrVer(selector, count) {
        var arr = [], newel = [];
        if ( simpleselector.test(selector) ) {
            arr = [ { tag: selector } ];    //if it is just a simple tag selector, then there is no need to parse it, makes it much more efficient
        }
        else {
            arr = parseSelector(selector);
        }

        if ( typeof count === "undefined" ) {
            count = 1;
        }
        var returns = $();
        var parent = [];
        var lastparent = [];
        var tempholder = document.createElement('div');
        //var buff = [];
        var depth = 0, i = 0, x = 0, len = arr.length;
        for ( i = 0; i < len; i++ ) {
            if ( arr[i].tag == '+' || arr[i].tag === '<' ) { parent = lastparent.slice(); --depth;}
            else {
                for ( x = 0; x < count; x++ ) {

                    if ( arr[i].tag == 'input' ) { // special case, needs to create tag by html
                        var html = [];
                        html.push( "<"+arr[i].tag);
                        if ( arr[i].id) {
                            html.push( "id=\'"+arr[i].id+"\'");
                        }
                        if ( arr[i].className || (arr[i].vars && "class" in arr[i].vars)) {
                            var val = [];
                            if ( arr[i].className ) { val.push(arr[i].className); }
                            if ( arr[i].vals["class"] ) { val.push(arr[i].vars["class"]); }
                            html.push( "class=\'"+val.join(" ") );
                            html.push( "\'");
                        }
                        if ( arr[i].vars ) {
                            for ( var j = 0; j < arr[i].vars.length; j++ ) {
                                html.push(arr[i].vars[j][0]+"=\'"+arr[i].vars[j][1]+"\'");
                            }
                        }
                        if ( arr[i].text ) {
                            html.push("value=\'"+arr[i].text+"\'");
                        }
                        html.push("/>");
                        lastparent[x] = parent[x];
                        if ( !parent[x] ) {
                            tempholder.innerHTML = html.join(" ");
                            parent[x] = tempholder.removeChild(tempholder.firstChild);
                        } else {
                            parent[x].innerHTML = parent[x].innerHTML+html.join(" ");
                            parent[x]=parent[x].lastChild;
                        }
                    }
                    else {
                        var buff = document.createElement(arr[i].tag);
                        if ( arr[i].vars ) {
                            for ( var j = 0; j < arr[i].vars.length; j++ ) {
                                buff.setAttribute(arr[i].vars[j][0], arr[i].vars[j][1]);
                            }
                        }
                        if ( arr[i].id ) {
                            buff.id = arr[i].id;
                        }
                        if ( arr[i].className ) {
                            buff.className = arr[i].className;
                        }
                        if ( arr[i].text ) {
                            buff.appendChild(document.createTextNode(arr[i].text));
                        }
                        lastparent[x] = parent[x];
                        if ( parent[x] ) {
                            parent[x] = parent[x].appendChild(buff);
                        } else {
                            parent[x] = buff;
                        }
                    }
                }
                if ( !depth++ ) {
                    Array.prototype.push.apply(returns, parent);
                }
            }
            Array.prototype.push.apply( newel, parent );
        }
        return returns;
    };
    function arrVer(sel, arr, count ) {
        var regex = /{[^}]*}/g;
        var vars = sel.match(regex) || [];
        var newel = $();
        for ( var i = 0; i < arr.length; i++ ) {
            var tsel = sel;
            for ( var j = 0; j < vars.length; j++ ) {
                var vname = vars[j].substr(1,vars[j].length-2).split('.'),
                    val = arr[i][vname[0]], oldval;
                if ( vname[0].trim() === "" ) {
                    val = arr[i].toString();
                } else {
                    for ( var x = 1; x < vname.length; x++ ) {
                        oldval = val;
                        val = val[vname[x]];
                        if (!val) {
                            val = "";
                            break;
                        }
                        if ( typeof val === "function" ) {
                            val = val.call(oldval)
                        }
                    }
                }
                if ( $.isArray(val) ) { val = val.join(","); }
                if ( typeof val === "string" ) {
                    val = val.replace(/\\['"\\]|['"]/g, function (s) {
                        return "\\"+s;
                    });
                } else if ( typeof val === "function" ) {
                    val = val.call(oldval)
                }
                val = val || "";
                tsel = tsel.replace(vars[j], val);
            }
            Array.prototype.push.apply(newel, nonArrVer(tsel, count));
            // newel = newel.pushStack(nonArrVer(tsel, count));
        }

        return newel;
    };
    var isArray = Array.isArray || function(arr){return toString.call(arr) === "[object Array]";}
    $.jseldom = function(selector) {
        if ( arguments.length == 2 && $.isPlainObject(arguments[1])) {
            return arrVer.apply(this, [arguments[0], [arguments[1]]]);
        }
        else if ( arguments.length == 1 || (arguments.length == 2 && !isArray(arguments[1]))) {
            return nonArrVer.apply( this, arguments );
        }
        else if ( arguments.length == 2 ) {
            return arrVer.apply( this, arguments);
        }
    }
    $.jseldomf = function(selector) {
        var args = [selector, [Array.prototype.slice.call(arguments,1)]];
        return arrVer.apply(this, args);
    }
})(jQuery);
