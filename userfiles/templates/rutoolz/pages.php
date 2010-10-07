
  <!--SUBNAV-->
  <div id="RU-subnav"> 
	<div class="pad3"></div>
 <div id="RU-nav">
	
    <ul class="nav">
    	<li><a href="#" title="#">Tab nav</a></li>
        <li><a href="#" title="#">Tab nav</a></li>
        <li><a href="#" title="#">Tab nav</a></li>
        <li><a href="#" title="#">Tab nav</a></li>
        <li><a href="#" title="#">Tab nav</a></li>
    </ul>
 
 </div>
  
  
   <!--HELP-->
    <div id="RU-help"> <a href="#" title="Help" class="help"></a> 
    
    
    
    
      <!--END HELP--> 
    </div>   
    <div class="clr"></div>
    <!--END SUBNAV--> 
  </div>
 
 
<div id="RU-content">
	<div class="pad2"></div>

	<div class="preloader">
    	<div class="preload"><img src="<? print TEMPLATE_URL; ?>images/loader.gif" alt="Preloader" /></div>
    </div>
    



<div class="box-holder">
    <div class="box-top">&nbsp;</div>
    <div class="box-inside">
        <a href="#" class="addnewcampaign">Add New Campaign</a>
        <h2 class="box-title">Campaign Manager</h2>
        <div id="campaign_searchbar">
            <div class="campaign_sort">
                <label>By date:</label>
                <select style="width: 100px;">
                    <option>Newest</option>
                    <option>Oldest</option>
                </select>
            </div>
            <form method="post" action="#" id="campaign_searchform">
                <input type="text" value="Search" class="type-text" onfocus="this.value=='Search'?this.value='':''" onblur="this.value==''?this.value='Search':''" />
                <input type="submit" value="" class="type-submit" />
            </form>
        </div>

        <div class="campaign">
            <div class="campaign-content">
                <div class="campaign-header">
                    <span class="campaign-name">Campain Name Here</span>
                    <span class="campaign-stat">Statistic</span>
                    <span class="campaign-date">Date</span>
                    <span class="campaign-add-page"><a class="add-page" href="#">Add pages</a></span>
                    <span class="campaign-edit"><strong>Edit</strong></span>
                    <span class="campaign-delete"><strong>Delete</strong></span>
                </div>
                <table cellpadding="0" cellspacing="0" class="" class="campaign-table">
                    <colgroup>
                        <col width="240" />
                        <col width="120" />
                        <col width="100" />
                        <col width="75" />
                        <col width="75" />
                        <col width="75" />
                        <col width="75" />
                    </colgroup>
                    <tr>
                        <td><strong class="campaign-page-title">Page title here</strong></td>
                        <td>Capture</td>
                        <td>12.12.2023</td>
                        <td><span class="statusY">&nbsp;</span></td>
                        <td><span class="magnifier">&nbsp;</span></td>
                        <td><span class="campaign-edit">&nbsp;</span></td>
                        <td><span class="remove">Remove</span></td>
                    </tr>
                </table>

            </div>
        </div>
           <a href="#" class="nextprev right" style="margin-left: 10px;">Next</a>
           <a href="#" class="nextprev right">Prev</a>
          <div class="c" style="padding-bottom: 12px">&nbsp;</div>
    </div>
    <div class="box-bottom">&nbsp;</div>
</div>

<div class="pad2"></div>
 <!--END CONTENT-->
</div>