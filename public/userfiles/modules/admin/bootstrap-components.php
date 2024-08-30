<div class="container-fluid  ">

    <div class="container ">
        <div class="row">

            <h1 class="text-center   py-5"> BOOTSTRAP COMPONENTS </h1>

                <!--ACCORDION -->

                <h1 class="my-5">ACCORDIONS</h1>

            <div class="col-12 my-5">

                <h3 class="py-3">Example</h3>
                <div class="accordion" id="accordionExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Accordion Item #1
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Accordion Item #2
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Accordion Item #3
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Flush</h3>

                <div class="accordion accordion-flush" id="accordionFlushExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseOne" aria-expanded="false" aria-controls="flush-collapseOne">
                                Accordion Item #1
                            </button>
                        </h2>
                        <div id="flush-collapseOne" class="accordion-collapse collapse" aria-labelledby="flush-headingOne" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the first item's accordion body.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo" aria-expanded="false" aria-controls="flush-collapseTwo">
                                Accordion Item #2
                            </button>
                        </h2>
                        <div id="flush-collapseTwo" class="accordion-collapse collapse" aria-labelledby="flush-headingTwo" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the second item's accordion body. Let's imagine this being filled with some actual content.</div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="flush-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapseThree" aria-expanded="false" aria-controls="flush-collapseThree">
                                Accordion Item #3
                            </button>
                        </h2>
                        <div id="flush-collapseThree" class="accordion-collapse collapse" aria-labelledby="flush-headingThree" data-bs-parent="#accordionFlushExample">
                            <div class="accordion-body">Placeholder content for this accordion, which is intended to demonstrate the <code>.accordion-flush</code> class. This is the third item's accordion body. Nothing more exciting happening here in terms of content, but just filling up the space to make it look, at least at first glance, a bit more representative of how this would look in a real-world application.</div>
                        </div>
                    </div>
                </div>
            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Always open</h3>

                <div class="accordion" id="accordionPanelsStayOpenExample">
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingOne">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseOne" aria-expanded="true" aria-controls="panelsStayOpen-collapseOne">
                                Accordion Item #1
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseOne" class="accordion-collapse collapse show" aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">
                                <strong>This is the first item's accordion body.</strong> It is shown by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseTwo" aria-expanded="false" aria-controls="panelsStayOpen-collapseTwo">
                                Accordion Item #2
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseTwo" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingTwo">
                            <div class="accordion-body">
                                <strong>This is the second item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="panelsStayOpen-headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#panelsStayOpen-collapseThree" aria-expanded="false" aria-controls="panelsStayOpen-collapseThree">
                                Accordion Item #3
                            </button>
                        </h2>
                        <div id="panelsStayOpen-collapseThree" class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingThree">
                            <div class="accordion-body">
                                <strong>This is the third item's accordion body.</strong> It is hidden by default, until the collapse plugin adds the appropriate classes that we use to style each element. These classes control the overall appearance, as well as the showing and hiding via CSS transitions. You can modify any of this with custom CSS or overriding our default variables. It's also worth noting that just about any HTML can go within the <code>.accordion-body</code>, though the transition does limit overflow.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!--END ACCORDIONS -->

            <hr>

            <!--ALERTS -->

            <h1 class="my-5">ALERTS</h1>

            <div class="col-12 my-5">
                <h3 class="py-3">Examples</h3>

                <div class="alert alert-primary" role="alert">
                    A simple primary alert—check it out!
                </div>
                <div class="alert alert-secondary" role="alert">
                    A simple secondary alert—check it out!
                </div>
                <div class="alert alert-success" role="alert">
                    A simple success alert—check it out!
                </div>
                <div class="alert alert-danger" role="alert">
                    A simple danger alert—check it out!
                </div>
                <div class="alert alert-warning" role="alert">
                    A simple warning alert—check it out!
                </div>
                <div class="alert alert-info" role="alert">
                    A simple info alert—check it out!
                </div>
                <div class="alert alert-light" role="alert">
                    A simple light alert—check it out!
                </div>
                <div class="alert alert-dark" role="alert">
                    A simple dark alert—check it out!
                </div>
            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Link Color</h3>

                <div class="alert alert-primary" role="alert">
                    A simple primary alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                </div>
                <div class="alert alert-secondary" role="alert">
                    A simple secondary alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                </div>
                <div class="alert alert-success" role="alert">
                    A simple success alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                </div>
                <div class="alert alert-danger" role="alert">
                    A simple danger alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                </div>
                <div class="alert alert-warning" role="alert">
                    A simple warning alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                </div>
                <div class="alert alert-info" role="alert">
                    A simple info alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                </div>
                <div class="alert alert-light" role="alert">
                    A simple light alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                </div>
                <div class="alert alert-dark" role="alert">
                    A simple dark alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Additional content</h3>

                <div class="alert alert-success" role="alert">
                    <h4 class="alert-heading">Well done!</h4>

                    <hr>
                    <p class="mb-0">Whenever you need to, be sure to use margin utilities to keep things nice and tidy.</p>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Icons</h3>

                <div class="alert alert-primary d-flex align-items-center" role="alert">
                     <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-exclamation-triangle-fill flex-shrink-0 me-2" viewBox="0 0 16 16" role="img" aria-label="Warning:">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </svg>
                    <div>
                        An example alert with an icon
                    </div>
                </div>
                <div class="mb-3">
                    <hr>
                </div>

                 <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" style="display: none;">
                    <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                    </symbol>
                    <symbol id="info-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm.93-9.412-1 4.705c-.07.34.029.533.304.533.194 0 .487-.07.686-.246l-.088.416c-.287.346-.92.598-1.465.598-.703 0-1.002-.422-.808-1.319l.738-3.468c.064-.293.006-.399-.287-.47l-.451-.081.082-.381 2.29-.287zM8 5.5a1 1 0 1 1 0-2 1 1 0 0 1 0 2z"/>
                    </symbol>
                    <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                    </symbol>
                </svg>

                <div class="alert alert-primary d-flex align-items-center" role="alert">
                     <svg fill="currentColor" class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Info:"><use xlink:href="#info-fill"/></svg>
                    <div>
                        An example alert with an icon
                    </div>
                </div>
                <div class="alert alert-success d-flex align-items-center" role="alert">
                     <svg fill="currentColor" class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                    <div>
                        An example success alert with an icon
                    </div>
                </div>
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                     <svg fill="currentColor" class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Warning:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div>
                        An example warning alert with an icon
                    </div>
                </div>
                <div class="alert alert-danger d-flex align-items-center" role="alert">
                     <svg fill="currentColor" class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                    <div>
                        An example danger alert with an icon
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Dismissing</h3>

                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <strong>Holy guacamole!</strong> You should check in on some of those fields below.
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            </div>

            <!--Badges -->

            <h1 class="my-5">BADGES</h1>

            <div class="col-12 my-5">
                <h3 class="py-3">Headings</h3>


                <h1>Example heading <span class="badge bg-secondary">New</span></h1>
                <h2>Example heading <span class="badge bg-secondary">New</span></h2>
                <h3>Example heading <span class="badge bg-secondary">New</span></h3>
                <h4>Example heading <span class="badge bg-secondary">New</span></h4>
                <h5>Example heading <span class="badge bg-secondary">New</span></h5>
                <h6>Example heading <span class="badge bg-secondary">New</span></h6>
            </div>



            <div class="col-12 my-5">
                <h3 class="py-3">Buttons</h3>

                <button type="button" class="btn btn-primary">
                    Notifications <span class="badge bg-secondary">4</span>
                </button>
            </div>

            <div class="col-12 my-5">
                <button type="button" class="btn btn-primary position-relative">
                    Inbox
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        99+
                        <span class="visually-hidden">unread messages</span>
                    </span>
                </button>
            </div>

            <div class="col-12 my-5">
                <button type="button" class="btn btn-primary position-relative">
                    Profile
                    <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                        <span class="visually-hidden">New alerts</span>
                    </span>
                </button>
            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Background Colors</h3>

                <span class="badge bg-primary">Primary</span>
                <span class="badge bg-secondary">Secondary</span>
                <span class="badge bg-success">Success</span>
                <span class="badge bg-danger">Danger</span>
                <span class="badge bg-warning  ">Warning</span>
                <span class="badge bg-info  ">Info</span>
                <span class="badge bg-azure-lt  ">Light</span>
                <span class="badge bg-dark">Dark</span>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Pill badges</h3>

                <span class="badge rounded-pill bg-primary">Primary</span>
                <span class="badge rounded-pill bg-secondary">Secondary</span>
                <span class="badge rounded-pill bg-success">Success</span>
                <span class="badge rounded-pill bg-danger">Danger</span>
                <span class="badge rounded-pill bg-warning  ">Warning</span>
                <span class="badge rounded-pill bg-info  ">Info</span>
                <span class="badge rounded-pill bg-azure-lt  ">Light</span>
                <span class="badge rounded-pill bg-dark">Dark</span>
            </div>



            <!--BREADCRUMB -->

            <h1 class="my-5">BREADCRUMB</h1>

            <div class="col-12 my-5">
                <h3 class="py-3">Normal</h3>


                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Home</li>
                    </ol>
                </nav>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Library</li>
                    </ol>
                </nav>

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item"><a href="#">Library</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Data</li>
                    </ol>
                </nav>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Dividers</h3>


                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Library</li>
                    </ol>
                </nav>


                        <!--            $breadcrumb-divider: quote(">");-->
            </div>

            <nav style="--bs-breadcrumb-divider: url(&#34;data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='8' height='8'%3E%3Cpath d='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z' fill='currentColor'/%3E%3C/svg%3E&#34;);" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Library</li>
                </ol>
            </nav>


            <nav style="--bs-breadcrumb-divider: '';" aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Library</li>
                </ol>
            </nav>


            <!--BUTTONS -->

            <h1 class="my-5">BUTTONS</h1>

            <div class="col-12 my-5">
                <h3 class="py-3">Normal</h3>


                <button type="button" class="btn btn-primary">Primary</button>
                <button type="button" class="btn btn-secondary">Secondary</button>
                <button type="button" class="btn btn-success">Success</button>
                <button type="button" class="btn btn-danger">Danger</button>
                <button type="button" class="btn btn-warning">Warning</button>
                <button type="button" class="btn btn-info">Info</button>
                <button type="button" class="btn btn-light">Light</button>
                <button type="button" class="btn btn-dark">Dark</button>

                <button type="button" class="btn btn-link">Link</button>
            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Button tags</h3>


                <a class="btn btn-primary" href="#" role="button">Link</a>
                <button class="btn btn-primary" type="submit">Button</button>
                <input class="btn btn-primary" type="button" value="Input">
                <input class="btn btn-primary" type="submit" value="Submit">
                <input class="btn btn-primary" type="reset" value="Reset">
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Outline buttons</h3>


                <button type="button" class="btn btn-outline-primary">Primary</button>
                <button type="button" class="btn btn-outline-secondary">Secondary</button>
                <button type="button" class="btn btn-outline-success">Success</button>
                <button type="button" class="btn btn-outline-danger">Danger</button>
                <button type="button" class="btn btn-outline-warning">Warning</button>
                <button type="button" class="btn btn-outline-info">Info</button>
                <button type="button" class="btn btn-outline-light">Light</button>
                <button type="button" class="btn btn-outline-dark">Dark</button>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Sizes</h3>


                <button type="button" class="btn btn-primary btn-lg">Large button</button>
                <button type="button" class="btn btn-secondary btn-lg">Large button</button>

            </div>

            <div class="col-12 my-5">


                <button type="button" class="btn btn-primary btn-sm">Small button</button>
                <button type="button" class="btn btn-secondary btn-sm">Small button</button>

            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Disabled state</h3>

                <button type="button" class="btn btn-lg btn-primary" disabled>Primary button</button>
                <button type="button" class="btn btn-secondary btn-lg" disabled>Button</button>

            </div>

            <div class="col-12 my-5">
                <a href="#" class="btn btn-primary btn-lg disabled" tabindex="-1" role="button" aria-disabled="true">Primary link</a>
                <a href="#" class="btn btn-secondary btn-lg disabled" tabindex="-1" role="button" aria-disabled="true">Link</a>
            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Block buttons</h3>

                <div class="d-grid gap-2">
                    <button class="btn btn-primary justify-content-center" type="button">Button</button>
                    <button class="btn btn-primary justify-content-center" type="button">Button</button>
                </div>

            </div>

            <div class="col-12 my-5">

                <div class="d-grid gap-2 d-md-block">
                    <button class="btn btn-primary" type="button">Button</button>
                    <button class="btn btn-primary" type="button">Button</button>
                </div>

            </div>

            <div class="col-12 my-5">
                <div class="d-grid gap-2 col-6 mx-auto">
                    <button class="btn btn-primary justify-content-center" type="button">Button</button>
                    <button class="btn btn-primary justify-content-center" type="button">Button</button>
                </div>

            </div>

            <div class="col-12 my-5">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button class="btn btn-primary me-md-2" type="button">Button</button>
                    <button class="btn btn-primary" type="button">Button</button>
                </div>

            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Button plugin</h3>

                <button type="button" class="btn btn-primary" data-bs-toggle="button" autocomplete="off">Toggle button</button>
                <button type="button" class="btn btn-primary active" data-bs-toggle="button" autocomplete="off" aria-pressed="true">Active toggle button</button>
                <button type="button" class="btn btn-primary" disabled data-bs-toggle="button" autocomplete="off">Disabled toggle button</button>

            </div>

            <div class="col-12 my-5">
                <a href="#" class="btn btn-primary" role="button" data-bs-toggle="button">Toggle link</a>
                <a href="#" class="btn btn-primary active" role="button" data-bs-toggle="button" aria-pressed="true">Active toggle link</a>
                <a href="#" class="btn btn-primary disabled" tabindex="-1" aria-disabled="true" role="button" data-bs-toggle="button">Disabled toggle link</a>
            </div>

            <h1 class="my-5">BUTTON GROUP</h1>

            <div class="col-12 my-5">
                <h3 class="py-3">Basic example</h3>


                <div class="btn-group" role="group" aria-label="Basic example">
                    <button type="button" class="btn btn-primary">Left</button>
                    <button type="button" class="btn btn-primary">Middle</button>
                    <button type="button" class="btn btn-primary">Right</button>
                </div>
            </div>

            <div class="col-12 my-5">
                <div class="btn-group">
                    <a href="#" class="btn btn-primary active" aria-current="page">Active link</a>
                    <a href="#" class="btn btn-primary">Link</a>
                    <a href="#" class="btn btn-primary">Link</a>
                </div>
            </div>


            <div class="col-12 my-5">

                <h3 class="py-3">Mixed styles</h3>

                <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                    <button type="button" class="btn btn-danger">Left</button>
                    <button type="button" class="btn btn-warning">Middle</button>
                    <button type="button" class="btn btn-success">Right</button>
                </div>
            </div>

            <div class="col-12 my-5">

                <h3 class="py-3">Outline styles</h3>

                <div class="btn-group" role="group" aria-label="Basic outlined example">
                    <button type="button" class="btn btn-outline-primary">Left</button>
                    <button type="button" class="btn btn-outline-primary">Middle</button>
                    <button type="button" class="btn btn-outline-primary">Right</button>
                </div>
            </div>

            <div class="col-12 my-5">

                <h3 class="py-3">Checkbox and radio button groups</h3>

                <div class="btn-group" role="group" aria-label="Basic checkbox toggle button group">
                    <input type="checkbox" class="btn-check" id="btncheck1" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btncheck1">Checkbox 1</label>

                    <input type="checkbox" class="btn-check" id="btncheck2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btncheck2">Checkbox 2</label>

                    <input type="checkbox" class="btn-check" id="btncheck3" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btncheck3">Checkbox 3</label>
                </div>
            </div>


            <div class="col-12 my-5">

                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="btnradio" id="btnradio1" autocomplete="off" checked>
                    <label class="btn btn-outline-primary" for="btnradio1">Radio 1</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio2" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio2">Radio 2</label>

                    <input type="radio" class="btn-check" name="btnradio" id="btnradio3" autocomplete="off">
                    <label class="btn btn-outline-primary" for="btnradio3">Radio 3</label>
                </div>
            </div>

            <div class="col-12 my-5">

                <h3 class="py-3">Button toolbar</h3>

                <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group me-2" role="group" aria-label="First group">
                        <button type="button" class="btn btn-primary">1</button>
                        <button type="button" class="btn btn-primary">2</button>
                        <button type="button" class="btn btn-primary">3</button>
                        <button type="button" class="btn btn-primary">4</button>
                    </div>
                    <div class="btn-group me-2" role="group" aria-label="Second group">
                        <button type="button" class="btn btn-secondary">5</button>
                        <button type="button" class="btn btn-secondary">6</button>
                        <button type="button" class="btn btn-secondary">7</button>
                    </div>
                    <div class="btn-group" role="group" aria-label="Third group">
                        <button type="button" class="btn btn-info">8</button>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">

                <div class="btn-toolbar mb-3" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group me-2" role="group" aria-label="First group">
                        <button type="button" class="btn btn-outline-secondary">1</button>
                        <button type="button" class="btn btn-outline-secondary">2</button>
                        <button type="button" class="btn btn-outline-secondary">3</button>
                        <button type="button" class="btn btn-outline-secondary">4</button>
                    </div>
                    <div class="input-group">
                        <div class="input-group-text" id="btnGroupAddon">@</div>
                        <input type="text" class="form-control" placeholder="Input group example" aria-label="Input group example" aria-describedby="btnGroupAddon">
                    </div>
                </div>

                <div class="btn-toolbar justify-content-between" role="toolbar" aria-label="Toolbar with button groups">
                    <div class="btn-group" role="group" aria-label="First group">
                        <button type="button" class="btn btn-outline-secondary">1</button>
                        <button type="button" class="btn btn-outline-secondary">2</button>
                        <button type="button" class="btn btn-outline-secondary">3</button>
                        <button type="button" class="btn btn-outline-secondary">4</button>
                    </div>
                    <div class="input-group">
                        <div class="input-group-text" id="btnGroupAddon2">@</div>
                        <input type="text" class="form-control" placeholder="Input group example" aria-label="Input group example" aria-describedby="btnGroupAddon2">
                    </div>
                </div>
            </div>


            <div class="col-12 my-5">

                <h3 class="py-3">Nesting</h3>


                <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                    <button type="button" class="btn btn-primary">1</button>
                    <button type="button" class="btn btn-primary">2</button>

                    <div class="btn-group" role="group">
                        <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                            <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                            <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">

                <h3 class="py-3">Vertical variation</h3>


                <div class="btn-group-vertical">
                    <button type="button" class="btn btn-dark">Button</button>
                    <button type="button" class="btn btn-dark">Button</button>
                    <button type="button" class="btn btn-dark">Button</button>
                    <button type="button" class="btn btn-dark">Button</button>
                    <button type="button" class="btn btn-dark">Button</button>
                    <button type="button" class="btn btn-dark">Button</button>
                </div>


                <div class="btn-group-vertical" role="group" aria-label="Vertical button group">
                    <button type="button" class="btn btn-primary">Button</button>
                    <button type="button" class="btn btn-primary">Button</button>
                    <div class="btn-group" role="group">
                        <button id="btnGroupVerticalDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            Dropdown
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                            <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                            <li><a class="dropdown-item" href="#">Dropdown link</a></li>
                        </ul>
                    </div>
                    <button type="button" class="btn btn-primary">Button</button>
                    <button type="button" class="btn btn-primary">Button</button>
                </div>
            </div>

            <!--CARD -->

            <h1 class="my-5">Cards</h1>

            <div class="col-12 my-5">
                <h3 class="py-3">Normal</h3>


                <div class="card mb-4" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Body</h3>


                <div class="card mb-4">
                    <div class="card-body">
                        This is some text within a card body.
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Titles, text, and links</h3>


                <div class="card mb-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <h6 class="card-subtitle mb-2 text-muted">Card subtitle</h6>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Images</h3>


                <div class="card mb-4" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">List groups</h3>


                <div class="card mb-4" style="width: 18rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                </div>
            </div>

            <div class="col-12 my-5">

                <div class="card mb-4" style="width: 18rem;">
                    <div class="card-header">
                        Featured
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                </div>
            </div>

            <div class="col-12 my-5">
                <div class="card mb-4" style="width: 18rem;">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <div class="card-footer">
                        Card footer
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Kitchen sink</h3>


                <div class="card mb-4" style="width: 18rem;">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">An item</li>
                        <li class="list-group-item">A second item</li>
                        <li class="list-group-item">A third item</li>
                    </ul>
                    <div class="card-body">
                        <a href="#" class="card-link">Card link</a>
                        <a href="#" class="card-link">Another link</a>
                    </div>
                </div>
            </div>
            <div class="col-12 my-5">
                <h3 class="py-3">Header and footer</h3>


                <div class="card mb-4">
                    <div class="card-header">
                        Featured
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">


                <div class="card mb-4">
                    <h5 class="card-header">Featured</h5>
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <div class="card mb-4">
                    <div class="card-header">
                        Quote
                    </div>
                    <div class="card-body">
                        <blockquote class="blockquote mb-0">

                            <footer class="blockquote-footer">Someone famous in <cite title="Source Title">Source Title</cite></footer>
                        </blockquote>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <div class="card text-center">
                    <div class="card-header">
                        Featured
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                    <div class="card-footer text-muted">
                        2 days ago
                    </div>
                </div>
            </div>


            <div class="col-12 my-5">
                <div class="card text-center">
                    <div class="card-header">
                        Using grid markup
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Special title treatment</h5>
                                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="card mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Special title treatment</h5>
                                    <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                                    <a href="#" class="btn btn-primary">Go somewhere</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                    <h3 class="py-3">Using Utilities</h3>
                <div class="card w-75">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Button</a>
                    </div>
                </div>

                <div class="card w-50">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Button</a>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Using custom CSS</h3>

                <div class="card mb-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Text alignment</h3>

                <div class="card mb-4" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>

                <div class="card text-center" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>

                <div class="card text-end" style="width: 18rem;">
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Navigation</h3>

                <div class="card text-center">
                    <div class="card-header">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" aria-current="true" href="#">Active</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Link</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <div class="card text-center">
                    <div class="card-header">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item">
                                <a class="nav-link active" href="#">Active</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">Link</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </li>
                        </ul>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Special title treatment</h5>
                        <p class="card-text">With supporting text below as a natural lead-in to additional content.</p>
                        <a href="#" class="btn btn-primary">Go somewhere</a>
                    </div>
                </div>
            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Image Craps</h3>

                <div class="card mb-7">
                    <img src="..." class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    </div>
                </div>
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Card title</h5>
                        <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                    </div>
                    <img src="..." class="card-img-bottom" alt="...">
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Image overlays</h3>


                    <div class="card bg-dark text-white">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg card-img" width="100%" height="270" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Card image" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="50%" y="50%" fill="#dee2e6" dy=".3em">Card image</text></svg>

                        <div class="card-img-overlay">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <p class="card-text">Last updated 3 mins ago</p>
                        </div>
                    </div>

            </div>


            <div class="col-12 my-5">
                <h3 class="py-3">Horizontal</h3>

                <div class="card mb-7" style="max-width: 540px;">
                    <div class="row g-0">
                        <div class="col-md-4">
                             <svg fill="currentColor" class="bd-placeholder-img img-fluid rounded-start" width="100%" height="250" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="35%" y="50%" fill="#dee2e6" dy=".3em">Image</text></svg>

                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                                <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Card styles</h3>

                <div class="card text-white bg-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Primary card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card text-white bg-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Secondary card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card text-white bg-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Success card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card text-white bg-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Danger card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card   bg-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Warning card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card   bg-info mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Info card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card   bg-azure-lt mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Light card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card text-white bg-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Dark card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Border</h3>

                <div class="card border-primary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body text-primary">
                        <h5 class="card-title">Primary card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card border-secondary mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body text-secondary">
                        <h5 class="card-title">Secondary card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card border-success mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body text-success">
                        <h5 class="card-title">Success card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card border-danger mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body text-danger">
                        <h5 class="card-title">Danger card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card border-warning mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Warning card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card border-info mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Info card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card border-light mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body">
                        <h5 class="card-title">Light card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
                <div class="card border-dark mb-3" style="max-width: 18rem;">
                    <div class="card-header">Header</div>
                    <div class="card-body  ">
                        <h5 class="card-title">Dark card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Mixins utilities</h3>

                <div class="card border-success mb-3" style="max-width: 18rem;">
                    <div class="card-header bg-transparent border-success">Header</div>
                    <div class="card-body text-success">
                        <h5 class="card-title">Success card title</h5>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                    </div>
                    <div class="card-footer bg-transparent border-success">Footer</div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Card groups</h3>

                <div class="card-group">
                    <div class="card mb-4">
                         <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                    </div>
                    <div class="card mb-4">
                         <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                    </div>
                    <div class="card mb-4">
                         <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                            <p class="card-text"><small class="text-muted">Last updated 3 mins ago</small></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">


                <div class="card-group">
                    <div class="card mb-4">
                         <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </div>
                    </div>
                    <div class="card mb-4">
                         <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This card has supporting text below as a natural lead-in to additional content.</p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </div>
                    </div>
                    <div class="card mb-4">
                         <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="180" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                        <div class="card-body">
                            <h5 class="card-title">Card title</h5>
                            <p class="card-text">This is a wider card with supporting text below as a natural lead-in to additional content. This card has even longer content than the first to show that equal height action.</p>
                        </div>
                        <div class="card-footer">
                            <small class="text-muted">Last updated 3 mins ago</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Grid Cards</h3>


                <div class="row row-cols-1 row-cols-md-2 g-4">
                    <div class="col">
                        <div class="card mb-4">
                             <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-4">
                             <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-4">
                             <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card mb-4">
                             <svg fill="currentColor" class="bd-placeholder-img card-img-top" width="100%" height="140" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Image cap" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#868e96"></rect><text x="40%" y="50%" fill="#dee2e6" dy=".3em">Image cap</text></svg>

                            <div class="card-body">
                                <h5 class="card-title">Card title</h5>
                                <p class="card-text">This is a longer card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!--        CAROUSELS-->

            <h1 class="my-5">Carousels</h1>

            <h3 class="py-3">Slides only</h3>


            <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555" dy=".3em">First slide</text></svg>

                    </div>
                    <div class="carousel-item active">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>

                    </div>
                    <div class="carousel-item">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333" dy=".3em">Third slide</text></svg>

                    </div>
                </div>


            <h3 class="py-3">With controls</h3>

                <div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555" dy=".3em">First slide</text></svg>

                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>

                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333" dy=".3em">Third slide</text></svg>

                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

            <h3 class="py-3"> With Indicators</h3>

                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-label="Slide 1" aria-current="true"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2" class=""></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3" class=""></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555" dy=".3em">First slide</text></svg>

                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>

                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333" dy=".3em">Third slide</text></svg>

                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>


            <h3 class="py-3">With captions</h3>

                <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="0" class="active" aria-label="Slide 1" aria-current="true"></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="1" aria-label="Slide 2" class=""></button>
                        <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="2" aria-label="Slide 3" class=""></button>
                    </div>
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555" dy=".3em">First slide</text></svg>

                            <div class="carousel-caption d-none d-md-block">
                                <h5>First slide label</h5>

                            </div>
                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>

                            <div class="carousel-caption d-none d-md-block">
                                <h5>Second slide label</h5>

                            </div>
                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333" dy=".3em">Third slide</text></svg>

                            <div class="carousel-caption d-none d-md-block">
                                <h5>Third slide label</h5>

                            </div>
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

            <h3 class="py-3">Crossfate</h3>

                <div id="carouselExampleFade" class="carousel slide carousel-fade" data-bs-ride="carousel">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555" dy=".3em">First slide</text></svg>

                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>

                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333" dy=".3em">Third slide</text></svg>

                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleFade" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>


            <h3 class="py-3">Individual</h3>

            <div id="carouselExampleInterval" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="10000">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555" dy=".3em">First slide</text></svg>

                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>

                    </div>
                    <div class="carousel-item">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333" dy=".3em">Third slide</text></svg>

                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleInterval" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>

            <h3 class="py-3">Disable touch swipping</h3>

                <div id="carouselExampleControlsNoTouching" class="carousel slide" data-bs-touch="false" data-bs-interval="false">
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#777"></rect><text x="50%" y="50%" fill="#555" dy=".3em">First slide</text></svg>

                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#666"></rect><text x="50%" y="50%" fill="#444" dy=".3em">Second slide</text></svg>

                        </div>
                        <div class="carousel-item">
                             <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#555"></rect><text x="50%" y="50%" fill="#333" dy=".3em">Third slide</text></svg>

                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControlsNoTouching" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>

            <h3 class="py-3">Dark variant</h3>

            <div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="0" class="active" aria-label="Slide 1" aria-current="true"></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="1" aria-label="Slide 2" class=""></button>
                    <button type="button" data-bs-target="#carouselExampleDark" data-bs-slide-to="2" aria-label="Slide 3" class=""></button>
                </div>
                <div class="carousel-inner">
                    <div class="carousel-item active" data-bs-interval="10000">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: First slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#f5f5f5"></rect><text x="50%" y="50%" fill="#aaa" dy=".3em">First slide</text></svg>

                        <div class="carousel-caption d-none d-md-block">
                            <h5>First slide label</h5>

                        </div>
                    </div>
                    <div class="carousel-item" data-bs-interval="2000">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Second slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#eee"></rect><text x="50%" y="50%" fill="#bbb" dy=".3em">Second slide</text></svg>

                        <div class="carousel-caption d-none d-md-block">
                            <h5>Second slide label</h5>

                        </div>
                    </div>
                    <div class="carousel-item">
                         <svg fill="currentColor" class="bd-placeholder-img bd-placeholder-img-lg d-block w-100" width="800" height="400" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Placeholder: Third slide" preserveAspectRatio="xMidYMid slice" focusable="false"><title>Placeholder</title><rect width="100%" height="100%" fill="#e5e5e5"></rect><text x="50%" y="50%" fill="#999" dy=".3em">Third slide</text></svg>

                        <div class="carousel-caption d-none d-md-block">
                            <h5>Third slide label</h5>

                        </div>
                    </div>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>


            <!-- Close button-->

            <h1 class="my-5">Close button</h1>


            <div class="col-12 my-5">
                <h3 class="py-3">Example</h3>
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>

            <div class="col-12 my-5">
                <h3 class="py-3">Disabled state</h3>
                <button type="button" class="btn-close" disabled aria-label="Close"></button>
            </div>


            <h3 class="py-3">White variant</h3>
            <div class="col-12 py-5 bg-dark">
                <button type="button" class="btn-close btn-close-white" aria-label="Close"></button>
                <button type="button" class="btn-close btn-close-white" disabled aria-label="Close"></button>
            </div>


            <!-- Dropdowns -->



                <h1 class="my-5">Dropdowns</h1>

                <div class="bd-content ps-lg-4">
                     <div class="bd-example py-2">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                     </div>

                    <div class="bd-example py-2">
                        <div class="dropdown">
                            <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown link
                            </a>

                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Primary</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Secondary</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Success</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Info</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Warning</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">Danger</button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                    </div>


                    <h3 class="py-3" id="split-button">Split button<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#split-button" style="padding-left: 0.375em;"></a></h3>
                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-primary">Primary</button>
                            <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary">Secondary</button>
                            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-success">Success</button>
                            <button type="button" class="btn btn-success dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-info">Info</button>
                            <button type="button" class="btn btn-info dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-warning">Warning</button>
                            <button type="button" class="btn btn-warning dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                        <div class="btn-group">
                            <button type="button" class="btn btn-danger">Danger</button>
                            <button type="button" class="btn btn-danger dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div><!-- /btn-group -->
                    </div>
                    <h2 class="py-3" id="sizing">Sizing<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#sizing" style="padding-left: 0.375em;"></a></h2>

                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button class="btn btn-secondary btn-lg dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Large button
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-lg btn-secondary">Large split button</button>
                            <button type="button" class="btn btn-lg btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Small button
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-secondary">Small split button</button>
                            <button type="button" class="btn btn-sm btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                    <h2 class="py-3" id="dark-dropdowns">Dark dropdowns<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#dark-dropdowns" style="padding-left: 0.375em;"></a></h2>

                    <div class="bd-example py-2">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton2" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown button
                            </button>
                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="dropdownMenuButton2">
                                <li><a class="dropdown-item active" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                    </div>

                    <div class="bd-example py-2">
                        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                            <div class="container-fluid">
                                <a class="navbar-brand" href="#">Navbar</a>
                                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDarkDropdown" aria-controls="navbarNavDarkDropdown" aria-expanded="false" aria-label="Toggle navigation">
                                    <span class="navbar-toggler-icon"></span>
                                </button>
                                <div class="collapse navbar-collapse" id="navbarNavDarkDropdown">
                                    <ul class="navbar-nav">
                                        <li class="nav-item dropdown">
                                            <a class="nav-link dropdown-toggle" href="#" id="navbarDarkDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Dropdown
                                            </a>
                                            <ul class="dropdown-menu dropdown-menu-dark" aria-labelledby="navbarDarkDropdownMenuLink">
                                                <li><a class="dropdown-item" href="#">Action</a></li>
                                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </nav>
                    </div>
                    <h2 class="py-3" id="directions">Directions<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#directions" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-callout bd-callout-info">

                        </p></div>
                    <h4 id="rtl">RTL</h4>

                    <h3 class="py-3" id="dropup">Dropup<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#dropup" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropup
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-secondary">
                                Split dropup
                            </button>
                            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropdown</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                    <h3 class="py-3" id="dropright">Dropright<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#dropright" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <div class="btn-group dropend">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropright
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group dropend">
                            <button type="button" class="btn btn-secondary">
                                Split dropend
                            </button>
                            <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="visually-hidden">Toggle Dropright</span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                    </div>
                    <h3 class="py-3" id="dropleft">Dropleft<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#dropleft" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <div class="btn-group dropstart">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropleft
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Action</a></li>
                                <li><a class="dropdown-item" href="#">Another action</a></li>
                                <li><a class="dropdown-item" href="#">Something else here</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="#">Separated link</a></li>
                            </ul>
                        </div>
                        <div class="btn-group">
                            <div class="btn-group dropstart" role="group">
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span class="visually-hidden">Toggle Dropleft</span>
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                            <button type="button" class="btn btn-secondary">
                                Split dropstart
                            </button>
                        </div>
                    </div>
                    <h2 class="py-3" id="menu-items">Menu items<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#menu-items" style="padding-left: 0.375em;"></a></h2>

                    <div class="bd-example py-2">
                        <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu2" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                                <li><button class="dropdown-item" type="button">Action</button></li>
                                <li><button class="dropdown-item" type="button">Another action</button></li>
                                <li><button class="dropdown-item" type="button">Something else here</button></li>
                            </ul>
                        </div>
                    </div>

                    <div class="bd-example py-2">
                        <ul class="dropdown-menu">
                            <li><span class="dropdown-item-text">Dropdown item text</span></li>
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                    </div>
                    <h3 class="py-3" id="active">Active<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#active" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Regular link</a></li>
                            <li><a class="dropdown-item active" href="#" aria-current="true">Active link</a></li>
                            <li><a class="dropdown-item" href="#">Another link</a></li>
                        </ul>
                    </div>
                    <h3 class="py-3" id="disabled">Disabled<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#disabled" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Regular link</a></li>
                            <li><a class="dropdown-item disabled" href="#" tabindex="-1" aria-disabled="true">Disabled link</a></li>
                            <li><a class="dropdown-item" href="#">Another link</a></li>
                        </ul>
                    </div>


                    <h2 class="py-3" id="menu-alignment">Menu alignment<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#menu-alignment" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-callout bd-callout-info">
                        <strong>Heads up!</strong> Dropdowns are positioned thanks to Popper except when they are contained in a navbar.
                    </div>

                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Right-aligned menu example
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><button class="dropdown-item" type="button">Action</button></li>
                                <li><button class="dropdown-item" type="button">Another action</button></li>
                                <li><button class="dropdown-item" type="button">Something else here</button></li>
                            </ul>
                        </div>
                    </div>


                    <h3 class="py-3" id="responsive-alignment">Responsive alignment<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#responsive-alignment" style="padding-left: 0.375em;"></a></h3>
                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                Left-aligned but right aligned when large screen
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><button class="dropdown-item" type="button">Action</button></li>
                                <li><button class="dropdown-item" type="button">Another action</button></li>
                                <li><button class="dropdown-item" type="button">Something else here</button></li>
                            </ul>
                        </div>
                    </div>

                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                Right-aligned but left aligned when large screen
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                <li><button class="dropdown-item" type="button">Action</button></li>
                                <li><button class="dropdown-item" type="button">Another action</button></li>
                                <li><button class="dropdown-item" type="button">Something else here</button></li>
                            </ul>
                        </div>
                    </div>

                    <h3 class="py-3" id="alignment-options">Alignment options<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#alignment-options" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropdown
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Right-aligned menu
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                Left-aligned, right-aligned lg
                            </button>
                            <ul class="dropdown-menu dropdown-menu-lg-end">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                                Right-aligned, left-aligned lg
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-lg-start">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group dropstart">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropstart
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group dropend">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropend
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-secondary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                Dropup
                            </button>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>
                    </div>
                    <h2 class="py-3" id="menu-content">Menu content<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#menu-content" style="padding-left: 0.375em;"></a></h2>
                    <h3 class="py-3" id="headers">Headers<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#headers" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <ul class="dropdown-menu">
                            <li><h6 class="dropdown-header">Dropdown header</h6></li>
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                        </ul>
                    </div>
                    <h3 class="py-3" id="dividers">Dividers<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#dividers" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Separated link</a></li>
                        </ul>
                    </div>
                    <h3 class="py-3" id="text">Text<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#text" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                            Some example text that's free-flowing within the dropdown menu.

                        </p>
                        <div class="dropdown-menu p-4 text-muted" style="max-width: 200px;">
                            <p class="mb-0">
                                And this is more example text.
                            </p>
                        </div>
                    </div>
                    <h3 class="py-3" id="forms">Forms<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#forms" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <div class="dropdown-menu">
                            <form class="px-4 py-3">
                                <div class="mb-3">
                                    <label for="exampleDropdownFormEmail1" class="form-label">Email address</label>
                                    <input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleDropdownFormPassword1" class="form-label">Password</label>
                                    <input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                                </div>
                                <div class="mb-3">
                                    <div class="form-check">
                                        <input type="checkbox" class="form-check-input" id="dropdownCheck">
                                        <label class="form-check-label" for="dropdownCheck">
                                            Remember me
                                        </label>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-primary">Sign in</button>
                            </form>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#">New around here? Sign up</a>
                            <a class="dropdown-item" href="#">Forgot password?</a>
                        </div>
                    </div>
                    <div class="bd-example py-2">
                        <form class="dropdown-menu p-4">
                            <div class="mb-3">
                                <label for="exampleDropdownFormEmail2" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="exampleDropdownFormEmail2" placeholder="email@example.com">
                            </div>
                            <div class="mb-3">
                                <label for="exampleDropdownFormPassword2" class="form-label">Password</label>
                                <input type="password" class="form-control" id="exampleDropdownFormPassword2" placeholder="Password">
                            </div>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="dropdownCheck2">
                                    <label class="form-check-label" for="dropdownCheck2">
                                        Remember me
                                    </label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Sign in</button>
                        </form>
                    </div>
                    <h2 class="py-3" id="dropdown-options">Dropdown options<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#dropdown-options" style="padding-left: 0.375em;"></a></h2>

                    <div class="bd-example py-2">
                        <div class="d-flex">
                            <div class="dropdown me-1">
                                <button type="button" class="btn btn-secondary dropdown-toggle" id="dropdownMenuOffset" data-bs-toggle="dropdown" aria-expanded="false" data-bs-offset="10,20">
                                    Offset
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuOffset">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                </ul>
                            </div>
                            <div class="btn-group">
                                <button type="button" class="btn btn-secondary">Reference</button>
                                <button type="button" class="btn btn-secondary dropdown-toggle dropdown-toggle-split" id="dropdownMenuReference" data-bs-toggle="dropdown" aria-expanded="false" data-bs-reference="parent">
                                    <span class="visually-hidden">Toggle Dropdown</span>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuReference">
                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li><a class="dropdown-item" href="#">Separated link</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <h3 class="py-3" id="auto-close-behavior">Auto close behavior<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#auto-close-behavior" style="padding-left: 0.375em;"></a></h3>

                    <div class="bd-example py-2">
                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="defaultDropdown" data-bs-toggle="dropdown" data-bs-auto-close="true" aria-expanded="false">
                                Default dropdown
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="defaultDropdown">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickableOutside" data-bs-toggle="dropdown" data-bs-auto-close="inside" aria-expanded="false">
                                Clickable outside
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickableOutside">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickableInside" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                                Clickable inside
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickableInside">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>

                        <div class="btn-group">
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuClickable" data-bs-toggle="dropdown" data-bs-auto-close="false" aria-expanded="false">
                                Manual close
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuClickable">
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                                <li><a class="dropdown-item" href="#">Menu item</a></li>
                            </ul>
                        </div>
                    </div>
                </div>




                <!-- List group-->

                <h1 class="my-5">List group</h1>

                <div class="bd-content ps-lg-4">


                    <h2 class="py-3" id="basic-example">Basic example<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#basic-example" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <ul class="list-group">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                            <li class="list-group-item">A fourth item</li>
                            <li class="list-group-item">And a fifth one</li>
                        </ul>
                    </div>
                    <h2 class="py-3" id="active-items">Active items<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#active-items" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <ul class="list-group">
                            <li class="list-group-item active" aria-current="true">An active item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                            <li class="list-group-item">A fourth item</li>
                            <li class="list-group-item">And a fifth one</li>
                        </ul>
                    </div>
                    <h2 class="py-3" id="disabled-items">Disabled items<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#disabled-items" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <ul class="list-group">
                            <li class="list-group-item disabled" aria-disabled="true">A disabled item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                            <li class="list-group-item">A fourth item</li>
                            <li class="list-group-item">And a fifth one</li>
                        </ul>
                    </div>
                    <h2 class="py-3" id="links-and-buttons">Links and buttons<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#links-and-buttons" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                The current link item
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">A second link item</a>
                            <a href="#" class="list-group-item list-group-item-action">A third link item</a>
                            <a href="#" class="list-group-item list-group-item-action">A fourth link item</a>
                            <a href="#" class="list-group-item list-group-item-action disabled" tabindex="-1" aria-disabled="true">A disabled link item</a>
                        </div>
                    </div>
                    <div class="bd-example py-3">
                        <div class="list-group">
                            <button type="button" class="list-group-item list-group-item-action active" aria-current="true">
                                The current button
                            </button>
                            <button type="button" class="list-group-item list-group-item-action">A second item</button>
                            <button type="button" class="list-group-item list-group-item-action">A third button item</button>
                            <button type="button" class="list-group-item list-group-item-action">A fourth button item</button>
                            <button type="button" class="list-group-item list-group-item-action" disabled="">A disabled button item</button>
                        </div>
                    </div>
                    <h2 class="py-3" id="flush">Flush<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#flush" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                            <li class="list-group-item">A fourth item</li>
                            <li class="list-group-item">And a fifth one</li>
                        </ul>
                    </div>
                    <h2 class="py-3" id="numbered">Numbered<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#numbered" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item">Cras justo odio</li>
                            <li class="list-group-item">Cras justo odio</li>
                            <li class="list-group-item">Cras justo odio</li>
                        </ol>
                    </div>
                    <div class="bd-example py-3">
                        <ol class="list-group list-group-numbered">
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Subheading</div>
                                    Cras justo odio
                                </div>
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Subheading</div>
                                    Cras justo odio
                                </div>
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-start">
                                <div class="ms-2 me-auto">
                                    <div class="fw-bold">Subheading</div>
                                    Cras justo odio
                                </div>
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                        </ol>
                    </div>
                    <h2 class="py-3" id="horizontal">Horizontal<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#horizontal" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">

                        <ul class="list-group list-group-horizontal">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                        <ul class="list-group list-group-horizontal-sm">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                        <ul class="list-group list-group-horizontal-md">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                        <ul class="list-group list-group-horizontal-lg">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                        <ul class="list-group list-group-horizontal-xl">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                        <ul class="list-group list-group-horizontal-xxl">
                            <li class="list-group-item">An item</li>
                            <li class="list-group-item">A second item</li>
                            <li class="list-group-item">A third item</li>
                        </ul>
                    </div>
                    <h2 class="py-3" id="contextual-classes">Contextual classes<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#contextual-classes" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <ul class="list-group">
                            <li class="list-group-item">A simple default list group item</li>

                            <li class="list-group-item list-group-item-primary">A simple primary list group item</li>
                            <li class="list-group-item list-group-item-secondary">A simple secondary list group item</li>
                            <li class="list-group-item list-group-item-success">A simple success list group item</li>
                            <li class="list-group-item list-group-item-danger">A simple danger list group item</li>
                            <li class="list-group-item list-group-item-warning">A simple warning list group item</li>
                            <li class="list-group-item list-group-item-info">A simple info list group item</li>
                            <li class="list-group-item list-group-item-light">A simple light list group item</li>
                            <li class="list-group-item list-group-item-dark">A simple dark list group item</li>
                        </ul>
                    </div>
                    <div class="bd-example py-3">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action">A simple default list group item</a>

                            <a href="#" class="list-group-item list-group-item-action list-group-item-primary">A simple primary list group item</a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-secondary">A simple secondary list group item</a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-success">A simple success list group item</a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-danger">A simple danger list group item</a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-warning">A simple warning list group item</a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-info">A simple info list group item</a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-light">A simple light list group item</a>
                            <a href="#" class="list-group-item list-group-item-action list-group-item-dark">A simple dark list group item</a>
                        </div>
                    </div
                    <div class="bd-callout bd-callout-info">
                        <h5 id="conveying-meaning-to-assistive-technologies">Conveying meaning to assistive technologies</h5>
                        </p></div>

                    <h2 class="py-3" id="with-badges">With badges<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#with-badges" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                A list item
                                <span class="badge bg-primary rounded-pill">14</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                A second list item
                                <span class="badge bg-primary rounded-pill">2</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                A third list item
                                <span class="badge bg-primary rounded-pill">1</span>
                            </li>
                        </ul>
                    </div>
                    <h2 class="py-3" id="custom-content">Custom content<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#custom-content" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <div class="list-group">
                            <a href="#" class="list-group-item list-group-item-action active" aria-current="true">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">List group item heading</h5>
                                    <small>3 days ago</small>
                                </div>
                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                <small>And some small print.</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">List group item heading</h5>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                <small class="text-muted">And some muted small print.</small>
                            </a>
                            <a href="#" class="list-group-item list-group-item-action">
                                <div class="d-flex w-100 justify-content-between">
                                    <h5 class="mb-1">List group item heading</h5>
                                    <small class="text-muted">3 days ago</small>
                                </div>
                                <p class="mb-1">Some placeholder content in a paragraph.</p>
                                <small class="text-muted">And some muted small print.</small>
                            </a>
                        </div>
                    </div>
                    <h2 class="py-3" id="checkboxes-and-radios">Checkboxes and radios<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#checkboxes-and-radios" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3">
                        <ul class="list-group">
                            <li class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                                First checkbox
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                                Second checkbox
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                                Third checkbox
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                                Fourth checkbox
                            </li>
                            <li class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="" aria-label="...">
                                Fifth checkbox
                            </li>
                        </ul>
                    </div>
                    <div class="bd-example py-3">
                        <div class="list-group">
                            <label class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="">
                                First checkbox
                            </label>
                            <label class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="">
                                Second checkbox
                            </label>
                            <label class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="">
                                Third checkbox
                            </label>
                            <label class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="">
                                Fourth checkbox
                            </label>
                            <label class="list-group-item">
                                <input class="form-check-input me-2" type="checkbox" value="">
                                Fifth checkbox
                            </label>
                        </div>
                    </div>
                    <h3 id="loop">Loop<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#loop" style="padding-left: 0.375em;"></a></h3>

                    <h2 class="py-3" id="javascript-behavior">JavaScript behavior<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#javascript-behavior" style="padding-left: 0.375em;"></a></h2>
                    <div class="bd-example py-3" role="tabpanel">
                        <div class="row">
                            <div class="col-4">
                                <div class="list-group" id="list-tab" role="tablist">
                                    <a class="list-group-item list-group-item-action active" id="list-home-list" data-bs-toggle="tab" href="#list-home" role="tab" aria-controls="list-home">Home</a>
                                    <a class="list-group-item list-group-item-action" id="list-profile-list" data-bs-toggle="tab" href="#list-profile" role="tab" aria-controls="list-profile">Profile</a>
                                    <a class="list-group-item list-group-item-action" id="list-messages-list" data-bs-toggle="tab" href="#list-messages" role="tab" aria-controls="list-messages">Messages</a>
                                    <a class="list-group-item list-group-item-action" id="list-settings-list" data-bs-toggle="tab" href="#list-settings" role="tab" aria-controls="list-settings">Settings</a>
                                </div>
                            </div>
                            <div class="col-8">
                                <div class="tab-content" id="nav-tabContent">
                                    <div class="tab-pane fade show active" id="list-home" role="tabpanel" aria-labelledby="list-home-list">
                                    </div>
                                    <div class="tab-pane fade" id="list-profile" role="tabpanel" aria-labelledby="list-profile-list">
                                    </div>
                                    <div class="tab-pane fade" id="list-messages" role="tabpanel" aria-labelledby="list-messages-list">
                                    </div>
                                    <div class="tab-pane fade" id="list-settings" role="tabpanel" aria-labelledby="list-settings-list">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                    <!-- Modals -->



                    <h1 class="my-5">Modals</h1>


                         <div class="bd-example bd-example-modal">
                            <div class="modal" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Modal title</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <p>Modal body text goes here.</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3 id="live-demo">Live demo<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#live-demo" style="padding-left: 0.375em;"></a></h3>
                        <p>Toggle a working modal demo by clicking the button below. It will slide down and fade in from the top of the page.</p>
                        <div class="modal fade" id="exampleModalLive" tabindex="-1" aria-labelledby="exampleModalLiveLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLiveLabel">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>Woohoo, you're reading this text in a modal!</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bd-example py-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLive">
                                Launch demo modal
                            </button>
                        </div>
                        <h3 id="static-backdrop">Static backdrop<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#static-backdrop" style="padding-left: 0.375em;"></a></h3>
                        <p>When backdrop is set to static, the modal will not close when clicking outside it. Click the button below to try it.</p>
                        <div class="modal fade" id="staticBackdropLive" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLiveLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLiveLabel">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>I will not close if you click outside me. Don't even try to press escape key.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Understood</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bd-example py-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdropLive">
                                Launch static backdrop modal
                            </button>
                        </div>
                        <h3 id="scrolling-long-content">Scrolling long content<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#scrolling-long-content" style="padding-left: 0.375em;"></a></h3>
                        <p>When modals become too long for the user’s viewport or device, they scroll independent of the page itself. Try the demo below to see what we mean.</p>
                        <div class="modal fade" id="exampleModalLong" tabindex="-1" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body" style="min-height: 1500px">
                                        <p>This is some placeholder content to show the scrolling behavior for modals. Instead of repeating the text the modal, we use an inline style set a minimum height, thereby extending the length of the overall modal and demonstrating the overflow scrolling. When content becomes longer than the height of the viewport, scrolling will move the modal as needed.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bd-example py-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalLong">
                                Launch demo modal
                            </button>
                        </div>
                        <p>You can also create a scrollable modal that allows scroll the modal body by adding <code>.modal-dialog-scrollable</code> to <code>.modal-dialog</code>.</p>
                        <div class="modal fade" id="exampleModalScrollable" tabindex="-1" aria-labelledby="exampleModalScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalScrollableTitle">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>This is some placeholder content to show the scrolling behavior for modals. We use repeated line breaks to demonstrate how content can exceed minimum inner height, thereby showing inner scrolling. When content becomes longer than the prefedined max-height of modal, content will be cropped and scrollable within the modal.</p>
                                        <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
                                        <p>This content should appear at the bottom after you scroll.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bd-example py-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
                                Launch demo modal
                            </button>
                        </div>
                        <h3 id="vertically-centered">Vertically centered<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#vertically-centered" style="padding-left: 0.375em;"></a></h3>
                        <p>Add <code>.modal-dialog-centered</code> to <code>.modal-dialog</code> to vertically center the modal.</p>
                        <div class="modal fade" id="exampleModalCenter" tabindex="-1" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenterTitle">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>This is a vertically centered modal.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal fade" id="exampleModalCenteredScrollable" tabindex="-1" aria-labelledby="exampleModalCenteredScrollableTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalCenteredScrollableTitle">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p>This is some placeholder content to show a vertically centered modal. We've added some extra copy here to show how vertically centering the modal works when combined with scrollable modals. We also use some repeated line breaks to quickly extend the height of the content, thereby triggering the scrolling. When content becomes longer than the prefedined max-height of modal, content will be cropped and scrollable within the modal.</p>
                                        <br><br><br><br><br><br><br><br><br><br>
                                        <p>Just like that.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bd-example py-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenter">
                                Vertically centered modal
                            </button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalCenteredScrollable">
                                Vertically centered scrollable modal
                            </button>
                        </div>
                        <h3 id="tooltips-and-popovers">Tooltips and popovers<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#tooltips-and-popovers" style="padding-left: 0.375em;"></a></h3>
                        <p><a href="/docs/5.0/components/tooltips/">Tooltips</a> and <a href="/docs/5.0/components/popovers/">popovers</a> can be placed within modals as needed. When modals are closed, any tooltips and popovers within are also automatically dismissed.</p>
                        <div class="modal fade" id="exampleModalPopovers" tabindex="-1" aria-labelledby="exampleModalPopoversLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalPopoversLabel">Modal title</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <h5>Popover in a modal</h5>
                                        <p>This <a href="#" role="button" class="btn btn-secondary popover-test" title="" data-bs-content="Popover body content is set in this attribute." data-bs-container="#exampleModalPopovers" data-bs-original-title="Popover title">button</a> triggers a popover on click.</p>
                                        <hr>
                                        <h5>Tooltips in a modal</h5>
                                        <p><a href="#" class="tooltip-test" title="" data-bs-container="#exampleModalPopovers" data-bs-original-title="Tooltip">This link</a> and <a href="#" class="tooltip-test" title="" data-bs-container="#exampleModalPopovers" data-bs-original-title="Tooltip">that link</a> have tooltips on hover.</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bd-example py-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalPopovers">
                                Launch demo modal
                            </button>
                        </div>
                        <h3 id="using-the-grid">Using the grid<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#using-the-grid" style="padding-left: 0.375em;"></a></h3>
                        <p>Utilize the Bootstrap grid system within a modal by nesting <code>.container-fluid</code> within the <code>.modal-body</code>. Then, use the normal grid system classes as you would anywhere else.</p>
                        <div class="modal fade" id="gridSystemModal" tabindex="-1" aria-labelledby="gridModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="gridModalLabel">Grids in modals</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="container-fluid bd-example-row">
                                            <div class="row">
                                                <div class="col-md-4">.col-md-4</div>
                                                <div class="col-md-4 ms-auto">.col-md-4 .ms-auto</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-3 ms-auto">.col-md-3 .ms-auto</div>
                                                <div class="col-md-2 ms-auto">.col-md-2 .ms-auto</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6 ms-auto">.col-md-6 .ms-auto</div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-9">
                                                    Level 1: .col-sm-9
                                                    <div class="row">
                                                        <div class="col-8 col-sm-6">
                                                            Level 2: .col-8 .col-sm-6
                                                        </div>
                                                        <div class="col-4 col-sm-6">
                                                            Level 2: .col-4 .col-sm-6
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Save changes</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bd-example py-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#gridSystemModal">
                                Launch demo modal
                            </button>
                        </div>
                        <h3 id="varying-modal-content">Varying modal content<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#varying-modal-content" style="padding-left: 0.375em;"></a></h3>
                        <p>Have a bunch of buttons that all trigger the same modal with slightly different contents? Use <code>event.relatedTarget</code> and <a href="https://developer.mozilla.org/en-US/docs/Learn/HTML/Howto/Use_data_attributes">HTML <code>data-bs-*</code> attributes</a> to vary the contents of the modal depending on which button was clicked.</p>
                        <p>Below is a live demo followed by example HTML and JavaScript. For more information, <a href="#events">read the modal events docs</a> for details on <code>relatedTarget</code>.</p>
                        <div class="bd-example py-3">
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@mdo">Open modal for @mdo</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@fat">Open modal for @fat</button>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal" data-bs-whatever="@getbootstrap">Open modal for @getbootstrap</button>

                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">New message</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <form>
                                                <div class="mb-3">
                                                    <label for="recipient-name" class="col-form-label">Recipient:</label>
                                                    <input type="text" class="form-control" id="recipient-name">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="message-text" class="col-form-label">Message:</label>
                                                    <textarea class="form-control" id="message-text"></textarea>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Send message</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <h3 id="toggle-between-modals">Toggle between modals<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#toggle-between-modals" style="padding-left: 0.375em;"></a></h3>
                        <p>Toggle between multiple modals with some clever placement of the <code>data-bs-target</code> and <code>data-bs-toggle</code> attributes. For example, you could toggle a password reset modal from within an already open sign in modal. <strong>Please note multiple modals cannot be open at the same time</strong>—this method simply toggles between two separate modals.</p>
                        <div class="bd-example py-3">
                            <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalToggleLabel">Modal 1</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Show a second modal and hide this one with the button below.
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-bs-target="#exampleModalToggle2" data-bs-toggle="modal" data-bs-dismiss="modal">Open second modal</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal fade" id="exampleModalToggle2" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalToggleLabel2">Modal 2</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            Hide this modal and show the first with the button below.
                                        </div>
                                        <div class="modal-footer">
                                            <button class="btn btn-primary" data-bs-target="#exampleModalToggle" data-bs-toggle="modal" data-bs-dismiss="modal">Back to first</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a class="btn btn-primary" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Open first modal</a>
                        </div>





                    <!--NAVS & TABS-->

                    <h1 class="my-5">NAVS & TABS</h1>

                        <div class="bd-example py-3">
                            <ul class="nav">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <p>Classes are used throughout, so your markup can be super flexible. Use <code>&lt;ul&gt;</code>s like above, <code>&lt;ol&gt;</code> if the order of your items is important, or roll your own with a <code>&lt;nav&gt;</code> element. Because the <code>.nav</code> uses <code>display: flex</code>, the nav links behave the same as nav items would, but without the extra markup.</p>
                        <div class="bd-example py-3">
                            <nav class="nav">
                                <a class="nav-link active" aria-current="page" href="#">Active</a>
                                <a class="nav-link" href="#">Link</a>
                                <a class="nav-link" href="#">Link</a>
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </nav>
                        </div>
                        <h2 id="available-styles">Available styles<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#available-styles" style="padding-left: 0.375em;"></a></h2>
                        <p>Change the style of <code>.nav</code>s component with modifiers and utilities. Mix and match as needed, or build your own.</p>
                        <h3 id="horizontal-alignment">Horizontal alignment<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#horizontal-alignment" style="padding-left: 0.375em;"></a></h3>
                        <p>Change the horizontal alignment of your nav with <a href="/docs/5.0/layout/grid/#horizontal-alignment">flexbox utilities</a>. By default, navs are left-aligned, but you can easily change them to center or right aligned.</p>
                        <p>Centered with <code>.justify-content-center</code>:</p>
                        <div class="bd-example py-3">
                            <ul class="nav justify-content-center">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <p>Right-aligned with <code>.justify-content-end</code>:</p>
                        <div class="bd-example py-3">
                            <ul class="nav justify-content-end">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <h3 id="vertical">Vertical<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#vertical" style="padding-left: 0.375em;"></a></h3>
                        <p>Stack your navigation by changing the flex item direction with the <code>.flex-column</code> utility. Need to stack them on some viewports but not others? Use the responsive versions (e.g., <code>.flex-sm-column</code>).</p>
                        <div class="bd-example py-3">
                            <ul class="nav flex-column">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <p>As always, vertical navigation is possible without <code>&lt;ul&gt;</code>s, too.</p>
                        <div class="bd-example py-3">
                            <nav class="nav flex-column">
                                <a class="nav-link active" aria-current="page" href="#">Active</a>
                                <a class="nav-link" href="#">Link</a>
                                <a class="nav-link" href="#">Link</a>
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </nav>
                        </div>
                        <h3 id="tabs">Tabs<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#tabs" style="padding-left: 0.375em;"></a></h3>
                        <p>Takes the basic nav from above and adds the <code>.nav-tabs</code> class to generate a tabbed interface. Use them to create tabbable regions with our <a href="#javascript-behavior">tab JavaScript plugin</a>.</p>
                        <div class="bd-example py-3">
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <h3 id="pills">Pills<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#pills" style="padding-left: 0.375em;"></a></h3>
                        <p>Take that same HTML, but use <code>.nav-pills</code> instead:</p>
                        <div class="bd-example py-3">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <h3 id="fill-and-justify">Fill and justify<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#fill-and-justify" style="padding-left: 0.375em;"></a></h3>
                        <p>Force your <code>.nav</code>’s contents to extend the full available width one of two modifier classes. To proportionately fill all available space with your <code>.nav-item</code>s, use <code>.nav-fill</code>. Notice that all horizontal space is occupied, but not every nav item has the same width.</p>
                        <div class="bd-example py-3">
                            <ul class="nav nav-pills nav-fill">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Much longer nav link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <p>When using a <code>&lt;nav&gt;</code>-based navigation, you can safely omit <code>.nav-item</code> as only <code>.nav-link</code> is required for styling <code>&lt;a&gt;</code> elements.</p>
                        <div class="bd-example py-3">
                            <nav class="nav nav-pills nav-fill">
                                <a class="nav-link active" aria-current="page" href="#">Active</a>
                                <a class="nav-link" href="#">Much longer nav link</a>
                                <a class="nav-link" href="#">Link</a>
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </nav>
                        </div>
                        <p>For equal-width elements, use <code>.nav-justified</code>. All horizontal space will be occupied by nav links, but unlike the <code>.nav-fill</code> above, every nav item will be the same width.</p>
                        <div class="bd-example py-3">
                            <ul class="nav nav-pills nav-justified">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="#">Active</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Much longer nav link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">Link</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                </li>
                            </ul>
                        </div>
                        <p>Similar to the <code>.nav-fill</code> example using a <code>&lt;nav&gt;</code>-based navigation.</p>
                        <div class="bd-example py-3">
                            <nav class="nav nav-pills nav-justified">
                                <a class="nav-link active" aria-current="page" href="#">Active</a>
                                <a class="nav-link" href="#">Much longer nav link</a>
                                <a class="nav-link" href="#">Link</a>
                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </nav>

                        </div>
                        <h2 id="working-with-flex-utilities">Working with flex utilities<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#working-with-flex-utilities" style="padding-left: 0.375em;"></a></h2>
                        <p>If you need responsive nav variations, consider using a series of <a href="/docs/5.0/utilities/flex/">flexbox utilities</a>. While more verbose, these utilities offer greater customization across responsive breakpoints. In the example below, our nav will be stacked on the lowest breakpoint, then adapt to a horizontal layout that fills the available width starting from the small breakpoint.</p>
                        <div class="bd-example py-3">
                            <nav class="nav nav-pills flex-column flex-sm-row">
                                <a class="flex-sm-fill text-sm-center nav-link active" aria-current="page" href="#">Active</a>
                                <a class="flex-sm-fill text-sm-center nav-link" href="#">Longer nav link</a>
                                <a class="flex-sm-fill text-sm-center nav-link" href="#">Link</a>
                                <a class="flex-sm-fill text-sm-center nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                            </nav>
                        </div>



                    <!--NAVBAR-->

                    <h1 class="my-5">NAVBAR</h1>


                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarSupportedContent">
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Link</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Dropdown
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                                </ul>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                            </li>
                                        </ul>
                                        <form class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-outline-success" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <p>This example uses <a href="/docs/5.0/utilities/background/">background</a> (<code>bg-azure-lt</code>) and <a href="/docs/5.0/utilities/spacing/">spacing</a> (<code>my-2</code>, <code>my-lg-0</code>, <code>me-sm-0</code>, <code>my-sm-0</code>) utility classes.</p>
                        <h3 id="brand">Brand<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#brand" style="padding-left: 0.375em;"></a></h3>
                        <p>The <code>.navbar-brand</code> can be applied to most elements, but an anchor works best, as some elements might require utility classes or custom styles.</p>
                        <h4 id="text">Text<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#text" style="padding-left: 0.375em;"></a></h4>
                        <p>Add your text within an element with the <code>.navbar-brand</code> class.</p>
                        <div class="bd-example py-3">
                            <!-- As a link -->
                            <nav class="navbar navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                </div>
                            </nav>

                            <!-- As a heading -->
                            <nav class="navbar navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <span class="navbar-brand mb-0 h1">Navbar</span>
                                </div>
                            </nav>
                        </div>
                        <h4 id="image">Image<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#image" style="padding-left: 0.375em;"></a></h4>
                        <p>You can replace the text within the <code>.navbar-brand</code> with an <code>&lt;img&gt;</code>.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-light bg-azure-lt">
                                <div class="container">
                                    <a class="navbar-brand" href="#">
                                        <img src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24">
                                    </a>
                                </div>
                            </nav>
                        </div>
                        <h4 id="image-and-text">Image and text<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#image-and-text" style="padding-left: 0.375em;"></a></h4>
                        <p>You can also make use of some additional utilities to add an image and text at the same time. Note the addition of <code>.d-inline-block</code> and <code>.align-text-top</code> on the <code>&lt;img&gt;</code>.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">
                                        <img src="/docs/5.0/assets/brand/bootstrap-logo.svg" alt="" width="30" height="24" class="d-inline-block align-text-top">
                                        Bootstrap
                                    </a>
                                </div>
                            </nav>
                        </div>
                        <h3 id="nav">Nav<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#nav" style="padding-left: 0.375em;"></a></h3>
                        <p>Navbar navigation links build on our <code>.nav</code> options with their own modifier class and require the use of <a href="#toggler">toggler classes</a> for proper responsive styling. <strong>Navigation in navbars will also grow to occupy as much horizontal space as possible</strong> to keep your navbar contents securely aligned.</p>
                        <p>Add the <code>.active</code> class on <code>.nav-link</code> to indicate the current page.</p>
                        <p>Please note that you should also add the <code>aria-current</code> attribute on the active <code>.nav-link</code>.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarNav">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <p>And because we use classes for our navs, you can avoid the list-based approach entirely if you like.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                                        <div class="navbar-nav">
                                            <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            <a class="nav-link" href="#">Features</a>
                                            <a class="nav-link" href="#">Pricing</a>
                                            <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                        </div>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <p>You can also use dropdowns in your navbar. Dropdown menus require a wrapping element for positioning, so be sure to use separate and nested elements for <code>.nav-item</code> and <code>.nav-link</code> as shown below.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarNavDropdown">
                                        <ul class="navbar-nav">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Dropdown link
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <h3 id="forms">Forms<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#forms" style="padding-left: 0.375em;"></a></h3>
                        <p>Place various form controls and components within a navbar:</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <form class="d-flex">
                                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                        <button class="btn btn-outline-success" type="submit">Search</button>
                                    </form>
                                </div>
                            </nav>
                        </div>
                        <p>Immediate child elements of <code>.navbar</code> use flex layout and will default to <code>justify-content: space-between</code>. Use additional <a href="/docs/5.0/utilities/flex/">flex utilities</a> as needed to adjust this behavior.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand">Navbar</a>
                                    <form class="d-flex">
                                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                        <button class="btn btn-outline-success" type="submit">Search</button>
                                    </form>
                                </div>
                            </nav>
                        </div>
                        <p>Input groups work, too. If your navbar is an entire form, or mostly a form, you can use the <code>&lt;form&gt;</code> element as the container and save some HTML.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-light bg-azure-lt">
                                <form class="container-fluid">
                                    <div class="input-group">
                                        <span class="input-group-text" id="basic-addon1">@</span>
                                        <input type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                                    </div>
                                </form>
                            </nav>
                        </div>
                        <p>Various buttons are supported as part of these navbar forms, too. This is also a great reminder that vertical alignment utilities can be used to align different sized elements.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-light bg-azure-lt">
                                <form class="container-fluid justify-content-start">
                                    <button class="btn btn-outline-success me-2" type="button">Main button</button>
                                    <button class="btn btn-sm btn-outline-secondary" type="button">Smaller button</button>
                                </form>
                            </nav>
                        </div>
                        <h3 id="text-1">Text<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#text-1" style="padding-left: 0.375em;"></a></h3>
                        <p>Navbars may contain bits of text with the help of <code>.navbar-text</code>. This class adjusts vertical alignment and horizontal spacing for strings of text.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <span class="navbar-text">
                                      Navbar text with an inline element
                                    </span>
                                </div>
                            </nav>
                        </div>
                        <p>Mix and match with other components and utilities as needed.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar w/ text</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarText">
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                        </ul>
                                        <span class="navbar-text">
                                            Navbar text with an inline element
                                          </span>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <h2 id="color-schemes">Color schemes<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#color-schemes" style="padding-left: 0.375em;"></a></h2>
                        <p>Theming the navbar has never been easier thanks to the combination of theming classes and <code>background-color</code> utilities. Choose from <code>.navbar-light</code> for use with light background colors, or <code>.navbar-dark</code> for dark background colors. Then, customize with <code>.bg-*</code> utilities.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarColor01">
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">About</a>
                                            </li>
                                        </ul>
                                        <form class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-outline-light" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </nav>
                            <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor02" aria-controls="navbarColor02" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarColor02">
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">About</a>
                                            </li>
                                        </ul>
                                        <form class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-outline-light" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </nav>
                            <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarColor03" aria-controls="navbarColor03" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarColor03">
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Features</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Pricing</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">About</a>
                                            </li>
                                        </ul>
                                        <form class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-outline-primary" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <h2 id="containers">Containers<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#containers" style="padding-left: 0.375em;"></a></h2>
                        <p>Although it’s not required, you can wrap a navbar in a <code>.container</code> to center it on a page–though note that an inner container is still required. Or you can add a container inside the <code>.navbar</code> to only center the contents of a <a href="#placement">fixed or static top navbar</a>.</p>
                        <div class="bd-example py-3">
                            <div class="container">
                                <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                    <div class="container-fluid">
                                        <a class="navbar-brand" href="#">Navbar</a>
                                    </div>
                                </nav>
                            </div>
                        </div>
                        <p>Use any of the responsive containers to change how wide the content in your navbar is presented.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-md">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                </div>
                            </nav>

                        <h2 id="placement">Placement<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#placement" style="padding-left: 0.375em;"></a></h2>
                        <p>Use our <a href="/docs/5.0/utilities/position/">position utilities</a> to place navbars in non-static positions. Choose from fixed to the top, fixed to the bottom, or stickied to the top (scrolls with the page until it reaches the top, then stays there). Fixed navbars use <code>position: fixed</code>, meaning they’re pulled from the normal flow of the DOM and may require custom CSS (e.g., <code>padding-top</code> on the <code>&lt;body&gt;</code>) to prevent overlap with other elements.</p>
                        <p>Also note that <strong><code>.sticky-top</code> uses <code>position: sticky</code>, which <a href="https://caniuse.com/css-sticky">isn’t fully supported in every browser</a></strong>.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Default</a>
                                </div>
                            </nav>
                        </div>
                        <div class="bd-example py-3">
                            <nav class="navbar fixed-top navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Fixed top</a>
                                </div>
                            </nav>
                        </div>
                        <div class="bd-example py-3">
                            <nav class="navbar fixed-bottom navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Fixed bottom</a>
                                </div>
                            </nav>
                        </div>
                        <div class="bd-example py-3">
                            <nav class="navbar sticky-top navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Sticky top</a>
                                </div>
                            </nav>
                        </div>
                        <h2 id="scrolling">Scrolling<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#scrolling" style="padding-left: 0.375em;"></a></h2>
                        <p>Add <code>.navbar-nav-scroll</code> to a <code>.navbar-nav</code> (or other navbar sub-component) to enable vertical scrolling within the toggleable contents of a collapsed navbar. By default, scrolling kicks in at <code>75vh</code> (or 75% of the viewport height), but you can override that with the local CSS custom property <code>--bs-navbar-height</code> or custom styles. At larger viewports when the navbar is expanded, content will appear as it does in a default navbar.</p>
                        <p>Please note that this behavior comes with a potential drawback of <code>overflow</code>—when setting <code>overflow-y: auto</code> (required to scroll the content here), <code>overflow-x</code> is the equivalent of <code>auto</code>, which will crop some horizontal content.</p>
                        <p>Here’s an example navbar using <code>.navbar-nav-scroll</code> with <code>style="--bs-scroll-height: 100px;"</code>, with some extra margin utilities for optimum spacing.</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar scroll</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarScroll">
                                        <ul class="navbar-nav me-auto my-2 my-lg-0 navbar-nav-scroll" style="--bs-scroll-height: 100px;">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Link</a>
                                            </li>
                                            <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="#" id="navbarScrollingDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                    Link
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="navbarScrollingDropdown">
                                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                                </ul>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Link</a>
                                            </li>
                                        </ul>
                                        <form class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-outline-success" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <h2 id="responsive-behaviors">Responsive behaviors<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#responsive-behaviors" style="padding-left: 0.375em;"></a></h2>
                        <p>Navbars can use <code>.navbar-toggler</code>, <code>.navbar-collapse</code>, and <code>.navbar-expand{-sm|-md|-lg|-xl|-xxl}</code> classes to determine when their content collapses behind a button. In combination with other utilities, you can easily choose when to show or hide particular elements.</p>
                        <p>For navbars that never collapse, add the <code>.navbar-expand</code> class on the navbar. For navbars that always collapse, don’t add any <code>.navbar-expand</code> class.</p>
                        <h3 id="toggler">Toggler<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#toggler" style="padding-left: 0.375em;"></a></h3>
                        <p>Navbar togglers are left-aligned by default, but should they follow a sibling element like a <code>.navbar-brand</code>, they’ll automatically be aligned to the far right. Reversing your markup will reverse the placement of the toggler. Below are examples of different toggle styles.</p>
                        <p>With no <code>.navbar-brand</code> shown at the smallest breakpoint:</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                                        <a class="navbar-brand" href="#">Hidden brand</a>
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Link</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                            </li>
                                        </ul>
                                        <form class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-outline-success" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <p>With a brand name shown on the left and toggler on the right:</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Link</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                            </li>
                                        </ul>
                                        <form class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-outline-success" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <p>With a toggler on the left and brand name on the right:</p>
                        <div class="bd-example py-3">
                            <nav class="navbar navbar-expand-lg navbar-light bg-azure-lt">
                                <div class="container-fluid">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                    <a class="navbar-brand" href="#">Navbar</a>
                                    <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                                        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                                            <li class="nav-item">
                                                <a class="nav-link active" aria-current="page" href="#">Home</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link" href="#">Link</a>
                                            </li>
                                            <li class="nav-item">
                                                <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                                            </li>
                                        </ul>
                                        <form class="d-flex">
                                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                                            <button class="btn btn-outline-success" type="submit">Search</button>
                                        </form>
                                    </div>
                                </div>
                            </nav>
                        </div>
                        <h3 id="external-content">External content<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#external-content" style="padding-left: 0.375em;"></a></h3>
                        <p>Sometimes you want to use the collapse plugin to trigger a container element for content that structurally sits outside of the <code>.navbar</code> . Because our plugin works on the <code>id</code> and <code>data-bs-target</code> matching, that’s easily done!</p>
                        <div class="bd-example py-3">
                            <div class="collapse" id="navbarToggleExternalContent">
                                <div class="bg-dark p-4">
                                    <h5 class="text-white h4">Collapsed content</h5>
                                    <span class="text-muted">Toggleable via the navbar brand.</span>
                                </div>
                            </div>
                            <nav class="navbar navbar-dark bg-dark">
                                <div class="container-fluid">
                                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggleExternalContent" aria-controls="navbarToggleExternalContent" aria-expanded="false" aria-label="Toggle navigation">
                                        <span class="navbar-toggler-icon"></span>
                                    </button>
                                </div>
                            </nav>
                        </div>



                            <!--Offcanvas -->

                            <h1 class="my-5">Offcanvas</h1>


                                <h2 id="examples">Examples<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#examples" style="padding-left: 0.375em;"></a></h2>
                                <h3 id="offcanvas-components">Offcanvas components<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#offcanvas-components" style="padding-left: 0.375em;"></a></h3>
                                <p>Below is an offcanvas example that is shown by default (via <code>.show</code> on <code>.offcanvas</code>). Offcanvas includes support for a header with a close button and an optional body class for some initial <code>padding</code>. We suggest that you include offcanvas headers with dismiss actions whenever possible, or provide an explicit dismiss action.</p>
                                <div class="bd-example bd-example-offcanvas p-0 bg-azure-lt overflow-hidden">
                                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvas" aria-labelledby="offcanvasLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" id="offcanvasLabel">Offcanvas</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            Content for the offcanvas goes here. You can place just about any Bootstrap component or custom elements here.
                                        </div>
                                    </div>
                                </div>
                                <h3 id="live-demo">Live demo<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#live-demo" style="padding-left: 0.375em;"></a></h3>
                                <p>Use the buttons below to show and hide an offcanvas element via JavaScript that toggles the <code>.show</code> class on an element with the <code>.offcanvas</code> class.</p>
                                <ul>
                                    <li><code>.offcanvas</code> hides content (default)</li>
                                    <li><code>.offcanvas.show</code> shows content</li>
                                </ul>
                                <p>You can use a link with the <code>href</code> attribute, or a button with the <code>data-bs-target</code> attribute. In both cases, the <code>data-bs-toggle="offcanvas"</code> is required.</p>
                                <div class="bd-example py-3">
                                    <a class="btn btn-primary" data-bs-toggle="offcanvas" href="#offcanvasExample" role="button" aria-controls="offcanvasExample">
                                        Link with href
                                    </a>
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                                        Button with data-bs-target
                                    </button>

                                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel" style="visibility: hidden;" aria-hidden="true">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" id="offcanvasExampleLabel">Offcanvas</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <div class="">
                                                Some text as placeholder. In real life you can have the elements you have chosen. Like, text, images, lists, etc.
                                            </div>
                                            <div class="dropdown mt-3">
                                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown">
                                                    Dropdown button
                                                </button>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <li><a class="dropdown-item" href="#">Action</a></li>
                                                    <li><a class="dropdown-item" href="#">Another action</a></li>
                                                    <li><a class="dropdown-item" href="#">Something else here</a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <h2 id="placement">Placement<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#placement" style="padding-left: 0.375em;"></a></h2>
                                <p>There’s no default placement for offcanvas components, so you must add one of the modifier classes below;</p>
                                <ul>
                                    <li><code>.offcanvas-start</code> places offcanvas on the left of the viewport (shown above)</li>
                                    <li><code>.offcanvas-end</code> places offcanvas on the right of the viewport</li>
                                    <li><code>.offcanvas-top</code> places offcanvas on the top of the viewport</li>
                                    <li><code>.offcanvas-bottom</code> places offcanvas on the bottom of the viewport</li>
                                </ul>
                                <p>Try the top, right, and bottom examples out below.</p>
                                <div class="bd-example py-3">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasTop" aria-controls="offcanvasTop">Toggle top offcanvas</button>

                                    <div class="offcanvas offcanvas-top" tabindex="-1" id="offcanvasTop" aria-labelledby="offcanvasTopLabel">
                                        <div class="offcanvas-header">
                                            <h5 id="offcanvasTopLabel">Offcanvas top</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            ...
                                        </div>
                                    </div>
                                </div>
                                <div class="bd-example py-3">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight" aria-controls="offcanvasRight">Toggle right offcanvas</button>

                                    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasRight" aria-labelledby="offcanvasRightLabel">
                                        <div class="offcanvas-header">
                                            <h5 id="offcanvasRightLabel">Offcanvas right</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            ...
                                        </div>
                                    </div>
                                </div>
                                <div class="bd-example py-3">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasBottom" aria-controls="offcanvasBottom">Toggle bottom offcanvas</button>

                                    <div class="offcanvas offcanvas-bottom" tabindex="-1" id="offcanvasBottom" aria-labelledby="offcanvasBottomLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" id="offcanvasBottomLabel">Offcanvas bottom</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body small">
                                            ...
                                        </div>
                                    </div>
                                </div>
                                <h2 id="backdrop">Backdrop<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#backdrop" style="padding-left: 0.375em;"></a></h2>
                                <p>Scrolling the <code>&lt;body&gt;</code> element is disabled when an offcanvas and its backdrop are visible. Use the <code>data-bs-scroll</code> attribute to toggle <code>&lt;body&gt;</code> scrolling and <code>data-bs-backdrop</code> to toggle the backdrop.</p>
                                <div class="bd-example py-3">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">Enable body scrolling</button>
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBackdrop" aria-controls="offcanvasWithBackdrop">Enable backdrop (default)</button>
                                    <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasWithBothOptions" aria-controls="offcanvasWithBothOptions">Enable both scrolling &amp; backdrop</button>

                                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" data-bs-backdrop="false" tabindex="-1" id="offcanvasScrolling" aria-labelledby="offcanvasScrollingLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" id="offcanvasScrollingLabel">Colored with scrolling</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>Try scrolling the rest of the page to see this option in action.</p>
                                        </div>
                                    </div>
                                    <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasWithBackdrop" aria-labelledby="offcanvasWithBackdropLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" id="offcanvasWithBackdropLabel">Offcanvas with backdrop</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>.....</p>
                                        </div>
                                    </div>
                                    <div class="offcanvas offcanvas-start" data-bs-scroll="true" tabindex="-1" id="offcanvasWithBothOptions" aria-labelledby="offcanvasWithBothOptionsLabel">
                                        <div class="offcanvas-header">
                                            <h5 class="offcanvas-title" id="offcanvasWithBothOptionsLabel">Backdroped with scrolling</h5>
                                            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                        </div>
                                        <div class="offcanvas-body">
                                            <p>Try scrolling the rest of the page to see this option in action.</p>
                                        </div>
                                    </div>
                                </div>


                            <!--PAGINATION -->

                            <h1 class="my-5">PAGINATION</h1>

                                <h2 id="overview">Overview<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#overview" style="padding-left: 0.375em;"></a></h2>
                                <p>We use a large block of connected links for our pagination, making links hard to miss and easily scalable—all while providing large hit areas. Pagination is built with list HTML elements so screen readers can announce the number of available links. Use a wrapping <code>&lt;nav&gt;</code> element to identify it as a navigation section to screen readers and other assistive technologies.</p>
                                <p>In addition, as pages likely have more than one such navigation section, it’s advisable to provide a descriptive <code>aria-label</code> for the <code>&lt;nav&gt;</code> to reflect its purpose. For example, if the pagination component is used to navigate between a set of search results, an appropriate label could be <code>aria-label="Search results pages"</code>.</p>
                                <div class="bd-example py-3">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item"><a class="page-link" href="#">Previous</a></li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item"><a class="page-link" href="#">Next</a></li>
                                        </ul>
                                    </nav>
                                </div
                                <h2 id="working-with-icons">Working with icons<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#working-with-icons" style="padding-left: 0.375em;"></a></h2>
                                <p>Looking to use an icon or symbol in place of text for some pagination links? Be sure to provide proper screen reader support with <code>aria</code> attributes.</p>
                                <div class="bd-example py-3">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination">
                                            <li class="page-item">
                                                <a class="page-link" href="#" aria-label="Previous">
                                                    <span aria-hidden="true">«</span>
                                                </a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#" aria-label="Next">
                                                    <span aria-hidden="true">»</span>
                                                </a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <h2 id="disabled-and-active-states">Disabled and active states<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#disabled-and-active-states" style="padding-left: 0.375em;"></a></h2>
                                <p>Pagination links are customizable for different circumstances. Use <code>.disabled</code> for links that appear un-clickable and <code>.active</code> to indicate the current page.</p>
                                <p>While the <code>.disabled</code> class uses <code>pointer-events: none</code> to <em>try</em> to disable the link functionality of <code>&lt;a&gt;</code>s, that CSS property is not yet standardized and doesn’t account for keyboard navigation. As such, you should always add <code>tabindex="-1"</code> on disabled links and use custom JavaScript to fully disable their functionality.</p>
                                <div class="bd-example py-3">
                                    <nav aria-label="...">
                                        <ul class="pagination">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item active" aria-current="page">
                                                <a class="page-link" href="#">2</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <p>You can optionally swap out active or disabled anchors for <code>&lt;span&gt;</code>, or omit the anchor in the case of the prev/next arrows, to remove click functionality and prevent keyboard focus while retaining intended styles.</p>
                                <div class="bd-example py-3">
                                    <nav aria-label="...">
                                        <ul class="pagination">
                                            <li class="page-item disabled">
                                                <span class="page-link">Previous</span>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">2</span>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <h2 id="sizing">Sizing<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#sizing" style="padding-left: 0.375em;"></a></h2>
                                <p>Fancy larger or smaller pagination? Add <code>.pagination-lg</code> or <code>.pagination-sm</code> for additional sizes.</p>
                                <div class="bd-example py-3">
                                    <nav aria-label="...">
                                        <ul class="pagination pagination-lg">
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">1</span>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="bd-example py-3">
                                    <nav aria-label="...">
                                        <ul class="pagination pagination-sm">
                                            <li class="page-item active" aria-current="page">
                                                <span class="page-link">1</span>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                        </ul>
                                    </nav>
                                </div>
                                <h2 id="alignment">Alignment<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#alignment" style="padding-left: 0.375em;"></a></h2>
                                <p>Change the alignment of pagination components with <a href="/docs/5.0/utilities/flex/">flexbox utilities</a>.</p>
                                <div class="bd-example py-3">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-center">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>
                                <div class="bd-example py-3">
                                    <nav aria-label="Page navigation example">
                                        <ul class="pagination justify-content-end">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="#" tabindex="-1" aria-disabled="true">Previous</a>
                                            </li>
                                            <li class="page-item"><a class="page-link" href="#">1</a></li>
                                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                                            <li class="page-item">
                                                <a class="page-link" href="#">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div>


                            <!--POPOVERS -->

                            <h1 class="my-5">POPOVERS</h1>


                                    <!--   INICIALIZE EVERYWHERE !!!! --->
                                    <!---->
                                    <!--  <script>-->
                                    <!--    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))-->
                                    <!-- var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {-->
                                    <!--   return new bootstrap.Popover(popoverTriggerEl)-->
                                    <!--  })-->
                                    <!--   </script>-->

                                    <!-- INICIALIZE IN COINTAIER ONLY-->
                                    <!---->
                                    <!--  var popover = new bootstrap.Popover(document.querySelector('.example-popover'), {-->
                                    <!--  container: 'body'-->
                                    <!--  })-->



                               <h2 id="example">Example<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#example" style="padding-left: 0.375em;"></a></h2>
                                <div class="bd-example py-3">
                                    <button type="button" class="btn btn-lg btn-danger" data-bs-toggle="popover" title="" data-bs-content="And here's some amazing content. It's very engaging. Right?" data-bs-original-title="Popover title" aria-describedby="popover569503">Click to toggle popover</button>
                                </div>
                                <h3 id="four-directions">Four directions<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#four-directions" style="padding-left: 0.375em;"></a></h3>
                                <p>Four options are available: top, right, bottom, and left aligned. Directions are mirrored when using Bootstrap in RTL.</p>
                                <div class="bd-example py-3">
                                    <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="top" data-bs-content="Top popover" data-bs-original-title="" title="">
                                        Popover on top
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="right" data-bs-content="Right popover" data-bs-original-title="" title="">
                                        Popover on right
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="bottom" data-bs-content="Bottom popover" data-bs-original-title="" title="">
                                        Popover on bottom
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-container="body" data-bs-toggle="popover" data-bs-placement="left" data-bs-content="Left popover" data-bs-original-title="" title="">
                                        Popover on left
                                    </button>
                                </div>
                                <h3 id="dismiss-on-next-click">Dismiss on next click<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#dismiss-on-next-click" style="padding-left: 0.375em;"></a></h3>
                                <p>Use the <code>focus</code> trigger to dismiss popovers on the user’s next click of a different element than the toggle element.</p>
                                <div class="bd-callout bd-callout-danger">
                                    <h4 id="specific-markup-required-for-dismiss-on-next-click">Specific markup required for dismiss-on-next-click</h4>
                                    <p>For proper cross-browser and cross-platform behavior, you must use the <code>&lt;a&gt;</code> tag, <em>not</em> the <code>&lt;button&gt;</code> tag, and you also must include a <a href="https://developer.mozilla.org/en-US/docs/Web/HTML/Global_attributes/tabindex"><code>tabindex</code></a> attribute.
                                    </p></div>

                                <div class="bd-example py-3">
                                    <a tabindex="0" class="btn btn-lg btn-danger" role="button" data-bs-toggle="popover" data-bs-trigger="focus" title="" data-bs-content="And here's some amazing content. It's very engaging. Right?" data-bs-original-title="Dismissible popover">Dismissible popover</a>
                                </div>
                                <h3 id="disabled-elements">Disabled elements<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#disabled-elements" style="padding-left: 0.375em;"></a></h3>
                                <p>Elements with the <code>disabled</code> attribute aren’t interactive, meaning users cannot hover or click them to trigger a popover (or tooltip). As a workaround, you’ll want to trigger the popover from a wrapper <code>&lt;div&gt;</code> or <code>&lt;span&gt;</code>, ideally made keyboard-focusable using <code>tabindex="0"</code>.</p>
                                <p>For disabled popover triggers, you may also prefer <code>data-bs-trigger="hover focus"</code> so that the popover appears as immediate visual feedback to your users as they may not expect to <em>click</em> on a disabled element.</p>
                                <div class="bd-example py-3">
                                    <span class="d-inline-block" tabindex="0" data-bs-toggle="popover" data-bs-trigger="hover focus" data-bs-content="Disabled popover" data-bs-original-title="" title="">
                                      <button class="btn btn-primary" type="button" disabled="">Disabled button</button>
                                    </span>
                                </div>


                            <!--PROGRESS BARS -->

                            <h1 class="my-5">PROGRESS BARS</h1>





                                <div class="bd-example py-3">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <p>Bootstrap provides a handful of <a href="/docs/5.0/utilities/sizing/">utilities for setting width</a>. Depending on your needs, these may help with quickly configuring progress.</p>
                                <div class="bd-example py-3">
                                    <div class="progress">
                                        <div class="progress-bar w-75" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <h2 id="labels">Labels<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#labels" style="padding-left: 0.375em;"></a></h2>
                                <p>Add labels to your progress bars by placing text within the <code>.progress-bar</code>.</p>
                                <div class="bd-example py-3">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">25%</div>
                                    </div>
                                </div>
                                <h2 id="height">Height<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#height" style="padding-left: 0.375em;"></a></h2>
                                <p>We only set a <code>height</code> value on the <code>.progress</code>, so if you change that value the inner <code>.progress-bar</code> will automatically resize accordingly.</p>
                                <div class="bd-example py-3">
                                    <div class="progress" style="height: 1px;">
                                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress" style="height: 20px;">
                                        <div class="progress-bar" role="progressbar" style="width: 25%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <h2 id="backgrounds">Backgrounds<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#backgrounds" style="padding-left: 0.375em;"></a></h2>
                                <p>Use background utility classes to change the appearance of individual progress bars.</p>
                                <div class="bd-example py-3">
                                    <div class="progress">
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <h2 id="multiple-bars">Multiple bars<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#multiple-bars" style="padding-left: 0.375em;"></a></h2>
                                <p>Include multiple progress bars in a progress component if you need.</p>
                                <div class="bd-example py-3">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 15%" aria-valuenow="15" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-success" role="progressbar" style="width: 30%" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                                        <div class="progress-bar bg-info" role="progressbar" style="width: 20%" aria-valuenow="20" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <h2 id="striped">Striped<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#striped" style="padding-left: 0.375em;"></a></h2>
                                <p>Add <code>.progress-bar-striped</code> to any <code>.progress-bar</code> to apply a stripe via CSS gradient over the progress bar’s background color.</p>
                                <div class="bd-example py-3">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 25%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-info" role="progressbar" style="width: 50%" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-warning" role="progressbar" style="width: 75%" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped bg-danger" role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                </div>
                                <h2 id="animated-stripes">Animated stripes<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#animated-stripes" style="padding-left: 0.375em;"></a></h2>
                                <p>The striped gradient can also be animated. Add <code>.progress-bar-animated</code> to <code>.progress-bar</code> to animate the stripes right to left via CSS3 animations.</p>
                                <div class="bd-example py-3">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="75" aria-valuemin="0" aria-valuemax="100" style="width: 75%"></div>
                                    </div>
                                    <button type="button" class="btn btn-secondary mt-3" data-bs-toggle="button" id="btnToggleAnimatedProgress" aria-pressed="false" autocomplete="off">
                                        Toggle animation
                                    </button>
                                </div>


                            <!--Scollspy -->

                            <h1 class="my-5">Scollspy</h1>





                                   <h2 class="py-3" id="example-in-navbar">Example in navbar<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#example-in-navbar" style="padding-left: 0.375em;"></a></h2>
                                   <p>Scroll the area below the navbar and watch the active class change. The dropdown items will be highlighted as well.</p>
                                   <div class="bd-example py-3">
                                       <nav id="navbar-example2" class="navbar navbar-light bg-azure-lt px-3">
                                           <a class="navbar-brand" href="#">Navbar</a>
                                           <ul class="nav nav-pills">
                                               <li class="nav-item">
                                                   <a class="nav-link" href="#scrollspyHeading1">First</a>
                                               </li>
                                               <li class="nav-item">
                                                   <a class="nav-link" href="#scrollspyHeading2">Second</a>
                                               </li>
                                               <li class="nav-item dropdown">
                                                   <a class="nav-link dropdown-toggle active" data-bs-toggle="dropdown" href="#" role="button" aria-expanded="false">Dropdown</a>
                                                   <ul class="dropdown-menu">
                                                       <li><a class="dropdown-item" href="#scrollspyHeading3">Third</a></li>
                                                       <li><a class="dropdown-item" href="#scrollspyHeading4">Fourth</a></li>
                                                       <li><hr class="dropdown-divider"></li>
                                                       <li><a class="dropdown-item active" href="#scrollspyHeading5">Fifth</a></li>
                                                   </ul>
                                               </li>
                                           </ul>
                                       </nav>
                                       <div data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-offset="0" class="scrollspy-example" tabindex="0">
                                           <h4 id="scrollspyHeading1">First heading</h4>
                                           <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                           <h4 id="scrollspyHeading2">Second heading</h4>
                                           <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                           <h4 id="scrollspyHeading3">Third heading</h4>
                                           <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                           <h4 id="scrollspyHeading4">Fourth heading</h4>
                                           <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                           <h4 id="scrollspyHeading5">Fifth heading</h4>
                                           <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                       </div>
                                   </div>
                                   <h2 class="py-3" id="example-with-nested-nav">Example with nested nav<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#example-with-nested-nav" style="padding-left: 0.375em;"></a></h2>
                                   <p>Scrollspy also works with nested <code>.nav</code>s. If a nested <code>.nav</code> is <code>.active</code>, its parents will also be <code>.active</code>. Scroll the area next to the navbar and watch the active class change.</p>
                                   <div class="bd-example py-3">
                                       <div class="row">
                                           <div class="col-4">
                                               <nav id="navbar-example3" class="navbar navbar-light bg-azure-lt flex-column align-items-stretch p-3">
                                                   <a class="navbar-brand" href="#">Navbar</a>
                                                   <nav class="nav nav-pills flex-column">
                                                       <a class="nav-link active" href="#item-1">Item 1</a>
                                                       <nav class="nav nav-pills flex-column">
                                                           <a class="nav-link ms-3 my-1" href="#item-1-1">Item 1-1</a>
                                                           <a class="nav-link ms-3 my-1" href="#item-1-2">Item 1-2</a>
                                                       </nav>
                                                       <a class="nav-link" href="#item-2">Item 2</a>
                                                       <a class="nav-link" href="#item-3">Item 3</a>
                                                       <nav class="nav nav-pills flex-column">
                                                           <a class="nav-link ms-3 my-1" href="#item-3-1">Item 3-1</a>
                                                           <a class="nav-link ms-3 my-1" href="#item-3-2">Item 3-2</a>
                                                       </nav>
                                                   </nav>
                                               </nav>
                                           </div>
                                           <div class="col-8">
                                               <div data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-offset="0" class="scrollspy-example-2" tabindex="0">
                                                   <h4 id="item-1">Item 1</h4>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h5 id="item-1-1">Item 1-1</h5>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h5 id="item-1-2">Item 1-2</h5>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h4 id="item-2">Item 2</h4>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h4 id="item-3">Item 3</h4>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h5 id="item-3-1">Item 3-1</h5>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h5 id="item-3-2">Item 3-2</h5>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                               </div>
                                           </div>
                                       </div>
                                   </div>
                                   <h2 class="py-3" id="example-with-list-group">Example with list-group<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#example-with-list-group" style="padding-left: 0.375em;"></a></h2>
                                   <p>Scrollspy also works with <code>.list-group</code>s. Scroll the area next to the list group and watch the active class change.</p>
                                   <div class="bd-example py-3">
                                       <div class="row">
                                           <div class="col-4">
                                               <div id="list-example" class="list-group">
                                                   <a class="list-group-item list-group-item-action active" href="#list-item-1">Item 1</a>
                                                   <a class="list-group-item list-group-item-action" href="#list-item-2">Item 2</a>
                                                   <a class="list-group-item list-group-item-action" href="#list-item-3">Item 3</a>
                                                   <a class="list-group-item list-group-item-action" href="#list-item-4">Item 4</a>
                                               </div>
                                           </div>
                                           <div class="col-8">
                                               <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" class="scrollspy-example" tabindex="0">
                                                   <h4 id="list-item-1">Item 1</h4>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h4 id="list-item-2">Item 2</h4>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h4 id="list-item-3">Item 3</h4>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                                   <h4 id="list-item-4">Item 4</h4>
                                                   <p>This is some placeholder content for the scrollspy page. Note that as you scroll down the page, the appropriate navigation link is highlighted. It's repeated throughout the component example. We keep adding some more example copy here to emphasize the scrolling and highlighting.</p>
                                               </div>
                                           </div>
                                       </div>
                                   </div>

                            <!--Spinners -->

                            <h1 class="my-5">Spinners</h1>

                            <div class="bd-content ps-lg-4">

                                <h2 id="border-spinner">Border spinner<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#border-spinner" style="padding-left: 0.375em;"></a></h2>
                                <p>Use the border spinners for a lightweight loading indicator.</p>
                                <div class="bd-example py-3">
                                    <div class="spinner-border" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <h3 id="colors">Colors<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#colors" style="padding-left: 0.375em;"></a></h3>
                                <p>The border spinner uses <code>currentColor</code> for its <code>border-color</code>, meaning you can customize the color with <a href="/docs/5.0/utilities/colors/">text color utilities</a>. You can use any of our text color utilities on the standard spinner.</p>
                                <div class="bd-example py-3">

                                    <div class="spinner-border text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-border text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-border text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-border text-danger" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-border text-warning" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-border text-info" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-border text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-border  " role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <div class="bd-callout bd-callout-info">
                                    <strong>Why not use <code>border-color</code> utilities?</strong> Each border spinner specifies a <code>transparent</code> border for at least one side, so <code>.border-{color}</code> utilities would override that.
                                </div>

                                <h2 id="growing-spinner">Growing spinner<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#growing-spinner" style="padding-left: 0.375em;"></a></h2>
                                <p>If you don’t fancy a border spinner, switch to the grow spinner. While it doesn’t technically spin, it does repeatedly grow!</p>
                                <div class="bd-example py-3">
                                    <div class="spinner-grow" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <p>Once again, this spinner is built with <code>currentColor</code>, so you can easily change its appearance with <a href="/docs/5.0/utilities/colors/">text color utilities</a>. Here it is in blue, along with the supported variants.</p>
                                <div class="bd-example py-3">

                                    <div class="spinner-grow text-primary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-secondary" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-success" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-danger" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-warning" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-info" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow text-light" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow  " role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <h2 id="alignment">Alignment<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#alignment" style="padding-left: 0.375em;"></a></h2>
                                <p>Spinners in Bootstrap are built with <code>rem</code>s, <code>currentColor</code>, and <code>display: inline-flex</code>. This means they can easily be resized, recolored, and quickly aligned.</p>
                                <h3 id="margin">Margin<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#margin" style="padding-left: 0.375em;"></a></h3>
                                <p>Use <a href="/docs/5.0/utilities/spacing/">margin utilities</a> like <code>.m-5</code> for easy spacing.</p>
                                <div class="bd-example py-3">
                                    <div class="spinner-border m-5" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <h3 id="placement">Placement<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#placement" style="padding-left: 0.375em;"></a></h3>
                                <p>Use <a href="/docs/5.0/utilities/flex/">flexbox utilities</a>, <a href="/docs/5.0/utilities/float/">float utilities</a>, or <a href="/docs/5.0/utilities/text/">text alignment</a> utilities to place spinners exactly where you need them in any situation.</p>
                                <h4 id="flex">Flex<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#flex" style="padding-left: 0.375em;"></a></h4>
                                <div class="bd-example py-3">
                                    <div class="d-flex justify-content-center">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="bd-example py-3">
                                    <div class="d-flex align-items-center">
                                        <strong>Loading...</strong>
                                        <div class="spinner-border ms-auto" role="status" aria-hidden="true"></div>
                                    </div>
                                </div>
                                <h4 id="floats">Floats<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#floats" style="padding-left: 0.375em;"></a></h4>
                                <div class="bd-example py-3">
                                    <div class="clearfix">
                                        <div class="spinner-border float-end" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <h4 id="text-align">Text align<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#text-align" style="padding-left: 0.375em;"></a></h4>
                                <div class="bd-example py-3">
                                    <div class="text-center">
                                        <div class="spinner-border" role="status">
                                            <span class="visually-hidden">Loading...</span>
                                        </div>
                                    </div>
                                </div>
                                <h2 id="size">Size<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#size" style="padding-left: 0.375em;"></a></h2>
                                <p>Add <code>.spinner-border-sm</code> and <code>.spinner-grow-sm</code> to make a smaller spinner that can quickly be used within other components.</p>
                                <div class="bd-example py-3">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow spinner-grow-sm" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <p>Or, use custom CSS or inline styles to change the dimensions as needed.</p>
                                <div class="bd-example py-3">
                                    <div class="spinner-border" style="width: 3rem; height: 3rem;" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                    <div class="spinner-grow" style="width: 3rem; height: 3rem;" role="status">
                                        <span class="visually-hidden">Loading...</span>
                                    </div>
                                </div>
                                <h2 id="buttons">Buttons<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#buttons" style="padding-left: 0.375em;"></a></h2>
                                <p>Use spinners within buttons to indicate an action is currently processing or taking place. You may also swap the text out of the spinner element and utilize button text as needed.</p>
                                <div class="bd-example py-3">
                                    <button class="btn btn-primary" type="button" disabled="">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        <span class="visually-hidden">Loading...</span>
                                    </button>
                                    <button class="btn btn-primary" type="button" disabled="">
                                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>
                                <div class="bd-example py-3">
                                    <button class="btn btn-primary" type="button" disabled="">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                        <span class="visually-hidden">Loading...</span>
                                    </button>
                                    <button class="btn btn-primary" type="button" disabled="">
                                        <span class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
                                        Loading...
                                    </button>
                                </div>


                                <!--TOASTS -->

                                <h1 class="my-5">TOASTS</h1>

                                    <h3 id="basic">Basic<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#basic" style="padding-left: 0.375em;"></a></h3>
                                    <p>To encourage extensible and predictable toasts, we recommend a header and body. Toast headers use <code>display: flex</code>, allowing easy alignment of content thanks to our margin and flexbox utilities.</p>
                                    <p>Toasts are as flexible as you need and have very little required markup. At a minimum, we require a single element to contain your “toasted” content and strongly encourage a dismiss button.</p>
                                    <div class="bd-example bg-azure-lt py-3">
                                        <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="toast-header">
                                                 <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                <strong class="me-auto">Bootstrap</strong>
                                                <small>11 mins ago</small>
                                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                Hello, world! This is a toast message.
                                            </div>
                                        </div>
                                    </div>
                                    <h3 id="live">Live<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#live" style="padding-left: 0.375em;"></a></h3>
                                    <p>Click the button below to show a toast (positioned with our utilities in the lower right corner) that has been hidden by default with <code>.hide</code>.</p>
                                    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 11">
                                        <div id="liveToast" class="toast hide" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="toast-header">
                                                 <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                <strong class="me-auto">Bootstrap</strong>
                                                <small>11 mins ago</small>
                                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                Hello, world! This is a toast message.
                                            </div>
                                        </div>
                                    </div>
                                    <div class="bd-example">
                                        <button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>
                                    </div>
                                    <h3 id="translucent">Translucent<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#translucent" style="padding-left: 0.375em;"></a></h3>
                                    <p>Toasts are slightly translucent to blend in with what’s below them.</p>
                                    <div class="bd-example bg-dark py-3">
                                        <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="toast-header">
                                                 <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                <strong class="me-auto">Bootstrap</strong>
                                                <small class="text-muted">11 mins ago</small>
                                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                Hello, world! This is a toast message.
                                            </div>
                                        </div>
                                    </div>
                                    <h3 id="stacking">Stacking<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#stacking" style="padding-left: 0.375em;"></a></h3>
                                    <p>You can stack toasts by wrapping them in a toast container, which will vertically add some spacing.</p>
                                    <div class="bd-example bg-azure-lt py-3">
                                        <div class="toast-container">
                                            <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                                <div class="toast-header">
                                                     <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                    <strong class="me-auto">Bootstrap</strong>
                                                    <small class="text-muted">just now</small>
                                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                                </div>
                                                <div class="toast-body">
                                                    See? Just like this.
                                                </div>
                                            </div>

                                            <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                                <div class="toast-header">
                                                     <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                    <strong class="me-auto">Bootstrap</strong>
                                                    <small class="text-muted">2 seconds ago</small>
                                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                                </div>
                                                <div class="toast-body">
                                                    Heads up, toasts will stack automatically
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 id="custom-content">Custom content<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#custom-content" style="padding-left: 0.375em;"></a></h3>
                                    <p>Customize your toasts by removing sub-components, tweaking them with <a href="/docs/5.0/utilities/api/">utilities</a>, or by adding your own markup. Here we’ve created a simpler toast by removing the default <code>.toast-header</code>, adding a custom hide icon from <a href="https://icons.getbootstrap.com/">Bootstrap Icons</a>, and using some <a href="/docs/5.0/utilities/flex/">flexbox utilities</a> to adjust the layout.</p>
                                    <div class="bd-example bg-azure-lt py-3">
                                        <div class="toast align-items-center fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="d-flex">
                                                <div class="toast-body">
                                                    Hello, world! This is a toast message.
                                                </div>
                                                <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <p>Alternatively, you can also add additional controls and components to toasts.</p>
                                    <div class="bd-example bg-azure-lt py-3">
                                        <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="toast-body">
                                                Hello, world! This is a toast message.
                                                <div class="mt-2 pt-2 border-top">
                                                    <button type="button" class="btn btn-primary btn-sm">Take action</button>
                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="toast">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h3 id="color-schemes">Color schemes<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#color-schemes" style="padding-left: 0.375em;"></a></h3>
                                    <p>Building on the above example, you can create different toast color schemes with our <a href="/docs/5.0/utilities/colors/">color</a> and <a href="/docs/5.0/utilities/background/">background</a> utilities. Here we’ve added <code>.bg-primary</code> and <code>.text-white</code> to the <code>.toast</code>, and then added <code>.btn-close-white</code> to our close button. For a crisp edge, we remove the default border with <code>.border-0</code>.</p>
                                    <div class="bd-example bg-azure-lt py-3">
                                        <div class="toast align-items-center text-white bg-primary border-0 fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                            <div class="d-flex">
                                                <div class="toast-body">
                                                    Hello, world! This is a toast message.
                                                </div>
                                                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="py-3" id="placement">Placement<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#placement" style="padding-left: 0.375em;"></a></h2>
                                    <p>Place toasts with custom CSS as you need them. The top right is often used for notifications, as is the top middle. If you’re only ever going to show one toast at a time, put the positioning styles right on the <code>.toast</code>.</p>
                                    <div class="bd-example py-3">
                                        <form>
                                            <div class="mb-3">
                                                <label for="selectToastPlacement">Toast placement</label>
                                                <select class="form-select mt-2" id="selectToastPlacement">
                                                    <option value="" selected="">Select a position...</option>
                                                    <option value="top-0 start-0">Top left</option>
                                                    <option value="top-0 start-50 translate-middle-x">Top center</option>
                                                    <option value="top-0 end-0">Top right</option>
                                                    <option value="top-50 start-0 translate-middle-y">Middle left</option>
                                                    <option value="top-50 start-50 translate-middle">Middle center</option>
                                                    <option value="top-50 end-0 translate-middle-y">Middle right</option>
                                                    <option value="bottom-0 start-0">Bottom left</option>
                                                    <option value="bottom-0 start-50 translate-middle-x">Bottom center</option>
                                                    <option value="bottom-0 end-0">Bottom right</option>
                                                </select>
                                            </div>
                                        </form>
                                        <div aria-live="polite" aria-atomic="true" class="bg-dark position-relative bd-example-toasts">
                                            <div class="toast-container position-absolute p-3" id="toastPlacement">
                                                <div class="toast fade show">
                                                    <div class="toast-header">
                                                         <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                        <strong class="me-auto">Bootstrap</strong>
                                                        <small>11 mins ago</small>
                                                    </div>
                                                    <div class="toast-body">
                                                        Hello, world! This is a toast message.
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p>For systems that generate more notifications, consider using a wrapping element so they can easily stack.</p>
                                    <div class="bd-example bg-dark bd-example-toasts p-0">
                                        <div aria-live="polite" aria-atomic="true" class="position-relative">
                                            <!-- Position it: -->
                                            <!-- - `.toast-container` for spacing between toasts -->
                                            <!-- - `.position-absolute`, `top-0` & `end-0` to position the toasts in the upper right corner -->
                                            <!-- - `.p-3` to prevent the toasts from sticking to the edge of the container  -->
                                            <div class="toast-container position-absolute top-0 end-0 p-3">

                                                <!-- Then put toasts within -->
                                                <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                                    <div class="toast-header">
                                                         <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                        <strong class="me-auto">Bootstrap</strong>
                                                        <small class="text-muted">just now</small>
                                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                                    </div>
                                                    <div class="toast-body">
                                                        See? Just like this.
                                                    </div>
                                                </div>

                                                <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                                    <div class="toast-header">
                                                         <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                        <strong class="me-auto">Bootstrap</strong>
                                                        <small class="text-muted">2 seconds ago</small>
                                                        <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                                    </div>
                                                    <div class="toast-body">
                                                        Heads up, toasts will stack automatically
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <p>You can also get fancy with flexbox utilities to align toasts horizontally and/or vertically.</p>
                                    <div class="bd-example bg-dark bd-example-toasts d-flex">
                                        <!-- Flexbox container for aligning the toasts -->
                                        <div aria-live="polite" aria-atomic="true" class="d-flex justify-content-center align-items-center w-100">

                                            <!-- Then put toasts within -->
                                            <div class="toast fade show" role="alert" aria-live="assertive" aria-atomic="true">
                                                <div class="toast-header">
                                                     <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                    <strong class="me-auto">Bootstrap</strong>
                                                    <small>11 mins ago</small>
                                                    <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                                </div>
                                                <div class="toast-body">
                                                    Hello, world! This is a toast message.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h2 class="py-3" id="accessibility">Accessibility<a class="anchorjs-link " aria-label="Anchor" data-anchorjs-icon="#" href="#accessibility" style="padding-left: 0.375em;"></a></h2>
                                    <p>Toasts are intended to be small interruptions to your visitors or users, so to help those with screen readers and similar assistive technologies, you should wrap your toasts in an <a href="https://developer.mozilla.org/en-US/docs/Web/Accessibility/ARIA/ARIA_Live_Regions"><code>aria-live</code> region</a>. Changes to live regions (such as injecting/updating a toast component) are automatically announced by screen readers without needing to move the user’s focus or otherwise interrupt the user. Additionally, include <code>aria-atomic="true"</code> to ensure that the entire toast is always announced as a single (atomic) unit, rather than just announcing what was changed (which could lead to problems if you only update part of the toast’s content, or if displaying the same toast content at a later point in time). If the information needed is important for the process, e.g. for a list of errors in a form, then use the <a href="/docs/5.0/components/alerts/">alert component</a> instead of toast.</p>
                                    <p>Note that the live region needs to be present in the markup <em>before</em> the toast is generated or updated. If you dynamically generate both at the same time and inject them into the page, they will generally not be announced by assistive technologies.</p>
                                    <p>You also need to adapt the <code>role</code> and <code>aria-live</code> level depending on the content. If it’s an important message like an error, use <code>role="alert" aria-live="assertive"</code>, otherwise use <code>role="status" aria-live="polite"</code> attributes.</p>
                                    <p>As the content you’re displaying changes, be sure to update the <a href="#options"><code>delay</code> timeout</a> so that users have enough time to read the toast.</p>
                                    <p>When using <code>autohide: false</code>, you must add a close button to allow users to dismiss the toast.</p>
                                    <div class="bd-example bg-azure-lt py-3">
                                        <div role="alert" aria-live="assertive" aria-atomic="true" class="toast fade show" data-bs-autohide="false">
                                            <div class="toast-header">
                                                 <svg fill="currentColor" class="bd-placeholder-img rounded me-2" width="20" height="20" xmlns="http://www.w3.org/2000/svg" aria-hidden="true" preserveAspectRatio="xMidYMid slice" focusable="false"><rect width="100%" height="100%" fill="#007aff"></rect></svg>

                                                <strong class="me-auto">Bootstrap</strong>
                                                <small>11 mins ago</small>
                                                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
                                            </div>
                                            <div class="toast-body">
                                                Hello, world! This is a toast message.
                                            </div>
                                        </div>
                                    </div>
                                    <p>While technically it’s possible to add focusable/actionable controls (such as additional buttons or links) in your toast, you should avoid doing this for autohiding toasts. Even if you give the toast a long <a href="#options"><code>delay</code> timeout</a>, keyboard and assistive technology users may find it difficult to reach the toast in time to take action (since toasts don’t receive focus when they are displayed). If you absolutely must have further controls, we recommend using a toast with <code>autohide: false</code>.</p>



                                <!--TOOLTIPS -->

                                <h1 class="my-5">TOOLTIPS</h1>

<!--                                Tooltips rely on the 3rd party library Popper for positioning. You must include popper.min.js before bootstrap.js or use bootstrap.bundle.min.js / bootstrap.bundle.js which contains Popper in order for tooltips to work!-->
<!--                                Tooltips are opt-in for performance reasons, so you must initialize them yourself.-->
<!--                                Tooltips with zero-length titles are never displayed.-->
<!--                                Specify container: 'body' to avoid rendering problems in more complex components (like our input groups, button groups, etc).-->
<!--                                Triggering tooltips on hidden elements will not work.-->
<!--                                Tooltips for .disabled or disabled elements must be triggered on a wrapper element.-->
<!--                                When triggered from hyperlinks that span multiple lines, tooltips will be centered. Use white-space: nowrap; on your <a>s to avoid this behavior.-->
<!--                                    Tooltips must be hidden before their corresponding elements have been removed from the DOM.-->
<!--                                    Tooltips can be triggered thanks to an element inside a shadow DOM.-->

                                <ul>
                                    <li>Tooltips rely on the 3rd party library <a href="https://popper.js.org/">Popper</a> for positioning. You must include <a href="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js">popper.min.js</a> before bootstrap.js or use <code>bootstrap.bundle.min.js</code> / <code>bootstrap.bundle.js</code> which contains Popper in order for tooltips to work!</li>
                                    <li>Tooltips are opt-in for performance reasons, so <strong>you must initialize them yourself</strong>.</li>
                                    <li>Tooltips with zero-length titles are never displayed.</li>
                                    <li>Specify <code>container: 'body'</code> to avoid rendering problems in more complex components (like our input groups, button groups, etc).</li>
                                    <li>Triggering tooltips on hidden elements will not work.</li>
                                    <li>Tooltips for <code>.disabled</code> or <code>disabled</code> elements must be triggered on a wrapper element.</li>
                                    <li>When triggered from hyperlinks that span multiple lines, tooltips will be centered. Use <code>white-space: nowrap;</code> on your <code>&lt;a&gt;</code>s to avoid this behavior.</li>
                                    <li>Tooltips must be hidden before their corresponding elements have been removed from the DOM.</li>
                                    <li>Tooltips can be triggered thanks to an element inside a shadow DOM.</li>
                                </ul>

                                <div class="bd-example py-3">
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="top" title="Tooltip on top">
                                        Tooltip on top
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="right" title="Tooltip on right">
                                        Tooltip on right
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="bottom" title="Tooltip on bottom">
                                        Tooltip on bottom
                                    </button>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-placement="left" title="Tooltip on left">
                                        Tooltip on left
                                    </button>
                                </div>

                                <div class="bd-example py-3">
                                    <h3 class="py-3">With custom HTML</h3>
                                    <button type="button" class="btn btn-secondary" data-bs-toggle="tooltip" data-bs-html="true" title="<em>Tooltip</em> <u>with</u> <b>HTML</b>">
                                        Tooltip with HTML
                                    </button>
                                </div>

            </div>
    </div>
</div>



<script>

    // POPOVER
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
    })
</script>
