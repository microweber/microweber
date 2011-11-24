<?php 
echo '<?xml version="1.0" encoding="utf-8"?>' . "\n";
?>
<?php if($the_index == true): ?>
<sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php $i= 1;  foreach($updates as $item): ?>
 <sitemap>
      <loc><?php print site_url('main/sitemaps/'. $i); ?></loc>
      <lastmod><?php print w3cDate(strtotime($item)); ?></lastmod>
   </sitemap>
<?php $i++; endforeach; ?>
</sitemapindex>
<?php else : ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php $i= 1;  foreach($updates as $item): ?>
   <url>
      <loc><?php print CI::model('content')->getContentURLById($item['id']) ?></loc>
      <lastmod><?php print w3cDate(strtotime($item['updated_on'])); ?></lastmod>
      <priority>0.<?php print intval(999) - $i ?></priority>
   </url>
   
<?php $i++; endforeach; ?>
</urlset>

<?php endif; ?>