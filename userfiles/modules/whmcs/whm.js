
mw.whm = {
   domain_check: function(domain, tld, callback){
    var obj = {
      domain:domain,
      tld:tld
    }
    if(tld==false) delete obj.tld;
    $.post(mw.settings.api_url + "whm_domainwhois", obj, function(data){
        typeof callback === 'function' ? callback.call(undefined, data) : '';
    });
   }
}