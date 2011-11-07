
function lazyload_replace_stub(fname,js,ps){eval(js);(eval(fname)).apply(this,ps);}
var i,funcs=[],fname;for(i=0;i<llStubs.length;++i){fname=llStubs[i];funcs.push('window.'+fname+'=function(){var ps=arguments;x_kfm_getJsFunction("'+fname+'",function(js){lazyload_replace_stub("'+fname+'",js,ps);});};');}
eval(funcs.join("\n"));funcs=null;i=null;fname=null;