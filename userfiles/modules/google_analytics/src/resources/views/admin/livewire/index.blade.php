<div>

    <div class="d-flex justify-content-between">
        <div>
        <h2>{{_e('Google Analytics')}}</h2>
        <p>
            {{_e('Google analytics is an analytics tool that allows you to measure the effectiveness of your advertising by understanding the actions people take on your website.')}}
        </p>
        </div>
    </div>

    <div class="mb-3 mt-4 gap-3">


            <div class="card mt-4">
                <div class="card-body mb-4">
                    <div>
                        <label class="live-edit-label">
                            {{_e('GoogleAnalytics pixel id')}}
                        </label>
                        <livewire:microweber-option::text placeholder="Place your GoogleAnalytics pixel id" optionKey="GoogleAnalyticsId" optionGroup="GoogleAnalytics" />
                    </div>
                </div>
            </div>

    </div>

</div>
