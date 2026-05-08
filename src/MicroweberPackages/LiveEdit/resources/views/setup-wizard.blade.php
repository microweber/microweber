<!DOCTYPE html>
<html <?php print lang_attributes(); ?>>
<head>
    <title><?php _e('Setup Wizard'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="robots" content="noindex">
    <?php get_favicon_tag(); ?>

    <?php print mw_admin_header_scripts() ?>


    <link type="text/css" rel="stylesheet" media="all"
          href="<?php print asset('vendor/microweber-packages/microweber-filament-theme/build/microweber-filament-theme.css'); ?>"/>
    <link type="text/css" rel="stylesheet" media="all"
          href="<?php print asset('vendor/microweber-packages/frontend-assets/build/install.css'); ?>"/>

</head>

<body class="bg-gray-50">




<main class="w-full min-h-screen py-10 bg-[#ececec]">
    <link href="//fonts.googleapis.com/css?family=Inter:200,300,400,500,600,700,800,900" rel="stylesheet"/>
    <div class="templates-wrapper max-w-[1650px] mx-auto px-4 sm:px-6 lg:px-8 relative">
        <!-- Loading Overlay -->
        <div id="form-loading-overlay"
             class="hidden fixed inset-0   bg-opacity-50 flex items-center justify-center z-50"
             style="background-color: rgb(149 187 221);">
            <div class="bg-white rounded-lg p-8 flex items-center gap-x-4 shadow-xl">
                <svg class="animate-spin h-8 w-8 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                     viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"
                            fill="none"></circle>
                    <path class="opacity-75" fill="currentColor"
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                          style="fill: #ffffff;stroke-width: 0px;"></path>
                </svg>
                <span class="text-lg font-medium text-gray-700"
                      id="installing_template_text">Installing template...</span>
            </div>
        </div>

        <div class="text-center mb-6 p-10">
            <h2 class="text-3xl font-thin text-gray-800 mb-3">Select template for your Website</h2>
            <p>Choose template and customize it to fit your
                style and ideas</p>
        </div>

        <!-- Categories Pills - Centered -->
        <div class="mb-8 flex justify-center">
            <div
                class="bg-[#f4f4f4] rounded-xl shadow-sm hover:shadow-md transition-all duration-300 p-3 inline-flex flex-wrap gap-2 justify-center">
                <button onclick="filterTemplates('')"
                        class="live-edit-label px-4 py-2 rounded-lg hover:bg-gray-100 hover:text-gray-900 hover:scale-[1.02] transition-all duration-200 text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 category-filter active"
                        data-category="">
                    All categories
                </button>
                @foreach($categories as $category)
                    <button onclick="filterTemplates('{{ $category }}')"
                            class="live-edit-label px-4 py-2 rounded-lg hover:bg-gray-100 hover:text-gray-900 hover:scale-[1.02] transition-all duration-200 text-gray-700 font-medium focus:outline-none focus:ring-2 focus:ring-blue-500 category-filter mt-0 mb-0"
                            data-category="{{ $category }}">
                        {{ $category }}
                    </button>
                @endforeach
            </div>
        </div>

        <!-- Templates Grid -->
        <div class="templates-container grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($templates as $template)
                <div
                    class="template-item group bg-[#f4f4f4] rounded-xl overflow-hidden border border-gray-200 shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 mb-4"
                    data-categories="{{ json_encode($template['categories']) }}">
                    {{-- audit-test 2026-05-08 PM TASK-017 / TICKET-AB cycle-52 sweep:
                         <img> migration. Empty <div bg-image> had nothing on top so
                         <img> is the simpler shape; click handler kept on the wrapper. --}}
                    <div class="template-preview"
                         onclick="installTemplate('{{ $template['dir_name'] }}')"
                         style="cursor:pointer; background-color: {{ empty($template['screenshot']) ? '#e5e7eb' : 'transparent' }};">
                        @if(!empty($template['screenshot']))
                            <img src="{{ $template['screenshot'] }}"
                                 alt="{{ $template['name'] ?? 'Template' }}"
                                 loading="lazy"
                                 decoding="async"
                                 class="d-block w-100"
                                 style="object-fit: cover;">
                        @endif
                    </div>
                    <div class="template-info">

                        @php
                            $displayName = $template['name'] ?? 'Untitled Template';

                            //remove numbers from the end
                            $displayName = preg_replace('/\s*\d+$/', '', $displayName);

                        @endphp


                        <h3 class="text-lg font-semibold text-gray-800 mb-4">{{ $displayName }}</h3>
                        {{--                                @if(isset($template['description']))--}}
                        {{--                                    <div class="template-description mb-4">--}}
                        {{--                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $template['description'] }}</p>--}}
                        {{--                                    </div>--}}
                        {{--                                @endif--}}

                        @if(!empty($template['categories']))
                            <div class="template-categories mb-4">
                                <div class="flex flex-wrap gap-1">
                                    @php
                                        $remove = ['cms', 'template', 'templates', 'default', 'website', 'default-template'];
                                        $filteredCategories = [];
                                        foreach($template['categories'] as $category) {
                                            if(!in_array(strtolower($category), $remove)) {
                                                $filteredCategories[] = $category;
                                            }
                                        }
                                    @endphp
                                    @foreach($filteredCategories as $category)
                                        <label
                                            class="live-edit-label !font-thin">{{ $category }}{{ !$loop->last ? ', ' : '' }}</label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <button
                            class="use-template-btn btn btn-outline-dark w-full hover:bg-gray-800 text-black hover:text-white py-2.5 px-4 rounded-lg transition-colors duration-200 transform hover:scale-[1.02] shadow-sm flex items-center justify-center"
                            onclick="installTemplate('{{ $template['dir_name'] }}')">
                            <span>Use this template</span>
                            <svg xmlns="http://www.w3.org/2000/svg"
                                 class="h-5 w-5 ml-2 opacity-0 group-hover:opacity-100 transition-opacity duration-200"
                                 fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        function filterTemplates(category) {
            // Track category filter usage
            if (typeof posthog !== "undefined") {
                posthog.capture('template_category_filtered', {
                    category: category || 'all',
                    page: 'setup_wizard'
                });
            }

            if (typeof clarity !== "undefined") {
                clarity('set', 'template_category_filtered', category || 'all');
            }

            // Update active category in sidebar
            document.querySelectorAll('.category-filter').forEach(button => {
                if (button.dataset.category === category) {
                    button.classList.add('bg-gray-100', 'text-gray-900');
                    button.classList.add('active');
                } else {
                    button.classList.remove('bg-gray-100', 'text-gray-900');
                    button.classList.remove('active');
                }
            });

            // Filter templates with fade effect
            const items = document.querySelectorAll('.template-item');
            items.forEach(item => {
                const categories = JSON.parse(item.dataset.categories);
                const shouldShow = !category || categories.includes(category);

                if (shouldShow) {
                    item.classList.remove('opacity-0', 'scale-95');
                    item.classList.add('opacity-100', 'scale-100');
                    setTimeout(() => {
                        item.style.display = 'block';
                    }, 50);
                } else {
                    item.classList.remove('opacity-100', 'scale-100');
                    item.classList.add('opacity-0', 'scale-95');
                    setTimeout(() => {
                        item.style.display = 'none';
                    }, 300);
                }
            });
        }

        function installTemplate(template) {
            // Track template selection
            if (typeof posthog !== "undefined") {
                posthog.capture('template_selected', {
                    template: template,
                    page: 'setup_wizard'
                });
            }

            if (typeof clarity !== "undefined") {
                clarity('set', 'template_selected', template);
            }

            // Show loading overlay
            const loadingOverlay = document.getElementById('form-loading-overlay');
            const loadingText = document.getElementById('installing_template_text');
            loadingOverlay.classList.remove('hidden');

            // Rotating text messages
            const messages = [
                'Installing template...',
                'Please wait...',
                'Will be ready shortly...',
                'Setting up your website...',
                'Almost done...'
            ];

            let messageIndex = 0;
            loadingText.textContent = messages[messageIndex];

            // Start rotating messages
            const messageInterval = setInterval(() => {
                messageIndex = (messageIndex + 1) % messages.length;
                loadingText.textContent = messages[messageIndex];
            }, 5000); // Change message every 5 seconds


            // Disable all buttons
            const allButtons = document.querySelectorAll('.use-template-btn');
            allButtons.forEach(btn => {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
            });

            fetch("{{ route('admin.setup-wizard.install-template') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({template: template})
            })
                .then(response => response.json())
                .then(data => {
                    clearInterval(messageInterval); // Stop rotating messages
                    loadingOverlay.classList.add('hidden');

                    if (data.error) {
                        // Track template installation error
                        if (typeof posthog !== "undefined") {
                            posthog.capture('template_installation_error', {
                                template: template,
                                error: data.error,
                                page: 'setup_wizard'
                            });
                        }

                        // Re-enable buttons on error
                        allButtons.forEach(btn => {
                            btn.disabled = false;
                            btn.classList.remove('opacity-50', 'cursor-not-allowed');
                        });

                        mw.notification.error(data.error);
                    } else if (data.success) {
                        // Track successful template installation
                        if (typeof posthog !== "undefined") {
                            posthog.capture('template_installed', {
                                template: template,
                                page: 'setup_wizard'
                            });
                        }

                        if (typeof clarity !== "undefined") {
                            clarity('set', 'template_installed', template);
                        }

                        mw.notification.success(data.success);


                        const wrapperMakeHiddern = document.querySelector('.templates-wrapper');

                        if (wrapperMakeHiddern) {
                            wrapperMakeHiddern.classList.add('hidden');
                        }

                        @php
                            $promptParams='';
                            if(request()->has('prompt')) {
                                $promptParams = '&prompt=' . urlencode(request()->get('prompt'));
                            }

                        @endphp



                            window.location.href = "{{ admin_url('live-edit') }}?setup_wizard=true&url=" + encodeURIComponent('{{ site_url() }}') + "&template=" + encodeURIComponent(template) + "{!! $promptParams !!}";
                    }
                })
                .catch(error => {
                    clearInterval(messageInterval); // Stop rotating messages
                    loadingOverlay.classList.add('hidden');

                    // Track template installation error
                    if (typeof posthog !== "undefined") {
                        posthog.capture('template_installation_error', {
                            template: template,
                            error: error.toString(),
                            page: 'setup_wizard'
                        });
                    }

                    // Re-enable buttons on error
                    allButtons.forEach(btn => {
                        btn.disabled = false;
                        btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    });

                    mw.notification.error('Error installing template');
                });
        }

        // Initialize first category as active
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelector('.category-filter').classList.add('bg-gray-100', 'text-gray-900');
        });


        @if(isset($_GET['template']) && !empty($_GET['template']))

        var existingTemplates = [];
        @if(isset($templates) && is_array($templates))
        @foreach($templates as $template)
        existingTemplates.push('{{ $template['dir_name'] }}');
        @endforeach
        @endif


        // install if in existingTemplates
        document.addEventListener('DOMContentLoaded', function () {
            const templateToInstall = '{{ $_GET['template'] }}';
            if (existingTemplates.includes(templateToInstall)) {
                installTemplate(templateToInstall);
            }

        });
        @endif


        //NotaryServices2

    </script>

    <style>
        /* You can keep this section for compatibility with existing styles */
        .templates-wrapper {
            padding: 20px;
            margin: 0 auto;
        }

        .hidden {
            display: none !important;
        }

        .templates-container {
            gap: 20px;
        }

        .template-item {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
            transition: all 0.3s ease;
            display: block;
            opacity: 1;
            transform: scale(1);
        }

        .template-item:hover {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        }

        .template-preview {
            width: 100%;
            height: 350px;
            background-size: cover;
            background-position: top center;
            background-repeat: no-repeat;
            transition: background-position 3s ease-in-out;
        }

        .template-preview:hover {
            background-position: bottom center;
        }

        .template-info {
            border-top: 1px solid #9d9d9d;
            padding: 20px;
        }

        .template-info h3 {
            margin: 0 0 15px 0;
            font-size: 16px;
        }

        /* Line clamp for description */
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Active category style - enhanced */
        .category-filter.active {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: 600;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        /* Add hover effect for sidebar items */
        .category-filter {
            position: relative;
            overflow: hidden;
        }

        .category-filter:hover {
            transform: translateX(3px);
        }


        .top-5 {
            top: 20px !important;
        }

        .template-categories {
            label {
                font-weight: 400 !important;
            }
        }

        .use-template-btn {
            &:hover:not(:disabled) {
                background-color: #1f2937 !important;
                color: #ffffff !important;
            }

            &:disabled {
                cursor: not-allowed !important;
                transform: none !important;
            }

            svg {
                &:hover {
                    fill: #ffffff !important;
                }
            }
        }

        .animate-spin {
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            from {
                transform: rotate(0deg);
            }
            to {
                transform: rotate(360deg);
            }
        }
    </style>
</main>

<?php print mw_admin_footer_scripts() ?>

</body>
</html>
