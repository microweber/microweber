<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<rss version="2.0"
    xmlns:dc="http://purl.org/dc/elements/1.1/"
    xmlns:sy="http://purl.org/rss/1.0/modules/syndication/"
    xmlns:admin="http://webns.net/mvcb/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:content="http://purl.org/rss/1.0/modules/content/"
	xmlns:atom="http://www.w3.org/2005/Atom">
	

    <channel>
    
    <title><?php print $this->core_model->optionsGetByKey ('rss_title'); ?></title>

    <link><?php print current_url(); ?></link>
	<atom:link href="<?php print current_url(); ?>" rel="self" type="application/rss+xml" />
    <description><?php print $this->core_model->optionsGetByKey ( 'content_meta_description' ); ?></description>
    <dc:language><?php print $this->core_model->optionsGetByKey ( 'rss_language'); ?></dc:language>
    <dc:creator><?php print $this->core_model->optionsGetByKey ('creator_email'); ?></dc:creator>
        <sy:updatePeriod>hourly</sy:updatePeriod>
        <sy:updateFrequency>1</sy:updateFrequency>
    <dc:rights>Copyright <?php echo gmdate("Y", time()); ?></dc:rights>
    <admin:generatorAgent rdf:resource="http://ooyes.net/" />

    <?php foreach($posts as $item): ?>
    
        <item>

          <title><?php print xml_convert($item['content_title']); ?></title>
          <link><![CDATA[<?php print $this->content_model->contentGetHrefForPostId($item['id']); ?>]]></link>
          <guid isPermaLink="false"><![CDATA[<?php print $this->content_model->contentGetHrefForPostId($item['id']); ?>]]></guid>
		 


          <description><![CDATA[
      <?php print (($item['the_content_body']));?>
      ]]></description>
      <pubDate><?php print date('r',strtotime($item['updated_on']));?></pubDate>
        </item>

        
    <?php endforeach; ?>
    
    </channel></rss> 