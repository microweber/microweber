<?php

/*

type: layout
content_type: dynamic
name: Homepage layout

description: Home layout

*/

?>
<? include THIS_TEMPLATE_DIR. "header.php"; ?>



   <div class="row">

                <div class="span8">

                    <h1 class="header">
                        Maecenas Euismod Elit
                    </h1>

                    <img src="img/blog_post.jpg" class="post_pic">

                    <div class="post_content">
                        <p>
                            There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn't anything embarrassing hidden in the middle of text.
                        </p>
                        <p>All the Lorem Ipsum generators on the Internet tend to repeat predefined chunks as necessary, making this the first true generator on the Internet. It uses a dictionary of over 200 Latin words, combined with a handful of model sentence structures, to generate Lorem Ipsum which looks reasonable. The generated Lorem Ipsum is therefore always free from repetition, injected humour, or non-characteristic words etc.</p>
                        <p>At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga.</p>
                        <p>Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat.</p>

                        <div class="author">Alejandra Galvan</div>
                        <div class="date">Oct 12, 2012</div>
                    </div>

                    <div class="comments">
                        <h4>Comments</h4>

                        <div class="comment">
                            <div class="row">
                                <div class="span1">
                                    <img class="img-circle author_pic" src="img/pic1.jpg">
                                </div>
                                <div class="span6">
                                    <div class="name">
                                        Alejandra Galvan
                                        <a class="reply" href="#">Reply</a>
                                    </div>
                                    <div class="date">
                                        Oct 12, 2012
                                    </div>
                                    <div class="response">
                                        There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <div class="row">
                                <div class="span1">
                                    <img class="img-circle author_pic" src="img/pic1.jpg">
                                </div>
                                <div class="span6">
                                    <div class="name">
                                        Alejandra Galvan
                                        <a class="reply" href="#">Reply</a>
                                    </div>
                                    <div class="date">
                                        Oct 12, 2012
                                    </div>
                                    <div class="response">
                                        There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment reply_to">
                            <div class="row">
                                <div class="span1 offset1">
                                    <img class="img-circle author_pic" src="img/pic1.jpg">
                                </div>
                                <div class="span5">
                                    <div class="name">
                                        Alejandra Galvan
                                        <a class="reply" href="#">Reply</a>
                                    </div>
                                    <div class="date">
                                        Oct 12, 2012
                                    </div>
                                    <div class="response">
                                        There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment">
                            <div class="row">
                                <div class="span1">
                                    <img class="img-circle author_pic" src="img/pic1.jpg">
                                </div>
                                <div class="span6">
                                    <div class="name">
                                        Alejandra Galvan
                                        <a class="reply" href="#">Reply</a>
                                    </div>
                                    <div class="date">
                                        Oct 12, 2012
                                    </div>
                                    <div class="response">
                                        There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don't look even slightly believable.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="new_comment">
                        <h4>Add Comment</h4>
                        <form>
                            <div class="row">
                                <div class="span3">
                                    <input type="text" name="name" placeholder="Name">
                                </div>
                                <div class="span3">
                                    <input type="text" name="email" placeholder="Email">
                                </div>
                            </div>
                            <div class="row">
                                <div class="span6">
                                    <textarea rows="7" placeholder="Comments"></textarea>
                                </div>
                            </div>
                            <div class="row">
                                <div class="span6">
                                    <a class="btn" href="#">Add comment</a>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>

                <div class="span3 sidebar offset1">

                    <input type="text" placeholder="Search" class="input-large search-query">

                    <h4 class="sidebar_header">
                        Menu
                    </h4>

                    <ul class="sidebar_menu">
                        <li><a href="#">Suspendisse Semper Ipsum</a></li>
                        <li><a href="#">Maecenas Euismod Elit</a></li>
                        <li><a href="#">Suspendisse Semper Ipsum</a></li>
                        <li><a href="#">Maecenas Euismod Elit</a></li>
                        <li><a href="#">Suspendisse Semper Ipsum</a></li>
                    </ul>

                    <h4 class="sidebar_header">
                        Recent posts
                    </h4>

                    <ul class="recent_posts">
                        <li>
                            <div class="row">
                                <div class="span1">
                                    <a href="#">
                                        <img src="img/pic_blog.png" class="thumb">
                                    </a>
                                </div>
                                <div class="span2">
                                    <a href="#" class="link">Suspendisse Semper Ipsum</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <div class="span1">
                                    <a href="#">
                                        <img src="img/pic_blog.png" class="thumb">
                                    </a>
                                </div>
                                <div class="span2">
                                    <a href="#" class="link">Maecenas Euismod Elit</a>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="row">
                                <div class="span1">
                                    <a href="#">
                                        <img src="img/pic_blog.png" class="thumb">
                                    </a>
                                </div>
                                <div class="span2">
                                    <a href="#" class="link">Suspendisse Semper Ipsum</a>
                                </div>
                            </div>
                        </li>
                    </ul>

                </div>

            </div>




<? include THIS_TEMPLATE_DIR. "footer.php"; ?>
