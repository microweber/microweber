<span class="left">Share: &nbsp;</span>
            <span class="share">
                <a class="sh share-twitter title-tip" target="_blank" title="Share on Twitter" href="http://twitter.com/home?status=<?php print $the_post['content_title']; ?> <?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>">Twitter</a>
                <a class="sh share-facebook title-tip" target="_blank" title="Share on facebook" href="http://www.facebook.com/share.php?u=<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>&t=<?php print $the_post['content_title']; ?>">Facebook</a>
                <a class="sh share-email title-tip" target="_blank" title="Email to friend" href="mailto:?subject=<?php print $the_post['content_title']; ?>&BODY=<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>)">Email</a>

                <span class="share-more-content">
                  <a class="sh share-favourites title-tip" title="Add to favourites" href="<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>" onclick="bookmark('<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>', '<?php print $the_post['content_title']; ?>', this);" title="<?php print $the_post['content_title']; ?>">Favorites</a>
                  <a class="sh share-digg title-tip" target="_blank" title="Share on Digg" href="http://digg.com/submit?url=<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>&amp;title=<?php print $the_post['content_title']; ?>">Digg</a>
                  <a class="sh share-delicious title-tip" target="_blank" title="Share on delicious" href="http://del.icio.us/post?v=4&noui&jump=close&url=<?php print $this->content_model->contentGetHrefForPostId($the_post['id']) ; ?>&title=<?php print $the_post['content_title']; ?>">Delicious</a>
                </span>
                <a href="#" class="share-more">More</a>
            </span>


