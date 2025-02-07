<div class="faq-holder">
    <div class="faq-list">
        @if(isset($faqs) && !empty($faqs))
            @foreach($faqs as $faq)
                <div class="faq-item">
                    <h4>{{ isset($faq['question']) && !empty($faq['question']) ? $faq['question'] : 'No question provided' }}</h4>
                    <p>{{ isset($faq['answer']) && !empty($faq['answer']) ? $faq['answer'] : 'No answer provided' }}</p>
                </div>
            @endforeach
        @else
            <p>No FAQs available.</p>
        @endif
    </div>
</div>

<style>
    .faq-holder {
        margin: 20px;
    }
    .faq-item {
        border: 1px solid #efefef;
        padding: 15px;
        margin-bottom: 10px;
        border-radius: 5px;
    }
    .faq-item h4 {
        margin: 0 0 10px;
        font-size: 18px;
        color: #1157c1;
    }
    .faq-item p {
        margin: 0;
        font-size: 14px;
        color: #6f6f6f;
    }
</style>
