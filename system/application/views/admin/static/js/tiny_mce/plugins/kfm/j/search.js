
function kfm_runSearch(){kfm_run_delayed('search',kfm_runSearch2);}
function kfm_searchBoxFile(){var sbox=document.createElement('input');sbox.id='kfm_search_keywords';$j.event.add(sbox,'keyup',kfm_runSearch);return sbox;}
llStubs.push('kfm_runSearch2');