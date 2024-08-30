<div>

    @php
        $model = \MicroweberPackages\Modules\Newsletter\Models\NewsletterCampaign::find($this->state['id']);
    @endphp

    @if($model)

    <div>
        <h3 class="text-xl">
            Campaign preview
        </h3>
        <p class="text-sm">
            Here is a preview of your campaign before sending it.
        </p>
    </div>

    <div class="flex gap-4 bg-blue-500/5 dark:bg-white/5 dark:border-white/10 rounded-2xl p-8 mt-4">
        <div class="flex items-center justify-center bg-blue-500/10 dark:bg-white/5 transition duration-150 bg-white rounded-xl p-4">
            @if ($model->recipients_from === 'all_subscribers')
            @svg("heroicon-o-users", "h-6 w-6 text-black/90 dark:text-white")
            @endif
            @if ($model->recipients_from === 'specific_list')
            @svg("heroicon-o-list-bullet", "h-6 w-6 text-black/90 dark:text-white")
            @endif
        </div>
        <div class="w-full flex flex-col justify-center">
            <h3 class="font-bold">
                Email to
            </h3>
            <div class="text-sm dark:text-white/80 text-black/80">
                @if ($model->recipients_from === 'all_subscribers')
                    All subscribers
                    <span>({{$model->countSubscribers()}} selected)</span>
                @endif
                @if ($model->recipients_from === 'specific_list')
                    Specific list
                    @php
                        $list = $model->list()->first();
                    @endphp
                    @if ($list)
                    <span>(<b>{{$model->countSubscribers()}}</b> subscribers in <b>{{$list->name}}</b>)</span>
                    @endif
                @endif
            </div>
        </div>
    </div>


    @php
        $senderAccount = $model->senderAccount()->first();
    @endphp

    @if($senderAccount)
    <div class="flex gap-4 bg-blue-500/5 dark:bg-white/5 dark:border-white/10 rounded-2xl p-8 mt-4">
        <div class="flex items-center justify-center">
            <div class="bg-blue-500/10 dark:bg-white/5 transition duration-150 bg-white rounded-xl p-4">
                @svg("heroicon-o-user", "h-6 w-6 text-black/90 dark:text-white")
            </div>
        </div>
        <div class="w-full flex flex-col justify-center">
            <h3 class="font-bold">
                Email from
            </h3>
            <div class="flex flex-col gap-y-1 text-sm dark:text-white/80 text-black/80">
                <div>
                    {{$senderAccount->from_name}} ({{$senderAccount->from_email}})
                </div>
                <div>
                    Reply: {{$senderAccount->reply_email}}
                </div>
            </div>
        </div>
    </div>
    @endif


    <div class="flex gap-4 bg-blue-500/5 dark:bg-white/5 dark:border-white/10 rounded-2xl p-8 mt-4">
        <div class="flex items-center justify-center bg-blue-500/10 dark:bg-white/5 transition duration-150 bg-white rounded-xl p-4">
            @svg("heroicon-o-calendar-days", "h-6 w-6 text-black/90 dark:text-white")
        </div>
        <div class="w-full flex flex-col justify-center">
            <h3 class="font-bold">
                Schedule
            </h3>
            <div class="text-sm dark:text-white/80 text-black/80">
                @if($model->delivery_type == 'send_now')
                Sending campaign right now (Send now)
                @endif
                @if($model->delivery_type == 'schedule')
                Scheduled for <b>{{$model->scheduled_at}} ({{$model->scheduled_timezone}})</b>
                @endif
            </div>
        </div>
    </div>


    @endif

</div>
