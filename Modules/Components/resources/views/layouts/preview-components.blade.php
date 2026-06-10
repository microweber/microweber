<!DOCTYPE html {!! lang_attributes() !!}>
<head>

    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    {!! meta_tags_head() !!}

    {{-- Vite CSS --}}

    @if(!lang_is_rtl())
        {{ template_vite('templates/bootstrap/dist', 'resources/assets/sass/app.scss') }}
    @else
        {{ template_vite('templates/bootstrap/dist', 'resources/assets/sass/app-rtl.scss') }}
    @endif

</head>

<body class="{!! helper_body_classes() !!}">

<div class="main">
    <x-container>
        <x-navbar brand="My App" brandUrl="/">
            <x-nav-item href="/" active>Home</x-nav-item>
            <x-nav-item href="/about">About</x-nav-item>
            <x-nav-item href="/contact">Contact</x-nav-item>
        </x-navbar>

        <x-hero>
            <x-slot name="image">{{asset('templates/bootstrap/img/heros/illustration-2.png')}}</x-slot>
            <x-slot name="title">
                <h2>Welcome to Microweber</h2>
            </x-slot>
            <x-slot name="content">
                <p>
                    Microweber is a drag and drop website builder and a powerful next-generation CMS. It's easy to use,
                    and it's a great tool for building websites, online shops, blogs, and more. It's based on the
                    Laravel PHP framework and the Bootstrap front-end framework.
                </p>
            </x-slot>
            <x-slot name="actions">
                <a href="#" class="btn btn-primary">Get Started</a>
                <a href="#" class="btn btn-secondary">Learn More</a>
            </x-slot>
        </x-hero>
    </x-container>

    <x-section title="Test Title" some-attr="Test" another-attr="Test2" class="custom-class">Section Content goes here</x-section>

    <x-container>
        <h2>Column Examples</h2>
        <x-row>
            <x-col size="4">
                <x-card>
                    <x-slot name="image">{{ asset('templates/bootstrap/img/bootstrap5/bootstrap-docs.png') }}</x-slot>
                    <x-slot name="title">Microweber Card</x-slot>
                    <x-slot name="content">
                        <p>
                            Some quick example text to build on the card title and make up the bulk of the card's content.
                        </p>
                    </x-slot>
                </x-card>
            </x-col>
            <x-col size="4">
                <x-card>
                    <x-slot name="title">Column 2</x-slot>
                    <x-slot name="content">Content for column 2</x-slot>
                </x-card>
            </x-col>
            <x-col size="4">
                <x-card>
                    <x-slot name="title">Column 3</x-slot>
                    <x-slot name="content">Content for column 3</x-slot>
                </x-card>
            </x-col>
        </x-row>
    </x-container>

    <x-container>
        <h2>Alert Example</h2>
        <x-alert type="success" dismissible>Your changes have been saved successfully!</x-alert>
        <x-alert type="danger" dismissible>There was an error processing your request.</x-alert>

        <h2>Button Example</h2>
        <x-button type="primary">Primary Button</x-button>
        <x-button type="secondary" outline>Secondary Button</x-button>

        <h2>Modal Example</h2>
        <x-modal id="exampleModal" title="Modal Title">
            <x-slot name="body">
                This is the modal body content.
            </x-slot>
            <x-slot name="footer">
                <x-button type="secondary" data-bs-dismiss="modal">Close</x-button>
                <x-button type="primary">Save changes</x-button>
            </x-slot>
        </x-modal>

        <h2>Select Example</h2>
        <x-select name="country" label="Select Country" :options="['USA', 'Canada', 'Mexico']"/>

        <h2>Input Example</h2>
        <x-input name="email" label="Email Address" type="email" placeholder="Enter email" required/>

        <h2>Progress Bar Example</h2>
        <x-progress-bar value="75" type="success" striped animated/>

        <h2>Tabs Example</h2>
        <x-tabs>
            <x-tab-pane title="Home" active>
                Home content
            </x-tab-pane>
            <x-tab-pane title="Profile">
                Profile content
            </x-tab-pane>
        </x-tabs>

        <h2>Pagination Example</h2>
        @php
        $posts = new \Illuminate\Pagination\LengthAwarePaginator(range(1, 100), 100, 10);
        @endphp
        <x-pagination :items="$posts"/>
    </x-container>

    <x-container>
        <h2>Pricing Table Example</h2>
        <x-pricing-table :columns="3" class="mb-5">
            <x-pricing-row
                plan-name="Starter"
                price="$9"
                period="/mo"
                :features="['5 users', '10 GB storage', 'Email support']"
                button-text="Get Started"
                button-style="btn btn-outline-primary"
            />
            <x-pricing-row
                plan-name="Pro"
                price="$29"
                period="/mo"
                :features="['25 users', '50 GB storage', 'Priority support', 'API access']"
                :highlighted="true"
                button-text="Choose Pro"
            />
            <x-pricing-row
                plan-name="Enterprise"
                price="$99"
                period="/mo"
                :features="['Unlimited users', '500 GB storage', '24/7 phone support', 'Dedicated manager']"
                button-text="Contact Sales"
                button-style="btn btn-outline-primary"
            />
        </x-pricing-table>
    </x-container>

    <x-container>
        <h2>Testimonial Cards Example</h2>
        <x-row class="g-4 mb-5">
            <x-col size="4">
                <x-testimonial-card
                    name="Jane Doe"
                    content="Absolutely fantastic product. It changed how we work."
                    company="Acme Corp"
                    role="VP of Engineering"
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="4">
                <x-testimonial-card
                    name="John Smith"
                    content="Simple, powerful, and elegant. Highly recommended."
                    company="StartupXYZ"
                    role="Founder"
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="4">
                <x-testimonial-card
                    name="Emily Clark"
                    content="The best CMS we have ever used for our agency clients."
                    company="Digital First"
                    role="Creative Director"
                    class="shadow-sm h-100"
                />
            </x-col>
        </x-row>
    </x-container>

    <x-container>
        <h2>Team Cards Example</h2>
        <x-row class="g-4 mb-5">
            <x-col size="3">
                <x-team-card
                    name="Alice Johnson"
                    role="CEO"
                    bio="Leading the company vision with 20 years of experience."
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="3">
                <x-team-card
                    name="Bob Williams"
                    role="CTO"
                    bio="Building scalable solutions with modern technology."
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="3">
                <x-team-card
                    name="Carol Davis"
                    role="Designer"
                    bio="Crafting beautiful interfaces with attention to detail."
                    class="shadow-sm h-100"
                />
            </x-col>
            <x-col size="3">
                <x-team-card
                    name="David Lee"
                    role="Developer"
                    bio="Full-stack developer passionate about clean code."
                    class="shadow-sm h-100"
                />
            </x-col>
        </x-row>
    </x-container>

    <x-container>
        <h2>Content Card Example</h2>
        <x-row class="g-4 mb-5">
            <x-col size="4">
                <x-content-card
                    title="Getting Started Guide"
                    description="Learn how to set up your website in minutes with our step-by-step guide."
                    date="June 1, 2026"
                    link="#"
                />
            </x-col>
            <x-col size="4">
                <x-content-card
                    title="Advanced Features"
                    description="Explore the powerful features that make Microweber stand out."
                    date="June 5, 2026"
                    link="#"
                />
            </x-col>
            <x-col size="4">
                <x-content-card
                    title="Best Practices"
                    description="Tips and tricks for building professional websites."
                    date="June 10, 2026"
                    link="#"
                />
            </x-col>
        </x-row>
    </x-container>

    <x-container>
        <h2>Post Card Example</h2>
        <x-row class="g-4 mb-5">
            <x-col size="4">
                <x-post-card
                    title="How to Build a Blog"
                    description="A comprehensive guide on creating a blog with Microweber."
                    date="May 20, 2026"
                    author="John Doe"
                    link="#"
                />
            </x-col>
            <x-col size="4">
                <x-post-card
                    title="SEO Tips for Beginners"
                    description="Learn the essentials of SEO to boost your site traffic."
                    date="May 25, 2026"
                    author="Jane Smith"
                    link="#"
                />
            </x-col>
            <x-col size="4">
                <x-post-card
                    title="Web Design Trends 2026"
                    description="Stay ahead of the curve with the latest design trends."
                    date="May 30, 2026"
                    author="Alex Brown"
                    link="#"
                />
            </x-col>
        </x-row>
    </x-container>

    <x-container>
        <h2>Product Card Example</h2>
        <x-row class="g-4 mb-5">
            <x-col size="3">
                <x-product-card
                    title="Premium Theme"
                    description="A stunning theme for modern websites."
                    price="$49"
                    link="#"
                />
            </x-col>
            <x-col size="3">
                <x-product-card
                    title="Business Plugin"
                    description="All-in-one business solution plugin."
                    price="$29"
                    original-price="$39"
                    link="#"
                />
            </x-col>
            <x-col size="3">
                <x-product-card
                    title="E-commerce Pack"
                    description="Complete e-commerce starter pack."
                    price="$79"
                    link="#"
                />
            </x-col>
            <x-col size="3">
                <x-product-card
                    title="Out of Stock Item"
                    description="This item is currently unavailable."
                    price="$19"
                    :in-stock="false"
                    link="#"
                />
            </x-col>
        </x-row>
    </x-container>

    <x-container>
        <h2>Media Card Example</h2>
        <x-row class="g-4 mb-5">
            <x-col size="4">
                <x-media-card
                    title="Photo Gallery"
                    description="A collection of beautiful photographs."
                    media-type="image"
                    link="#"
                />
            </x-col>
            <x-col size="4">
                <x-media-card
                    title="Video Tutorial"
                    description="Watch our step-by-step video guide."
                    media-type="video"
                    link="#"
                />
            </x-col>
            <x-col size="4">
                <x-media-card
                    title="Audio Podcast"
                    description="Listen to our latest podcast episode."
                    media-type="audio"
                    link="#"
                />
            </x-col>
        </x-row>
    </x-container>

    <x-container>
        <hr class="my-5">

        <h2>Section Heading Example</h2>
        <x-section-heading tag="h2" subtitle="This is a subtitle for the section heading component.">Section Heading Component</x-section-heading>
        <x-section-heading tag="h3" align="start">Left-Aligned Heading</x-section-heading>

        <h2>Social Links Example</h2>
        <x-social-links />

        <h2>CTA Example (Stacked)</h2>
        <x-cta align="center">
            <x-slot:heading>
                <h3>Ready to get started?</h3>
                <p>Sign up now and start building your website today.</p>
            </x-slot:heading>
            <a href="#" class="btn btn-primary btn-lg">Get Started</a>
        </x-cta>

        <h2>CTA Example (Inline)</h2>
        <x-cta layout="inline" align="start">
            <x-slot:heading>
                <h4>Subscribe to our newsletter</h4>
            </x-slot:heading>
            <a href="#" class="btn btn-outline-primary">Subscribe</a>
        </x-cta>

        <h2>Accordion Example</h2>
        <x-accordion id="demo-accordion">
            <x-accordion-item title="What is Microweber?" :open="true" parent="demo-accordion">
                Microweber is a drag and drop website builder and a powerful next-generation CMS.
            </x-accordion-item>
            <x-accordion-item title="How do I get started?" parent="demo-accordion">
                Simply sign up for an account and start building your website with our intuitive drag and drop editor.
            </x-accordion-item>
            <x-accordion-item title="Is it free?" parent="demo-accordion">
                Microweber offers both free and premium plans to suit your needs.
            </x-accordion-item>
        </x-accordion>

        <h2>Tab Example (New Components)</h2>
        <x-tab id="demo-tab">
            <x-slot:navItems>
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-pane" type="button" role="tab">Home</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-pane" type="button" role="tab">Profile</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="settings-tab" data-bs-toggle="tab" data-bs-target="#settings-pane" type="button" role="tab">Settings</button>
                </li>
            </x-slot:navItems>

            <x-tab-item id="home-pane" :active="true">Home tab content goes here.</x-tab-item>
            <x-tab-item id="profile-pane">Profile tab content goes here.</x-tab-item>
            <x-tab-item id="settings-pane">Settings tab content goes here.</x-tab-item>
        </x-tab>

        <h2>Feature Item Example</h2>
        <div class="row">
            <x-feature-item icon="mw-micon-Add-User" title="User Management" text="Manage your users with ease using our built-in tools." />
            <x-feature-item icon="mw-micon-Shield-Protected" title="Security First" text="Enterprise-grade security included on every plan." />
            <x-feature-item icon="mw-micon-Speed-Fast" title="Lightning Fast" text="NVMe storage and HTTP/3 deliver blazing-fast performance." />
        </div>

        <h2>Stat Counter Example</h2>
        <div class="row">
            <div class="col-md-3">
                <x-stat-counter value="1,200" label="Happy Customers" suffix="+" />
            </div>
            <div class="col-md-3">
                <x-stat-counter value="99.9" label="Uptime" suffix="%" />
            </div>
            <div class="col-md-3">
                <x-stat-counter value="50" label="Countries" prefix="+" />
            </div>
            <div class="col-md-3">
                <x-stat-counter value="24/7" label="Support" />
            </div>
        </div>

        <h2>Video Embed Example</h2>
        <x-video-embed url="https://www.youtube.com/watch?v=dQw4w9WgXcQ" ratio="16x9" />
    </x-container>
</div>

</body>
</html>
