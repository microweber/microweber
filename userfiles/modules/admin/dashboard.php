
<?
$piwik_url = site_url ( 'system/application/stats/' ) . '/';

$piwik_site_id =	CI::model ( 'stats' )->site_id();

?>


<a href="<? print $piwik_url ?>">See all stats</a>


<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <th scope="col">Overview</th>
    <th scope="col">Live</th>
  </tr>
  <tr>
    <td><iframe width="100%" height="600" src="<? print $piwik_url ?>index.php?module=Widgetize&action=iframe&moduleToWidgetize=VisitsSummary&actionToWidgetize=index&idSite=<? print $piwik_site_id ?>&period=day&date=today&disableLink=1" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe></td>
    <td> 
    <iframe width="100%" height="600" src="<? print $piwik_url ?>index.php?module=Widgetize&action=iframe&moduleToWidgetize=Live&actionToWidgetize=widget&idSite=<? print $piwik_site_id ?>&period=day&date=today&disableLink=1" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe>
</td>
  </tr>
</table>
<br />
<br />
<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <th scope="col">User map</th>
    <th scope="col">Refferers</th>
  </tr>
  <tr>
    <td><iframe width="100%" height="450" src="<? print $piwik_url ?>index.php?module=Widgetize&action=iframe&moduleToWidgetize=UserCountryMap&actionToWidgetize=worldMap&idSite=<? print $piwik_site_id ?>&period=day&date=today&disableLink=1" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe></td>
    <td><iframe width="100%" height="450" src="<? print $piwik_url ?>index.php?module=Widgetize&action=iframe&moduleToWidgetize=Referers&actionToWidgetize=getRefererType&idSite=<? print $piwik_site_id ?>&period=day&date=today&disableLink=1" scrolling="no" frameborder="0" marginheight="0" marginwidth="0"></iframe>
</td>
  </tr>
</table>
<br />
<br />
<br />
<br />





