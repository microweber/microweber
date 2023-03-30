<template>

    <div v-if="showModal" style="visibility: hidden; position: absolute; width: 1px; height: 1px;"></div>
    <div v-if="showModal" class="mw-le-overlay active"></div>

    <Transition
        enter-active-class="animate__animated animate__backInLeft"
        leave-active-class="animate__animated animate__backOutLeft"
    >
        <div v-if="showModal"
             class="mw-le-dialog-block mw-le-modules-dialog active"
             style="inset:20px;transform:none;"
        >


            <div class="modules-list modules-list-defaultModules">
                <div class="modules-list-search-block"><input type="text" placeholder="Type to Search..."
                                                              class="modules-list-search-field"></div>
                <div class="modules-list-block">
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>miscellaneous</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/inline_table/inline_table.svg)"></div>
                            <div class="modules-list-block-item-title">Inline Table</div>
                            <div class="modules-list-block-item-description">Microweber</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/search/search.svg)"></div>
                            <div class="modules-list-block-item-title">Search</div>
                            <div class="modules-list-block-item-description">Module to search for content</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/popup/popup.svg)"></div>
                            <div class="modules-list-block-item-title">Pop-Up</div>
                            <div class="modules-list-block-item-description">Pop-Up</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/embed/embed.svg)"></div>
                            <div class="modules-list-block-item-title">Embed Code</div>
                            <div class="modules-list-block-item-description">Embed Code</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/logo/logo.svg)"></div>
                            <div class="modules-list-block-item-title">Logo</div>
                            <div class="modules-list-block-item-description">Logo</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/pdf/pdf.svg)"></div>
                            <div class="modules-list-block-item-title">PDF</div>
                            <div class="modules-list-block-item-description">PDF</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/categories/category_images/category_images.svg)"></div>
                            <div class="modules-list-block-item-title">Categories Images</div>
                            <div class="modules-list-block-item-description">Categories Images</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/accordion/accordion.svg)"></div>
                            <div class="modules-list-block-item-title">Accordion</div>
                            <div class="modules-list-block-item-description">Accordion</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/tabs/tabs.svg)"></div>
                            <div class="modules-list-block-item-title">Tabs</div>
                            <div class="modules-list-block-item-description">Tabs</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/breadcrumb/breadcrumb.svg)"></div>
                            <div class="modules-list-block-item-title">Breadcrumb</div>
                            <div class="modules-list-block-item-description">Breadcrumb navigation</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/faq/faq.svg)"></div>
                            <div class="modules-list-block-item-title">FAQ</div>
                            <div class="modules-list-block-item-description">FAQ</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/teamcard/teamcard.svg)"></div>
                            <div class="modules-list-block-item-title">Team Card</div>
                            <div class="modules-list-block-item-description">Team Card</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/multilanguage/multilanguage.svg)"></div>
                            <div class="modules-list-block-item-title">Multilanguage</div>
                            <div class="modules-list-block-item-description">Multilanguage</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/testimonials/testimonials.svg)"></div>
                            <div class="modules-list-block-item-title">Testimonials</div>
                            <div class="modules-list-block-item-description">Testimonials</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/tags/tags.svg)"></div>
                            <div class="modules-list-block-item-title">Tags</div>
                            <div class="modules-list-block-item-description">Tags module for your posts!</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>essentials</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/title/title.svg)"></div>
                            <div class="modules-list-block-item-title">Title</div>
                            <div class="modules-list-block-item-description">Microweber</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/text/text.svg)"></div>
                            <div class="modules-list-block-item-title">Text</div>
                            <div class="modules-list-block-item-description">Simple text</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/spacer/spacer.svg)"></div>
                            <div class="modules-list-block-item-title">Spacer</div>
                            <div class="modules-list-block-item-description">Microweber</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/text/empty_element.svg)"></div>
                            <div class="modules-list-block-item-title">Empty Element</div>
                            <div class="modules-list-block-item-description">Microweber</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/text/multiple_columns.svg)"></div>
                            <div class="modules-list-block-item-title">Multiple Columns</div>
                            <div class="modules-list-block-item-description">Microweber</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/btn/btn.svg)"></div>
                            <div class="modules-list-block-item-title">Button</div>
                            <div class="modules-list-block-item-description">Button</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/contact_form/contact_form.svg)"></div>
                            <div class="modules-list-block-item-title">Contact form</div>
                            <div class="modules-list-block-item-description">Contact form</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/google_maps/google_maps.svg)"></div>
                            <div class="modules-list-block-item-title">Google Maps</div>
                            <div class="modules-list-block-item-description">Google Maps</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/posts/posts.svg)"></div>
                            <div class="modules-list-block-item-title">Posts List</div>
                            <div class="modules-list-block-item-description">Posts List</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/content/content.svg)"></div>
                            <div class="modules-list-block-item-title">Content</div>
                            <div class="modules-list-block-item-description">Shows dynamic content</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/menu/menu.svg)"></div>
                            <div class="modules-list-block-item-title">Menu</div>
                            <div class="modules-list-block-item-description">Navigation menu for pages and links.</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>media</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/picture/picture.svg)"></div>
                            <div class="modules-list-block-item-title">Picture</div>
                            <div class="modules-list-block-item-description">Picture</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/pictures/pictures.svg)"></div>
                            <div class="modules-list-block-item-title">Picture Gallery</div>
                            <div class="modules-list-block-item-description">Picture Gallery</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/video/video.svg)"></div>
                            <div class="modules-list-block-item-title">Video</div>
                            <div class="modules-list-block-item-description">Video</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/image_rollover/image_rollover.svg)"></div>
                            <div class="modules-list-block-item-title">Image Rollover</div>
                            <div class="modules-list-block-item-description">Image Rollover</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/slider/slider.svg)"></div>
                            <div class="modules-list-block-item-title">Slider</div>
                            <div class="modules-list-block-item-description">Slider</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/audio/audio.svg)"></div>
                            <div class="modules-list-block-item-title">Audio</div>
                            <div class="modules-list-block-item-description">Microweber</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/beforeafter/beforeafter.svg)"></div>
                            <div class="modules-list-block-item-title">Before/After</div>
                            <div class="modules-list-block-item-description">Before/After</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/carousel_grid/carousel_grid.svg)"></div>
                            <div class="modules-list-block-item-title">Carousel Grid</div>
                            <div class="modules-list-block-item-description">Microweber Carousel Grid</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>social</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/social_links/social_links.svg)"></div>
                            <div class="modules-list-block-item-title">Social Links</div>
                            <div class="modules-list-block-item-description">Social Links</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/facebook_like/facebook_like.svg)"></div>
                            <div class="modules-list-block-item-title">Facebook Like</div>
                            <div class="modules-list-block-item-description">Facebook Like</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/facebook_page/facebook_page.svg)"></div>
                            <div class="modules-list-block-item-title">Facebook page</div>
                            <div class="modules-list-block-item-description">Facebook page integration for your
                                website!
                            </div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/tweet_embed/tweet_embed.svg)"></div>
                            <div class="modules-list-block-item-title">Tweet Embed</div>
                            <div class="modules-list-block-item-description">Tweet Embed</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/twitter_feed/twitter_feed.svg)"></div>
                            <div class="modules-list-block-item-title">Twitter feed</div>
                            <div class="modules-list-block-item-description">Feed of tweets</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/sharer/sharer.svg)"></div>
                            <div class="modules-list-block-item-title">Sharer</div>
                            <div class="modules-list-block-item-description">Sharer</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>store</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/shop/products/products.svg)"></div>
                            <div class="modules-list-block-item-title">Products</div>
                            <div class="modules-list-block-item-description">Products</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/shop/cart_add/cart_add.svg)"></div>
                            <div class="modules-list-block-item-title">Add to cart</div>
                            <div class="modules-list-block-item-description">Add to cart</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/shop/shop.svg)"></div>
                            <div class="modules-list-block-item-title">Online shop</div>
                            <div class="modules-list-block-item-description">Online shop</div>

                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>navigation</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/pages/pages.svg)"></div>
                            <div class="modules-list-block-item-title">Pages Menu</div>
                            <div class="modules-list-block-item-description">Pages Menu</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/categories/categories.svg)"></div>
                            <div class="modules-list-block-item-title">Categories</div>
                            <div class="modules-list-block-item-description">Categories</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>users</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/users/forgot_password/forgot_password.svg)"></div>
                            <div class="modules-list-block-item-title">Forgot password</div>
                            <div class="modules-list-block-item-description">Microweber</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/users/login/login.svg)"></div>
                            <div class="modules-list-block-item-title">Login</div>
                            <div class="modules-list-block-item-description">Login</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/users/register/register.svg)"></div>
                            <div class="modules-list-block-item-title">Registration</div>
                            <div class="modules-list-block-item-description">Microweber</div>

                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>marketing</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/newsletter/newsletter.svg)"></div>
                            <div class="modules-list-block-item-title">Newsletter</div>
                            <div class="modules-list-block-item-description">Newsletter</div>

                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>content</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/blog/blog.svg)"></div>
                            <div class="modules-list-block-item-title">Blog</div>
                            <div class="modules-list-block-item-description">Blog</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/comments/comments.svg)"></div>
                            <div class="modules-list-block-item-title">Comments</div>
                            <div class="modules-list-block-item-description">Comments</div>

                        </div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-true">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/layouts/layouts.svg)"></div>
                            <div class="modules-list-block-item-title">Layouts</div>
                            <div class="modules-list-block-item-description">Layouts</div>
                            <span class="modules-list-block-item-locked-badge">PRO</span>
                        </div>
                    </div>
                    <div class="modules-list-block-category-section">
                        <div class="modules-list-block-category-section-title"><h5>other</h5></div>
                        <div class="modules-list-block-item modules-list-block-item-is-locked-false">
                            <div class="modules-list-block-item-picture"
                                 style="background-image: url(http://127.0.0.1:8000/userfiles/modules/highlight_code/highlight_code.svg)"></div>
                            <div class="modules-list-block-item-title">highlight_code</div>
                            <div class="modules-list-block-item-description">highlight_code</div>

                        </div>
                    </div>


                    <div class="modules-list-block-no-results" style="display: none;">Nothing found...</div>

                </div>
            </div>


        </div>
    </Transition>

    <div v-if="showModal" v-on:click="showModal = false" class="mw-le-dialog-close active"></div>

</template>

<style>
@import "//cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css";
</style>

<script>
export default {
    methods: {
        getLayoutsList() {
            return mw.app.modules.list();
        }
    },
    components: {},
    mounted() {
        const instance = this;
        this.getLayoutsList().then(function (data) {
            instance.layoutsList = data;
        });

        this.emitter.on("live-edit-ui-show", show => {
            if (show == 'show-modules') {
                if (this.showModal == false) {
                    this.showModal = true;
                } else {
                    this.showModal = false;
                }
            }
        });
    },
    data() {
        return {
            keyword: '',
            category: '',
            layoutsList: null,
            showModal: false
        }
    }
}
</script>




