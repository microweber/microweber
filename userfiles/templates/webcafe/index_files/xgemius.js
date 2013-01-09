// (c) 2000-2009 by Gemius SA

function gemius_parameters() {
  var d=document;
  var href=new String(d.location.href);
  var ref;
  var f=0;
  var fv='-';
  if (d.referrer) { ref=new String(d.referrer); } else { ref=''; }
  if (typeof Error!='undefined') {
    var fo;
    eval('try { f=(d==top.document)?1:2; if (typeof top.document.referrer=="string") { ref=top.document.referrer } } catch(e) {f=3;}');
    eval('try { fv=navigator.plugins["Shockwave Flash"].description; } catch (e) {}');
    eval('if (typeof ActiveXObject!="undefined") { try { fo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7"); } catch(e) { try { fo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6"); fv="X"; fo.AllowScriptAccess="always"; } catch(e) { if (fv=="X") { fv="WIN 6,0,20,0"; }} try { fo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash"); } catch(e) {} } if ((fv=="-" || fv=="X") && fo) { fv=fo.GetVariable("$version"); }}');
  }
  var url='&fr='+f+'&fv='+escape(fv)+'&tz='+(new Date()).getTimezoneOffset()+'&href='+escape(href.substring(0,499))+'&ref='+escape(ref.substring(0,499));
  if (screen) {
    var s=screen;
    if (s.width) url+='&screen='+s.width+'x'+s.height;
    if (s.colorDepth) url+='&col='+s.colorDepth;
  }
  return url;
}

window.pp_gemius_params = gemius_parameters();

function gemius_add_onload_event(obj,fn) {
  if (obj.attachEvent) {
    obj.attachEvent('onload', fn);
  } else if(obj.addEventListener) {
    obj.addEventListener('load', fn, false);
  }

}

function gemius_append_script(xp_url) {
  if (typeof Error!='undefined') {
    eval('try { xp_javascript=document.createElement("script"); xp_javascript.src=xp_url; xp_javascript.type="text/javascript"; xp_javascript.defer=true; document.body.appendChild(xp_javascript); } catch(e) {}');
  }
}

function gemius_obj_loaded() {
  window.pp_gemius_loaded+=1;
  if (window.pp_gemius_loaded==2 && window.pp_gemius_image.width && window.pp_gemius_image.width>1) {
    gemius_append_script(window.pp_gemius_script);
  }
}

function pp_gemius_timer() {
  window.pp_gemius_timer_image = new Image();
  window.pp_gemius_timer_image.src = window.pp_gemius_host+(new Date()).getTime()+'/dot.gif?l=36'+window.pp_gemius_time_id+window.pp_gemius_params;
}

var pp_gemius_proto = (document.location && document.location.protocol && document.location.protocol=='https:')?'https://':'http://';
if (typeof pp_gemius_hitcollector == 'undefined') {
  if (typeof gemius_hitcollector != 'undefined') {
    pp_gemius_hitcollector = gemius_hitcollector;
  } else {
    pp_gemius_hitcollector = 'gabg.hit.gemius.pl';
  }
}
var pp_gemius_host = pp_gemius_proto+pp_gemius_hitcollector+'/_';
var pp_gemius_sv;

if (typeof pp_gemius_nhit == 'undefined') {
  if (typeof pp_gemius_identifier == 'undefined') {
    if (typeof gemius_identifier != 'undefined') {
      pp_gemius_identifier = gemius_identifier;
      gemius_identifier = 'USED_'+gemius_identifier;
    } else {
      pp_gemius_identifier = '';
    }
  }


  if (typeof window.pp_gemius_cnt != 'undefined') {
    if (typeof window.pp_gemius_images == 'undefined') {
      window.pp_gemius_images = new Array();
    }
    var gemius_l = window.pp_gemius_images.length;
    window.pp_gemius_images[gemius_l]=new Image();
    window.pp_gemius_images[gemius_l].src = pp_gemius_host+(new Date()).getTime()+'/redot.gif?l=33&id=ERR_'+pp_gemius_identifier.replace(/id=/,'id=ERR_')+window.pp_gemius_params;
  } else {
    if (typeof pp_gemius_time_identifier != 'undefined' && typeof window.pp_gemius_time_id == 'undefined') {
      window.pp_gemius_time_id = '&id='+pp_gemius_time_identifier;
      window.pp_gemius_host = pp_gemius_host;
      setInterval('pp_gemius_timer()',60*1000);
      pp_gemius_sv=35;
    } else {
      window.pp_gemius_time_id = '';
      pp_gemius_sv=31;
    }
    window.pp_gemius_image = new Image();
    if (typeof pp_gemius_mode == 'undefined') {
      window.pp_gemius_loaded = 0;
      window.pp_gemius_script = pp_gemius_host+(new Date()).getTime()+'/pp.js?id='+pp_gemius_identifier;
      gemius_add_onload_event(window,gemius_obj_loaded);
      gemius_add_onload_event(window.pp_gemius_image,gemius_obj_loaded);
      pp_gemius_sv-=1;
    }
    window.pp_gemius_image.src = pp_gemius_host+(new Date()).getTime()+'/rexdot.gif?l='+pp_gemius_sv.toString()+'&id='+pp_gemius_identifier+window.pp_gemius_time_id+window.pp_gemius_params;
    window.pp_gemius_cnt = 1;
  }
  pp_gemius_identifier = 'USED_'+pp_gemius_identifier;
}

function pp_gemius_hit_int(list,rfrom,rto) {
  var gu = pp_gemius_host+(new Date()).getTime()+'/redot.gif?l=32';
  if (typeof window.xgemius_prot_cnt == 'undefined') {
    window.xgemius_prot_cnt=0;
  }
  if (typeof xgemius_prot_cnt == 'undefined') {
    xgemius_prot_cnt=0;
  }
  if (window.xgemius_prot_cnt<=xgemius_prot_cnt) {
    go=1;
    window.xgemius_prot_cnt++;
  } else {
    go=0;
  }
  xgemius_prot_cnt++;
  for (var i=rfrom ; i<rto ; i++) {
    gu += '&id='+list[i];
  }
  gu += window.pp_gemius_params;
  if (typeof window.gemius_hit_images == 'undefined') {
    window.gemius_hit_images = new Array();
  }
  var gl = window.gemius_hit_images.length;
  window.gemius_hit_images[gl]=new Image();
  if (go) {
    window.gemius_hit_images[gl].src = gu;
  } else {
    window.gemius_hit_images[gl].src = gu.replace(/id=/,'id=DBL_');
  }
}

function pp_gemius_hit() {
  for (i=0 ; i<arguments.length ; i+=5) {
    if (i+5<arguments.length) {
      pp_gemius_hit_int(arguments,i,i+5);
    } else {
      pp_gemius_hit_int(arguments,i,arguments.length);
    }
  }
}

if (typeof gemius_hit != 'function') {
  gemius_hit=pp_gemius_hit;
}
