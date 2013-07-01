

</div> <!-- /#content -->

<div id="footer">
    <div id="footer-top">
        <div class="container">

            <div class="row">
                <div class="span4">
                    <h5>About</h5>
                    <p>
                      Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                      Curabitur tempor sapien vel nisl facilisis, eu sollicitudin nisi bibendum.
                    </p>
                    <p>
                      Praesent ut nisl tortor. Aliquam porta, lorem at accumsan mollis, tortor tellus mattis sapien, id interdum risus nunc rutrum nisi.
                      Nulla metus urna, tincidunt ac nibh vel, bibendum congue lectus.
                    </p>

                </div>
                <div class="span4">
                   <h5>Latest Products</h5>
                   <module
                        type="shop/products"
                        template="sidebar"
                        data-hide-paging="true"
                        data-limit="2"
                        data-description-length="80"
                        data-show="thumbnail,title,description,price">

                </div>
                <div class="span4">
                    <h5>Latest Posts</h5>
                    <module
                        type="posts"
                        template="sidebar"
                        data-limit="2"
                        data-description-length="80"
                        data-hide-paging="true"
                        data-show="thumbnail,title,description">
                </div>

            </div>

        </div>
    </div>
    <div id="footer-bottom">
        <div class="container">

        <div class="row">
            <div class="span6"><address>Copyright &copy; <?php print date('Y');  ?> All rights reserved</address></div>
            <div class="span6"><module type="menu" template="minimal"></div>
        </div>






        </div>
    </div>

</div> <!-- /#footer -->

</body></html>