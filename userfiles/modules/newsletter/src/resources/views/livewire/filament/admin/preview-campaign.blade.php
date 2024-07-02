<div>


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
            @if ($model->recipients_from === 'specific_lists')
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
                @if ($model->recipients_from === 'specific_lists')
                    Specific list
                    <span>(<b>{{$model->countSubscribers()}}</b> subscribers in <b>{{$model->listName()}}</b>)</span>
                @endif
            </div>
        </div>
    </div>


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
            <div class="flex flex-column gap-4 text-sm dark:text-white/80 text-black/80">
                @php
                $senderAccount = $model->senderAccount()->first();
                @endphp
                Name: {{$senderAccount->from_name}}  <br />
                From: {{$senderAccount->from_email}} <br />
                Reply: {{$senderAccount->reply_email}}
            </div>
        </div>
    </div>


    <div class="flex gap-4 bg-blue-500/5 dark:bg-white/5 dark:border-white/10 rounded-2xl p-8 mt-4">
        <div class="flex items-center justify-center bg-blue-500/10 dark:bg-white/5 transition duration-150 bg-white rounded-xl p-4">
            @svg("heroicon-o-calendar-days", "h-6 w-6 text-black/90 dark:text-white")
        </div>
        <div class="w-full flex flex-col justify-center">
            <h3 class="font-bold">
                Schedule
            </h3>
            <div class="text-sm dark:text-white/80 text-black/80">
                Sending campaign right now (Send now)
            </div>
        </div>
    </div>



</div>
