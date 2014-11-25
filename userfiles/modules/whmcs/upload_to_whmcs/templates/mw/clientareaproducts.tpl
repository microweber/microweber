{include file="$template/pageheader.tpl" title=$LANG.clientareaproducts desc=$LANG.clientareaproductsintro} 
<script>mw.require("wysiwyg.js");</script> 
<script>mw.require("url.js");</script> 
{assign var=services1 value=$services|mw_hosting_sort}


{php}
 

  $tpl_theme =  $this->get_template_vars('services') ;
  $myVar = mw_hosting_sort($tpl_theme);

  $this->assign('services',$myVar);

{/php}



{assign var=l value=$services|@count}



{if $l gt 6}
	<div class="searchbox">
	<form method="post" action="clientarea.php?action=products">
			<div class="input-append">
			<input type="text" name="q" value="{if $q}{$q}{else}{$LANG.searchenterdomain}{/if}" class="input-medium appendedInputButton mw-400" onfocus="if(this.value=='{$LANG.searchenterdomain}')this.value=''" />
			<button type="submit" class="btn btn-info">{$LANG.searchfilter}</button>
		</div>
		</form>
</div>
	<hr>
{/if}


{*
<div class="resultsbox">
	<p>{$numitems} {$LANG.recordsfound}, {$LANG.page} {$pagenumber} {$LANG.pageof} {$totalpages}</p>
</div>
<div class="vspace"></div>
<h2>Support</h2>
<hr>
<table class="table">
	<thead>
		<tr>
			<th>Support Type</th>
			<th>Status</th>
			<th>Service Tracking</th>
		</tr>
	</thead>
	<tbody>
	
	{assign var=count value=0}
	
	{foreach from=$services item=service}
	{assign var=count value=$count+1}
	{if $service.group eq 'Support'}
	
	{assign var=hosting_data value=$service.id|mw_hosting_data_by_order_id}
	<tr>
		<td>{$service.product}</td>
		<td><span class="label {$service.rawstatus}">{$service.statustext}</span></td>
		<td> {foreach  from=$hosting_data.customfields.customfield item=field}
			
			{if $field.name eq 'time_left'}
			
			{if $field.value eq NULL}
			1 Hour
			{else}
			{$field.value}
			{/if}
			
			{/if}
			
			{/foreach} </td>
		<td> {if $service.rawstatus eq 'pending'}
			
			{assign var=invoice_data value=$service.id|mw_get_product_invoices}
			
			
			{if $invoice_data.0.invoiceid neq ''} <a href="viewinvoice.php?id={$invoice_data.0.invoiceid}" class="btn">View Invoice</a> {/if}
			{/if} </td>
	</tr>
	{if $count eq 1}
	
	
	
	{/if}
	{/if}
	{/foreach}
		</tbody>
	
</table>
<hr>
*}
<h2>My Sites</h2>
<hr>
{foreach from=$services item=service}

{if $service.group eq 'Hosting' AND $service.rawstatus neq 'terminated' AND $service.rawstatus neq 'canceled'}

{assign var=invoice_data value=$service.id|mw_get_product_invoices}



{if $service.rawstatus eq 'active'}


{assign var=hosting_data value=$service.id|mw_hosting_data_by_order_id}
 
<div class="row-fluid products-row">
	<div class="span6">
		<div data-bg="//screen.microweber.com/shot.php?url={$service.domain}" class="products-screen-item products-screen-item-not-loaded" onclick="mw.$('form', this.parentNode).submit();"> <span class="edit-product-btn animate-all"> <i class="icon-pencil animate-all"></i> <span class="animate-all">Edit</span> </span> </div>
		<div class="product-basic-info">
			<div class="vpad"> <a class="blue" href="http://{$service.domain}/"> {$service.domain} </a> <span class="label label-success" title="Your site is {$service.statustext} ."> {$service.statustext} </span> <span class="badge" title="You are using {$service.product} {$service.group}.">{$service.product}</span> </div>
			<form class="pull-left" action="http://{$service.domain}/api/user_login" target="_blank" method="post">
 				<input type="hidden" name="username" value="{$hosting_data.username}" />
 			

 
<input type="hidden" name="password" value="{$hosting_data.password}" />
				<input type="submit" name="Edit website" value="Edit website" class="xbtn xbtn-blue" />
				<input type="hidden" name="redirect" value="http://{$service.domain}/admin" />
			</form>
		</div>
	</div>
	<div class="span6">
		<h6>Website Address</h6>
		<a class="blue" href="http://{$service.domain}/"> {$service.domain} </a> <br />
		<small class="muted">#
		{$service.id} </small>
		<h6>Website Control Panel</h6>
		<form class="pull-left" action="http://{$service.domain}/api/user_login" target="_blank" method="post">
			<input type="hidden" name="username" value="{$hosting_data.username}" />
			<input type="hidden" name="password" value="{$hosting_data.password}" />
			<input type="hidden" name="redirect" value="http://{$service.domain}/admin" />
		</form>
		<a class="blue" href="javascript:;" onclick="$(this).prev().submit();">{$service.domain}/admin</a>
		
		
		
		
		
		
		 
		 
		
			<form class="pull-left" action="http://{$service.domain}/api/user_login" target="_blank" method="post">
			<input type="hidden" name="username" value="{$loggedinuser.email}" />
			<input type="hidden" name="password" value="{$loggedinuser.id|md5}{$loggedinuser.email|sha1}" />
			<input type="hidden" name="redirect" value="http://{$service.domain}/admin" />
		</form>
				<a class="blue" href="javascript:;" onclick="$(this).prev().submit();">.</a>

		<h6>Disk Space Usage</h6>
		{math equation="(a / b) * 100" a=$hosting_data.diskusage b=$hosting_data.disklimit assign="percent"}
		<div class="blue disk-usage-info"> Disk Usage: {$hosting_data.diskusage} <abbr title="{$hosting_data.diskusage} megabytes">MB</abbr> / {$hosting_data.disklimit} <abbr title="{$hosting_data.disklimit} megabytes">MB</abbr> ({$percent}%)<br>
		</div>
		<div class="mwinput"> {if $percent lt 80}
			<div style="width: {$percent}%;" class="mwbar">{$percent}%</div>
			{elseif $percent gt 80}
			{if $percent lt 98}
			<div style="width: {$percent}%;" class="mwbar mwbar-orange">{$percent}%</div>
			{else}
			<div style="width: {$percent}%;" class="mwbar mwbar-red">{$percent}%</div>
			{/if}
			{/if} </div>
		<h6>Username:</h6>
		<span onclick="mw.wysiwyg.select_all(this);" class="blue x-item">{$hosting_data.username}</span>
		<h6>Password:</h6>
		<div class="mwinput hosting-pass-input"> <span class="mwinput-item"> <span class="ftp-pass fp1">*******</span> <span class="ftp-real-pass fp1" style="display: none" onclick="mw.wysiwyg.select_all(this);">{$hosting_data.password}</span> <small class="showhidepass" onclick="showhidepass(this.parentNode, event);">Show Password</small> </span> </div>
	</div>
</div>
{else}
<div class="row-fluid products-row">
	<div class="span6">
		<div class="products-screen-item products-screen-item-not-active">
			<div style="top:245px" class="text-center relative">
				<h6>This website is <span class="label {$service.rawstatus}">{$service.statustext}</span></h6>
				<!--<small class="blue">Delete this website</small> --> 
			</div>
		</div>
	</div>
	<div class="span6 the_padlock"> {if $service.rawstatus eq 'pending'}
		<h6>Website Name</h6>
		<a class="blue" href="http://{$service.domain}/"> {$service.domain} </a> <br />
		<small class="muted">#
		{$service.id} </small>
		<hr>
		{/if}
		
		
		
		{if $invoice_data.0.invoiceid neq ''} <a
    href="viewinvoice.php?id={$invoice_data.0.invoiceid}"
    class="xbtn xbtn-red">View/Pay Invoice</a> {/if}
		<div class="yellow-box2 text-center">
			<div class="pad">
				<p>Your website status is: <span title="Your site is {$service.statustext}." class="label {$service.rawstatus}">{$service.statustext}</span> </p>
				<a class="xbtn xbtn-orange" href="submitticket.php?step=2&deptid=1&relatedservice=S{$service.id}&subject=Please activate my website {$service.domain}">Contact support</a> </div>
		</div>
	</div>
</div>
{/if}
<div class="product-item-dlm"></div>
{/if}

{foreachelse}
<div class="alert alert-warning"> {$LANG.norecordsfound} &nbsp;&nbsp;&nbsp;<a href="cart.php" class="btn btn-mini btn-warning "><i class="icon-plus"></i> Create Website</a></div>
{/foreach}



{*


{include file="$template/clientarearecordslimit.tpl" clientareaaction=$clientareaaction}
<div class="pagination">
	<ul>
		<li class="prev{if !$prevpage} disabled{/if}"><a href="{if $prevpage}clientarea.php?action=products{if $q}&q={$q}{/if}&amp;page={$prevpage}{else}javascript:return false;{/if}">&larr; {$LANG.previouspage}</a></li>
		<li class="next{if !$nextpage} disabled{/if}"><a href="{if $nextpage}clientarea.php?action=products{if $q}&q={$q}{/if}&amp;page={$nextpage}{else}javascript:return false;{/if}">{$LANG.nextpage} &rarr;</a></li>
	</ul>
</div>
*} 
<script>


{literal}

showhidepass = function(el,e){
        mw.$(".fp1", el).each(function(){
            var n = this;
            if(n.style.display == 'none'){
               n.style.display = 'inline-block';
               e.target.innerHTML = 'Hide Password';
            }
            else{
               n.style.display = 'none';
               e.target.innerHTML = 'Show Password';
            }
        });
    }


$(window).bind("scroll load", function(){
        mw.$(".products-screen-item-not-loaded").each(function(){
          if(mw.tools.inview(this)){
            var el = this, $el = $(el);
            $el.removeClass("products-screen-item-not-loaded");
            $el.addClass("loading");
            var bg = $el.dataset("bg");
            mw.tools.preload(bg, function(){
                el.style.backgroundImage = "url(" + bg + ")";
                $el.removeClass("loading");
            });
          }
        });
    });



{/literal}


</script> 
