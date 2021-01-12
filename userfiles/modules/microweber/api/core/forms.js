mw.serializeFields =  function(id, ignorenopost){
    ignorenopost = ignorenopost || false;
    var el = mw.$(id);
    var fields = "input[type='text'], input[type='email'], input[type='number'], input[type='tel'], "
        + "input[type='color'], input[type='url'], input[type='week'], input[type='search'], input[type='range'], "
        + "input[type='datetime-local'], input[type='month'], "
        + "input[type='password'], input[type='hidden'], input[type='datetime'], input[type='date'], input[type='time'], "
        +"input[type='email'],  textarea, select, input[type='checkbox']:checked, input[type='radio']:checked, "
        +"input[type='checkbox'][data-value-checked][data-value-unchecked]";
    var data = {};
    $(fields, el).each(function(){
        if(!this.name){
            console.warn('Name attribute missing on ' + this.outerHTML);
        }
        if((!$(this).hasClass('no-post') || ignorenopost) && !this.disabled && this.name && typeof this.name != 'undefined'){
            var el = this, _el = $(el);
            var val = _el.val();
            var name = el.name;
            if(el.name.contains("[]")){
                data[name] = data[name] || []
                data[name].push(val);
            }
            else if(el.type === 'checkbox' && el.getAttribute('data-value-checked') ){
                data[name] = el.checked ? el.getAttribute('data-value-checked') : el.getAttribute('data-value-unchecked');
            }
            else{
                data[name] = val;
            }
        }
    });
    return data;
}
