{{--
 type: layout
 name: Feature 62
 position: 62
 categories: Features
--}}

<style>
    .feature-62 .content h3 {
        font-weight: 700;
        font-size: 26px;
        color: #728394;
    }

    .feature-62 .content ul {
        list-style: none;
        padding: 0;
    }

    .feature-62 .content ul li {
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .feature-62 .content ul strong {
        margin-right: 10px;
    }

    .feature-62 .content ul i {
        font-size: 16px;
        margin-right: 5px;
        color: #0563bb;
        line-height: 0;
    }

    .feature-62 .content p:last-child {
        margin-bottom: 0;
    }
</style>

<x-layout-section
    :params="$params"
    :classes="$classes"
    :layout-classes="$layout_classes ?? ''"
    section-class="section feature-62 mw-featured4"
    :has-spacers="false"
    container-class="mw-layout-container no-element"
>
    <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-top"/>

        <div class="mw-layout-container container no-element edit" field="layout-feature-skin-62-{{ $params['id'] }}" rel="module">
            <div class="section-title text-center mb-5">
                <h2 data-mwplaceholder="{{ _e('Enter title here') }}">About</h2>
                <p data-mwplaceholder="{{ _e('Enter text here') }}">Magnam dolores commodi suscipit. Necessitatibus eius consequatur ex aliquid fuga eum quidem. Sit sint consectetur velit. Quisquam quos quisquam cupiditate. Et nemo qui impedit suscipit alias ea. Quia fugiat sit in iste officiis commodi quidem hic quas.</p>
            </div>

            <x-row class="safe-mode">
                <div class="col-lg-4">
                    <img loading="lazy" class="w-100 h-100" src="{{ asset('templates/big/img/layouts/gallery-1-1.jpg') }}" alt=""/>
                </div>
                <div class="col-lg-8 pt-4 pt-lg-0 content safe-mode">
                    <h3 data-mwplaceholder="{{ _e('Enter title here') }}">UI/UX Designer &amp; Web Developer.</h3>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}" class="fst-italic mb-4">
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore
                        magna aliqua.
                    </p>
                    <x-row>
                        <div class="col-lg-6">
                            <ul>
                                <li class="cloneable p-1 element"><i class="mw-micon-Arrow-Right"></i> <strong>Birthday:</strong> <span>1 May 1995</span></li>
                                <li class="cloneable p-1 element"><i class="mw-micon-Arrow-Right"></i> <strong>Website:</strong> <span>www.example.com</span></li>
                                <li class="cloneable p-1 element"><i class="mw-micon-Arrow-Right"></i> <strong>Phone:</strong> <span>+123 456 7890</span></li>
                                <li class="cloneable p-1 element"><i class="mw-micon-Arrow-Right"></i> <strong>City:</strong> <span>New York, USA</span></li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            <ul>
                                <li class="cloneable p-1 element"><i class="mw-micon-Arrow-Right"></i> <strong>Age:</strong> <span>30</span></li>
                                <li class="cloneable p-1 element"><i class="mw-micon-Arrow-Right"></i> <strong>Degree:</strong> <span>Master</span></li>
                                <li class="cloneable p-1 element"><i class="mw-micon-Arrow-Right"></i> <strong>Email:</strong> <span><a href="mailto:email@example.com">email@example.com</a></span></li>
                                <li class="cloneable p-1 element"><i class="mw-micon-Arrow-Right"></i> <strong>Freelance:</strong> <span>Available</span></li>
                            </ul>
                        </div>
                    </x-row>
                    <p data-mwplaceholder="{{ _e('Enter text here') }}">
                        Officiis eligendi itaque labore et dolorum mollitia officiis optio vero. Quisquam sunt adipisci omnis et ut. Nulla accusantium dolor incidunt officia tempore. Et eius omnis.
                        Cupiditate ut dicta maxime officiis quidem quia. Sed et consectetur qui quia repellendus itaque neque. Aliquid amet quidem ut quaerat cupiditate. Ab et eum qui repellendus omnis culpa magni laudantium dolores.
                    </p>
                </div>
            </x-row>
        </div>
        <module height="80px" type="spacer" id="spacer-layout--{{ $params['id'] }}-bottom"/>
</x-layout-section>
