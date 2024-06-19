<div>

    <div class="mt-6 mb-4">
        <h1 class="text-2xl">
            Newsletter PRO v3.0
        </h1>
    </div>

    <div class="flex text-sm font-medium text-center text-gray-500 bg-white shadow rounded-lg dark:divide-gray-700 dark:text-gray-400">
        @foreach ($tabLinks as $tab)

            @php
            $activeClass = '';
            if (route_is($tab['route'])) {
                $activeClass = 'bg-gray-500/10 dark:bg-gray-800 text-black/90 dark:text-white';
            }
            @endphp
            <a href="#" class="first:rounded-l-lg last:rounded-r-lg flex items-center justify-center gap-3 w-full py-4 px-2 hover:bg-gray-500/5 {{$activeClass}}">
                @svg($tab['icon'], "h-6 w-6 text-black/90 dark:text-white")
                {{$tab['name']}}
            </a>
        @endforeach
    </div>

    <div class="mt-4 flex gap-4">
        @foreach ($dashboardStats as $statsI=>$stats)

            @php
            $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500'];
            $statsColor = $colors[$statsI];
            @endphp

            <div class="bg-white shadow rounded-lg first:rounded-l-lg last:rounded-r-lg flex items-center gap-3 w-full py-4 px-6 hover:bg-gray-500/5">
                <div class="{{$statsColor}} rounded p-2">
                    @svg($tab['icon'], "h-8 w-8 text-white")
                </div>
               <div class="text-sm">
                   {{$stats['value']}} <br />
                   {{$stats['name']}} <br />
               </div>
            </div>
        @endforeach
    </div>

    <div class="shadow bg-white rounded p-4 mt-4 border border-gray-500/10 border-l-4 border-l-blue-500 text-sm text-blue-500">
        Add this to your cron jobs to process campaigns. Frequency must be every 30 minutes.
        <div class="flex gap-2 items-center mt-2">
            <code class="rounded text-[0.6rem] px-2 uppercase bg-gray-200 text-gray-600">
                php artisan newsletter:process-campaigns
            </code>
            or
            <a href="javascript:;" class="rounded text-[0.6rem] px-2 uppercase text-blue-500 border border-blue-500">
                run the process manually
            </a>
        </div>
    </div>

    <div class="mt-4">
        @livewire('admin-newsletter-campaign-list')
    </div>

</div>
