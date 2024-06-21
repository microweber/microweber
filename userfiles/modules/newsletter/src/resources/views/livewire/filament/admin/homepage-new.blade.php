<div>
    @php
        $quickActions = [
            [
                'name' => 'Create new campaign',
                'icon' => 'heroicon-o-document-text',
                'url' => ''
            ],
            [
                'name' => 'Add a subscriber',
                'icon' => 'heroicon-o-user-plus',
                'url' => ''
            ],
            [
                'name' => 'Import contacts',
                'icon' => 'heroicon-o-users',
                'url' => ''
            ]
        ];
    @endphp
    <div class="">
        <h3 class="font-bold">Quick actions</h3>
        <div class="flex gap-8 mt-2">
            @foreach ($quickActions as $action)
                <a href="{{$action['url']}}" class="bg-white hover:shadow rounded-2xl border border-gray-500/60 flex items-center gap-3 w-full py-4 px-6 hover:bg-gray-500/5">
                    @svg($action['icon'], "h-6 w-6 text-blue-500")
                    {{$action['name']}}
                </a>
            @endforeach
        </div>
    </div>

    <div class="mt-8 flex gap-8 bg-white rounded-lg border border-gray-500/60">
        @if(isset($dashboardStats))
            @foreach ($dashboardStats as $statsI=>$stats)

                @php
                    $colors = ['bg-blue-500', 'bg-green-500', 'bg-yellow-500', 'bg-red-500'];
                    $statsColor = $colors[$statsI];
                @endphp

                <div class="flex items-center gap-3 w-full py-4 px-6 hover:bg-gray-500/5">
                    <div class="{{$statsColor}} rounded p-2">
                        @svg($stats['icon'], "h-8 w-8 text-white")
                    </div>
                    <div class="text-sm">
                        <b class="font-bold text-black/50">{{$stats['value']}}</b> <br />
                        {{$stats['name']}} <br />
                    </div>
                </div>
            @endforeach
        @endif
    </div>

<!--    <div class="bg-white rounded p-4 mt-4 mb-4 border border-gray-500/10 border-l-4 border-l-blue-500 text-sm text-blue-500">
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
    </div>-->
</div>
