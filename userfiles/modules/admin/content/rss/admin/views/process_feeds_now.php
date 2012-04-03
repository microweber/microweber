<? if(!empty($feeds)): ?>

<? foreach($feeds as $feed) : ?>
<hr />
<pre>
<? $rss_grabber->rssGrabAndProcessFeeds($feed['id']); ?>
</pre>




<? endforeach; ?>
<? else: ?>

<hr />
<pre>
No feeds to get!
</pre>
<? endif; ?>