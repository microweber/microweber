

<script>
mw.kuler = {
    templateColorsList: function(key, callback){
    	mw.kuler.get(key, function(){
    	      var data = this;
        	  var items = '', item = 0, len = data.length;
              if(len === 0){
                var kuler_out = "<ul class='mw-kuler-list mw-kuler-list-empty' onmousedown='event.preventDefault()'><li><?php _e('No results found for'); ?> <strong>"+key+"</strong></li></ul>";
                if(typeof callback === 'function'){
                  callback.call(kuler_out);
                }
                return false;
              }
              for( ; item < len; item ++ ){
                  var colors = data[item];
                  var color_items = colors.toString().split(',');
                  var color_items_preview = '<table cellspacing="0" cellpadding="0" ><tr>';
                  var l = color_items.length, i = 0;
                  for ( ;i<l;i++){
                    color_items_preview += "<td style='background-color:" + color_items[i] + "'>&nbsp;</td>" ;
                  }
                  color_items_preview +='</tr></table>' ;
                  items += "<li class='mw-kuler-list-item' id='mw_kuler_" + item + "' onclick=\"mw.kuler.setTemplateCSS('"+colors+"')\">" + color_items_preview + "</li>";
              }
        	  var kuler_out = "<ul class='mw-kuler-list' onmousedown='event.preventDefault()'>" + items + "</ul>";
              if(typeof callback === 'function'){
                callback.call(kuler_out);
              }
    	});
    },
    templateSettingsSearch : function(val, callback){
         if(typeof val === 'undefined'){ var val = ''; }
         var test = val.replace(/\s+/g, '');
         if(test != ''){
        	 mw.kuler.templateColorsList(val, function(){
                mw.$("#mw_kuler").html(this).show();
                if(typeof callback === 'function'){
                  callback.call();
                }
        	 });
         }
         else{
            mw.$("#mw_kuler").hide().html('');
            if(typeof callback === 'function'){
              callback.call();
            }
         }
    },
    setTemplateCSS : function(colors){
        colors = colors.replace(/#/g,'');
        mw.$("#kuler_colors").val(colors).trigger("change");
        mw.$("#color-scheme-input").val('kuler').trigger("change");
        var csslink = window.parent.document.getElementById('colorscss');
        var url = mw.settings.template_url + 'css/colors/kuler.php?v='+mw.random()+'&colors='+colors;
        csslink.href = url;
    },
    get:function(key, callback){
        if(key.contains('#')){
          var key = key.replace(/#/g, '');
        }
        var url = mw.kuler.settings.baseurl;
    	if(typeof key !== 'undefined' && key !== ''){
    		var url = mw.kuler.settings.queryUrl + key;
    	}
        $.getJSON( url, function( data ) {
            if(typeof callback === 'function'){
               callback.call(data);
            }
        });
    },
    templateSettingsField:function(el){
        var el = mw.$(el);
        el.bind('keyup', function(e){
            var test = this.value.replace(/\s+/g, '');
            if(test != ''){
                $(this).addClass('loading');
                mw.on.stopWriting(this, function(){
                    mw.kuler.templateSettingsSearch(this.value, function(){
                        el.removeClass('loading');
                    });
                });
            }
        });
        el.bind('focus', function(e){
            $(this).attr('placeholder', 'e.g. blue or #A0407F');
            if(mw.$('#mw_kuler .mw-kuler-list-empty').length === 0 && mw.$('#mw_kuler ul').length > 0){
                mw.$('#mw_kuler').show()
            }
        });
        el.bind('blur', function(e){
            mw.$('#mw_kuler').hide();
            $(this).attr('placeholder', 'COLOR SEARCH');
        });
    }
};

mw.kuler.settings = {}
mw.kuler.settings.baseurl = '//api.microweber.com/service/kuler/';
mw.kuler.settings.queryUrl = mw.kuler.settings.baseurl + '?q=';


$(mwd).ready(function(){
   mw.kuler.templateSettingsField("#kuler_color_search");
});

</script>


<div class="kuler-search-master">
  <input type="search" onkeyup="" name="kuler_color_search" class="mw-ui-field" id="kuler_color_search" placeholder="COLOR SEARCH" />
  <div id="mw_kuler" style="display:none"></div>
</div>