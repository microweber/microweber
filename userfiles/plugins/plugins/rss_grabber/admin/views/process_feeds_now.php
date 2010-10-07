<?php if(!empty($feeds)): ?>

<?php foreach($feeds as $feed) : ?>
<hr />
<pre>
<?php $rss_grabber->rssGrabAndProcessFeeds($feed['id']); ?>
</pre>




<?php endforeach; ?>
<?php else: ?>

<hr />
<pre>
No feeds to get!
</pre>
<?php endif; ?>