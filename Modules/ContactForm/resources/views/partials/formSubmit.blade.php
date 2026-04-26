{{--
    Two-cell footer: captcha on the left, submit on the right. The
    button has `w-100` (full width of its parent), but the parent
    `.form-group` shrunk to its content because nothing in the
    flex-children claimed grow space — so the submit rendered as a
    ~90px pill at the right edge. Adding `flex-wrap` to the row and
    `flex: 1 1 auto; min-width: 160px;` to the button cell makes
    the submit:
      - Wrap to its own line on narrow columns (col-md-3) where
        the captcha + button can't both fit horizontally.
      - Claim its share of the row at full width on col-md-6+ so
        the CTA looks like one, not an afterthought.
--}}
<div class="d-flex flex-wrap align-items-center justify-content-between mt-5 gap-3">
    <div class="form-group" style="flex: 0 1 auto;">
        @if(get_option('enable_captcha', $params['id']))
            <module type="captcha" id="captcha_contact_form-{{ $form_id }}"/>
        @endif
    </div>

    <div class="form-group" style="flex: 1 1 auto; min-width: 160px;">
        <button type="submit" class="btn btn-primary w-100" :disabled="$data.loading">
            <span x-show="!$data.loading">{{ $button_text }}</span>
            <span x-cloak x-show="$data.loading">@lang('Sending...')</span>
        </button>
    </div>
</div>
