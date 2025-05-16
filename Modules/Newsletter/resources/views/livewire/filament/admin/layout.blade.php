<div>

    <div class="mt-6 mb-4">

        <div class="mt-4 flex items-center justify-between">
            <h1 class="text-2xl">
                Newsletter PRO v3.0
            </h1>
        </div>

    </div>

    @php
    $tabLinks = [
            [
                'name' => 'Dashboard',
                'icon' => 'heroicon-o-home',
                'route' => 'filament.admin.pages.newsletter.homepage',
            ],
            [
                'name' => 'Campaigns',
                'icon' => 'heroicon-o-megaphone',
                'route' => 'filament.admin.pages.newsletter.campaigns',
            ],
            [
                'name' => 'Lists',
                'icon' => 'heroicon-o-queue-list',
                'route' => 'filament.admin.pages.newsletter.lists',
            ],
            [
                'name' => 'Subscribers',
                'icon' => 'heroicon-o-bell-alert',
                'route' => 'filament.admin.pages.newsletter.subscribers',
            ],
//            [
//                'name' => 'Templates',
//                'icon' => 'heroicon-o-paint-brush',
//                'route' => 'filament.admin.pages.newsletter.templates',
//            ],
            [
                'name' => 'Sender Accounts',
                'icon' => 'heroicon-o-paper-airplane',
                'route' => 'filament.admin.pages.newsletter.sender-accounts',
            ],
            [
                'name' => 'Settings',
                'icon' => 'heroicon-o-cog',
                'route' => '',
            ],
        ];
    @endphp

    <div class="flex text-sm font-medium text-center text-gray-500 bg-white shadow rounded-lg dark:divide-gray-700 dark:text-gray-400">
        @foreach ($tabLinks as $tab)

            @php
                $activeClass = '';
                if (route_is($tab['route'])) {
                    $activeClass = 'bg-gray-500/10 dark:bg-gray-800 text-black/90 dark:text-white';
                }
            @endphp

            <a
                @if($tab['route'])
                    href="{{route($tab['route'])}}"
                @endif
                class="first:rounded-l-lg last:rounded-r-lg cursor-pointer flex items-center justify-center gap-3 w-full py-4 px-2 hover:bg-gray-500/5 {{$activeClass}}">
                @svg($tab['icon'], "h-6 w-6 text-black/90 dark:text-white dark:fill-white")
                {{$tab['name']}}
            </a>
        @endforeach
    </div>


    <div class="mt-4">
        @yield('newsletter-content')
    </div>

</div>
