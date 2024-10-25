<div class="faq-module">
    <h2>Frequently Asked Questions</h2>
    <div class="accordion" id="faqAccordion">
        @foreach($faqs as $faq)
            <div class="card">
                <div class="card-header" id="heading{{ $faq->id }}">
                    <h5 class="mb-0">
                        <button class="btn btn-link" data-toggle="collapse" data-target="#collapse{{ $faq->id }}" aria-expanded="true" aria-controls="collapse{{ $faq->id }}">
                            {{ $faq->question }}
                        </button>
                    </h5>
                </div>

                <div id="collapse{{ $faq->id }}" class="collapse" aria-labelledby="heading{{ $faq->id }}" data-parent="#faqAccordion">
                    <div class="card-body">
                        {{ $faq->answer }}
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
