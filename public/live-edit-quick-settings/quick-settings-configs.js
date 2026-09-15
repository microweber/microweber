// task-2026-09-14-qskit — Per-module quick-settings configs.
//
// Each config drives the shared kit (quick-settings-kit.js). Every control key
// is the SAME option the module's real settings form + template already use, so
// a change on the canvas round-trips exactly like the full settings dialog.
//
// Batch 1 (verified template consumption): Marquee, Spacer, Breadcrumb.
// Remaining modules are added here incrementally, one verified config at a time.
(function () {
    if (!window.mw || !mw.quickSettingsKit) { return; }
    var K = mw.quickSettingsKit;

    // ── Marquee ── options: text, fontSize, animationSpeed, textWeight,
    //    textStyle, textColor (all rendered directly by the template). ────────
    K.register({
        type: 'marquee',
        title: 'Marquee',
        badge: 'Mq',
        sections: [
            { type: 'text', label: 'Text', key: 'text', placeholder: 'Your text here' },
            { type: 'swatches', label: 'Color', key: 'textColor', def: '#000000' },
            {
                type: 'segmented', label: 'Size', key: 'fontSize', def: 46,
                options: [{ label: 'S', value: 24 }, { label: 'M', value: 46 }, { label: 'L', value: 72 }]
            },
            {
                type: 'segmented', label: 'Speed', key: 'animationSpeed', def: 100,
                options: [{ label: 'Slow', value: 50 }, { label: 'Normal', value: 100 }, { label: 'Fast', value: 200 }]
            },
            {
                type: 'segmented', label: 'Weight', key: 'textWeight', def: 'normal',
                options: [{ label: 'Normal', value: 'normal' }, { label: 'Bold', value: 'bold' }]
            },
            {
                type: 'segmented', label: 'Style', key: 'textStyle', def: 'normal',
                options: [{ label: 'Normal', value: 'normal' }, { label: 'Italic', value: 'italic' }]
            },
            { type: 'advanced', label: 'Advanced', hint: 'All marquee settings' }
        ]
    });

    // ── Spacer ── option: height (free-form CSS length, fed to the style attr).
    K.register({
        type: 'spacer',
        title: 'Spacer',
        badge: 'Sp',
        sections: [
            {
                type: 'segmented', label: 'Height', key: 'height',
                options: [{ label: 'S', value: '24px' }, { label: 'M', value: '48px' }, { label: 'L', value: '96px' }]
            },
            { type: 'text', label: 'Custom height', key: 'height', placeholder: '50px', suffix: 'px / rem / vh' }
        ]
    });

    // ── Breadcrumb ── option: data-start-from ('' | page | category). ────────
    K.register({
        type: 'breadcrumb',
        title: 'Breadcrumb',
        badge: 'Br',
        sections: [
            {
                type: 'segmented', label: 'Start from', key: 'data-start-from', def: '',
                options: [{ label: 'Default', value: '' }, { label: 'Page', value: 'page' }, { label: 'Category', value: 'category' }]
            },
            { type: 'advanced', label: 'Advanced', hint: 'All breadcrumb settings' }
        ]
    });

    // ── Google Maps ── options: data-map-type, data-zoom, data-show-marker,
    //    data-marker-label (address + API key live in Advanced). ─────────────
    K.register({
        type: 'google_maps',
        title: 'Google Maps',
        badge: 'Gm',
        sections: [
            {
                type: 'select', label: 'Map type', key: 'data-map-type', def: 'roadmap',
                options: [
                    { label: 'Road map', value: 'roadmap' }, { label: 'Satellite', value: 'satellite' },
                    { label: 'Terrain', value: 'terrain' }, { label: 'Hybrid', value: 'hybrid' }
                ]
            },
            {
                type: 'select', label: 'Zoom', key: 'data-zoom', def: '12',
                options: [
                    { label: 'Country', value: '5' }, { label: 'City', value: '10' }, { label: 'District', value: '12' },
                    { label: 'Street', value: '15' }, { label: 'Building', value: '18' }
                ]
            },
            { type: 'toggle', label: 'Show marker', key: 'data-show-marker', def: '1', onValue: '1', offValue: '0' },
            { type: 'text', label: 'Marker label', key: 'data-marker-label', placeholder: 'e.g. Our office' },
            { type: 'advanced', label: 'Advanced', hint: 'Address, size, API key' }
        ]
    });

    // ── Video ── options: autoplay, loop, muted, hide_controls (0/1);
    //    source (embed / upload) + thumbnail live in Advanced. ───────────────
    K.register({
        type: 'video',
        title: 'Video',
        badge: 'Vi',
        sections: [
            { type: 'toggle', label: 'Autoplay', key: 'autoplay', hint: 'Starts muted until interaction', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Loop', key: 'loop', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Muted', key: 'muted', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Hide controls', key: 'hide_controls', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced', hint: 'Source, size, thumbnail' }
        ]
    });

    // ── Batch 3 (scalar modules) ─────────────────────────────────────────────

    // Audio — data-audio-source (file|url), data-audio-url, autoplay, loop,
    //   show_controls (added to the module for the full design). Upload → Advanced.
    K.register({
        type: 'audio', title: 'Audio', badge: 'Au',
        sections: [
            { type: 'segmented', label: 'Source', key: 'data-audio-source', def: 'file', options: [{ label: 'File', value: 'file' }, { label: 'URL', value: 'url' }] },
            { type: 'text', label: 'Audio URL', key: 'data-audio-url', placeholder: 'https://…/audio.mp3' },
            { type: 'toggle', label: 'Autoplay', key: 'autoplay', hint: 'Muted until the visitor interacts', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Loop', key: 'loop', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Show controls', key: 'show_controls', def: '1', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced', hint: 'Upload audio file' }
        ]
    });

    // Facebook page — fbPage, width, height, friends, timeline.
    K.register({
        type: 'facebook_page', title: 'Facebook page', badge: 'Fb',
        sections: [
            { type: 'text', label: 'Page URL', key: 'fbPage', placeholder: 'facebook.com/YourPage' },
            { type: 'text', label: 'Width', key: 'width', inputType: 'number', def: 380, suffix: 'px' },
            { type: 'text', label: 'Height', key: 'height', inputType: 'number', def: 300, suffix: 'px' },
            { type: 'toggle', label: "Show friends' faces", key: 'friends', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Show timeline', key: 'timeline', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced' }
        ]
    });

    // Facebook like — layout, color, show_faces, url.
    K.register({
        type: 'facebook_like', title: 'Facebook like', badge: 'FL',
        sections: [
            { type: 'select', label: 'Layout', key: 'layout', def: 'standard', options: [{ label: 'Standard', value: 'standard' }, { label: 'Button count', value: 'button_count' }, { label: 'Button', value: 'button' }, { label: 'Box count', value: 'box_count' }] },
            { type: 'segmented', label: 'Color', key: 'color', def: 'light', options: [{ label: 'Light', value: 'light' }, { label: 'Dark', value: 'dark' }] },
            { type: 'toggle', label: 'Show faces', key: 'show_faces', def: '1', onValue: '1', offValue: '0' },
            { type: 'text', label: 'URL', key: 'url', placeholder: 'Page to like (blank = this page)' },
            { type: 'advanced', label: 'Advanced' }
        ]
    });

    // Sharer — per-network *_enabled toggles.
    K.register({
        type: 'sharer', title: 'Share buttons', badge: 'Sh',
        sections: [
            { type: 'toggle', label: 'Facebook', key: 'facebook_enabled', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'X (Twitter)', key: 'x_enabled', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Pinterest', key: 'pinterest_enabled', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'LinkedIn', key: 'linkedin_enabled', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Viber', key: 'viber_enabled', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'WhatsApp', key: 'whatsapp_enabled', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Telegram', key: 'telegram_enabled', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced' }
        ]
    });

    // Tweet embed — twitter_url.
    K.register({
        type: 'tweet_embed', title: 'Tweet embed', badge: 'Tw',
        sections: [
            { type: 'text', label: 'Tweet URL', key: 'twitter_url', placeholder: 'https://x.com/…/status/…' },
            { type: 'advanced', label: 'Advanced' }
        ]
    });

    // Embed — code_type, hide_in_live_edit. Source code → Advanced.
    K.register({
        type: 'embed', title: 'Embed', badge: 'Em',
        sections: [
            { type: 'select', label: 'Code type', key: 'code_type', def: 'html', options: [{ label: 'HTML', value: 'html' }, { label: 'CSS', value: 'css' }, { label: 'JavaScript', value: 'javascript' }] },
            { type: 'toggle', label: 'Hide in Live Edit', key: 'hide_in_live_edit', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Edit source code' }
        ]
    });

    // Rating — starColor, starBgColor, starSize.
    K.register({
        type: 'rating', title: 'Rating', badge: 'Ra',
        sections: [
            { type: 'swatches', label: 'Star color', key: 'starColor', def: '#FFD700' },
            { type: 'swatches', label: 'Star background', key: 'starBgColor', def: 'transparent' },
            { type: 'text', label: 'Star size', key: 'starSize', inputType: 'number', def: 24, suffix: 'px' },
            { type: 'advanced', label: 'Advanced' }
        ]
    });

    // Logo — text, text_color, font_size, size (image). Logo image → Advanced.
    K.register({
        type: 'logo', title: 'Logo', badge: 'Lo',
        sections: [
            { type: 'text', label: 'Text', key: 'text', placeholder: 'Brand name' },
            { type: 'swatches', label: 'Text color', key: 'text_color' },
            { type: 'text', label: 'Font size', key: 'font_size', inputType: 'number', suffix: 'px' },
            { type: 'text', label: 'Image size', key: 'size', inputType: 'number', def: 100, suffix: 'px' },
            { type: 'advanced', label: 'Advanced', hint: 'Logo image' }
        ]
    });

    // Pagination — show_first_last, limit, active_color, link_color.
    K.register({
        type: 'pagination', title: 'Pagination', badge: 'Pg',
        sections: [
            { type: 'toggle', label: 'Show first / last', key: 'show_first_last', onValue: '1', offValue: '0' },
            { type: 'text', label: 'Visible pages', key: 'limit', inputType: 'number', def: 5 },
            { type: 'swatches', label: 'Active color', key: 'active_color' },
            { type: 'swatches', label: 'Link color', key: 'link_color' },
            { type: 'advanced', label: 'Advanced' }
        ]
    });

    // Text type — text, textColor, fontSize, animationSpeed (typing text).
    K.register({
        type: 'text_type', title: 'Typed text', badge: 'Tt',
        sections: [
            { type: 'text', label: 'Text', key: 'text', placeholder: 'Your text here' },
            { type: 'swatches', label: 'Color', key: 'textColor', def: '#000000' },
            { type: 'segmented', label: 'Size', key: 'fontSize', def: 24, options: [{ label: 'S', value: 18 }, { label: 'M', value: 24 }, { label: 'L', value: 36 }] },
            { type: 'segmented', label: 'Speed', key: 'animationSpeed', def: 50, options: [{ label: 'Slow', value: 30 }, { label: 'Normal', value: 50 }, { label: 'Fast', value: 100 }] },
            { type: 'advanced', label: 'Advanced' }
        ]
    });

    // Image rollover — size, text, href-url. Images → Advanced.
    K.register({
        type: 'image_rollover', title: 'Image rollover', badge: 'Ir',
        sections: [
            { type: 'text', label: 'Size', key: 'size', inputType: 'number', def: 350, suffix: 'px' },
            { type: 'text', label: 'Text', key: 'text' },
            { type: 'link', label: 'Link', key: 'href-url' },
            { type: 'advanced', label: 'Advanced', hint: 'Default + rollover image' }
        ]
    });

    // Captcha — provider. API keys → Advanced.
    K.register({
        type: 'captcha', title: 'Captcha', badge: 'Ca',
        sections: [
            { type: 'select', label: 'Provider', key: 'provider', def: 'microweber', options: [{ label: 'Microweber', value: 'microweber' }, { label: 'reCAPTCHA v2', value: 'google_recaptcha_v2' }, { label: 'reCAPTCHA v3', value: 'google_recaptcha_v3' }] },
            { type: 'advanced', label: 'Advanced', hint: 'API keys' }
        ]
    });

    // Before / After — direction, starts_at (added to the module); images →
    //   Advanced. Feeds twentytwenty orientation + default_offset_pct.
    K.register({
        type: 'before_after', title: 'Before / After', badge: 'BA',
        sections: [
            { type: 'segmented', label: 'Direction', key: 'direction', def: 'horizontal', options: [{ label: 'Horizontal', value: 'horizontal' }, { label: 'Vertical', value: 'vertical' }] },
            { type: 'segmented', label: 'Starts at', key: 'starts_at', def: '50', options: [{ label: '25%', value: '25' }, { label: '50%', value: '50' }, { label: '75%', value: '75' }] },
            { type: 'advanced', label: 'Advanced', hint: 'Before + after image' }
        ]
    });

    // PDF — data-pdf-source (file|url), data-pdf-url. Upload → Advanced.
    K.register({
        type: 'pdf', title: 'PDF', badge: 'Pd',
        sections: [
            { type: 'segmented', label: 'Source', key: 'data-pdf-source', def: 'file', options: [{ label: 'File', value: 'file' }, { label: 'URL', value: 'url' }] },
            { type: 'text', label: 'PDF URL', key: 'data-pdf-url', placeholder: 'https://…/document.pdf' },
            { type: 'advanced', label: 'Advanced', hint: 'Upload PDF' }
        ]
    });

    // ── Batch 4 (scalar modules) ─────────────────────────────────────────────

    // Newsletter — title, description, require_terms. Mailing list → Advanced.
    K.register({
        type: 'newsletter', title: 'Newsletter', badge: 'Nl',
        sections: [
            { type: 'text', label: 'Title', key: 'title', placeholder: 'Subscribe' },
            { type: 'text', label: 'Description', key: 'description' },
            { type: 'toggle', label: 'Require terms', key: 'require_terms', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced', hint: 'Mailing list, fields' }
        ]
    });

    // Tags — show_tag_counts, tag_size, tag_color, tag_hover_color. Root page → Advanced.
    K.register({
        type: 'tags', title: 'Tags', badge: 'Tg',
        sections: [
            { type: 'toggle', label: 'Show counts', key: 'show_tag_counts', def: '1', onValue: '1', offValue: '0' },
            { type: 'segmented', label: 'Size', key: 'tag_size', def: 'medium', options: [{ label: 'S', value: 'small' }, { label: 'M', value: 'medium' }, { label: 'L', value: 'large' }] },
            { type: 'swatches', label: 'Color', key: 'tag_color' },
            { type: 'swatches', label: 'Hover color', key: 'tag_hover_color' },
            { type: 'advanced', label: 'Advanced', hint: 'Source page' }
        ]
    });

    // Categories — single_only, show_subcats, hide_pages, filter_only_in_stock.
    //   Source content / max depth → Advanced.
    K.register({
        type: 'categories', title: 'Categories', badge: 'Ct',
        sections: [
            { type: 'toggle', label: 'Single category only', key: 'single_only', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Show subcategories', key: 'show_subcats', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Hide pages', key: 'hide_pages', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Only in stock', key: 'filter_only_in_stock', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced', hint: 'Source content, depth' }
        ]
    });

    // Social links — per network <net>_url (auto-sets <net>_enabled via
    //   enableKey). Common networks here; the rest (github, skype, discord,
    //   soundcloud, pinterest, viber) → Advanced.
    K.register({
        type: 'social_links', title: 'Social links', badge: 'So',
        sections: [
            { type: 'text', label: 'Facebook', key: 'facebook_url', enableKey: 'facebook_enabled', placeholder: 'facebook.com/…' },
            { type: 'text', label: 'Instagram', key: 'instagram_url', enableKey: 'instagram_enabled', placeholder: 'instagram.com/…' },
            { type: 'text', label: 'X (Twitter)', key: 'x_url', enableKey: 'x_enabled', placeholder: 'x.com/…' },
            { type: 'text', label: 'LinkedIn', key: 'linkedin_url', enableKey: 'linkedin_enabled', placeholder: 'linkedin.com/…' },
            { type: 'text', label: 'YouTube', key: 'youtube_url', enableKey: 'youtube_enabled', placeholder: 'youtube.com/…' },
            { type: 'text', label: 'WhatsApp', key: 'whatsapp_url', enableKey: 'whatsapp_enabled', placeholder: 'wa.me/…' },
            { type: 'text', label: 'Telegram', key: 'telegram_url', enableKey: 'telegram_enabled', placeholder: 't.me/…' },
            { type: 'advanced', label: 'Advanced', hint: 'GitHub, Pinterest, Skype, Discord, SoundCloud, Viber' }
        ]
    });

    // Background — Overlay (data-background-overlay) + Fit (data-background-size)
    //   added to the module; Color = data-background-color. Image/video → Advanced.
    K.register({
        type: 'background', title: 'Background', badge: 'Bg',
        sections: [
            { type: 'segmented', label: 'Overlay', key: 'data-background-overlay', def: '', options: [{ label: 'None', value: '' }, { label: 'Light', value: 'light' }, { label: 'Dark', value: 'dark' }] },
            { type: 'segmented', label: 'Fit', key: 'data-background-size', def: 'cover', options: [{ label: 'Cover', value: 'cover' }, { label: 'Contain', value: 'contain' }] },
            { type: 'swatches', label: 'Color', key: 'data-background-color' },
            { type: 'advanced', label: 'Advanced', hint: 'Image / video, all background settings' }
        ]
    });

    // ── List modules — DISPLAY options only (item CRUD stays in full settings) ─

    // Shop — default_sort, default_limit, columns, show_price, show_add_to_cart.
    //   Source products / filters → Advanced (the item list is DB-backed).
    K.register({
        type: 'shop', title: 'Shop', badge: 'Sp',
        sections: [
            { type: 'select', label: 'Sort', key: 'default_sort', def: 'created_by_desc', options: [{ label: 'Newest', value: 'created_by_desc' }, { label: 'Oldest', value: 'created_by_asc' }, { label: 'Price ↑', value: 'price_asc' }, { label: 'Price ↓', value: 'price_desc' }] },
            { type: 'text', label: 'Products per page', key: 'default_limit', inputType: 'number', def: 10 },
            { type: 'segmented', label: 'Columns', key: 'columns', def: '3', options: [{ label: '2', value: '2' }, { label: '3', value: '3' }, { label: '4', value: '4' }] },
            { type: 'toggle', label: 'Show price', key: 'show_price', def: '1', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Add-to-cart button', key: 'show_add_to_cart', def: '1', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced', hint: 'Source products, filters' }
        ]
    });

    // Comments — moderation, notifications, per-page, sort, avatars.
    K.register({
        type: 'comments', title: 'Comments', badge: 'Cm',
        sections: [
            { type: 'toggle', label: 'Moderate before publish', key: 'enable_moderation', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Notify admin', key: 'notify_admin', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Notify users', key: 'notify_users', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Show on this page', key: 'show_on_current_content', def: '1', onValue: '1', offValue: '0' },
            { type: 'text', label: 'Comments per page', key: 'comments_per_page', inputType: 'number', def: 10 },
            { type: 'select', label: 'Sort', key: 'sort_order', def: 'newest', options: [{ label: 'Newest first', value: 'newest' }, { label: 'Oldest first', value: 'oldest' }] },
            { type: 'toggle', label: 'User avatars', key: 'show_user_avatar', def: '1', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced' }
        ]
    });

    // Slider — autoplay, autoplay_speed, loop, effect, show_arrows, show_dots.
    //   Slides (DB-backed) → Advanced.
    K.register({
        type: 'slider', title: 'Slider', badge: 'Sl',
        sections: [
            {
                type: 'itemlist', label: 'Slides', endpoint: 'slider-slides', addLabel: 'Add slide', ai: true,
                fields: [
                    { key: 'name', label: 'Title' },
                    { key: 'description', label: 'Description', multiline: true },
                    { key: 'button_text', label: 'Button text' },
                    { key: 'link', label: 'Button link' }
                ]
            },
            { type: 'select', label: 'Effect', key: 'effect', def: 'slide', options: [{ label: 'Slide', value: 'slide' }, { label: 'Fade', value: 'fade' }, { label: 'Coverflow', value: 'coverflow' }] },
            { type: 'toggle', label: 'Autoplay', key: 'autoplay', def: '1', onValue: '1', offValue: '0' },
            { type: 'text', label: 'Autoplay speed', key: 'autoplay_speed', inputType: 'number', def: 3000, suffix: 'ms' },
            { type: 'toggle', label: 'Loop', key: 'loop', def: '1', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Arrows', key: 'show_arrows', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Dots', key: 'show_dots', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced', hint: 'Slides' }
        ]
    });

    // Accordion — inline item list (DB-backed via /api/accordion-items).
    //   Each item = title + content. "Create with AI" opens the full editor.
    K.register({
        type: 'accordion', title: 'Accordion', badge: 'Ac',
        sections: [
            {
                type: 'itemlist', label: 'Items', endpoint: 'accordion-items', addLabel: 'Add item', ai: true,
                fields: [
                    { key: 'title', label: 'Title' },
                    { key: 'content', label: 'Content', multiline: true }
                ]
            },
            { type: 'advanced', label: 'Advanced', hint: 'Icons, design, animation' }
        ]
    });

    // FAQ — inline item list (DB-backed via /api/faq-items). question + answer.
    K.register({
        type: 'faq', title: 'FAQ', badge: 'Fq',
        sections: [
            {
                type: 'itemlist', label: 'Questions', endpoint: 'faq-items', addLabel: 'Add question', ai: true,
                fields: [{ key: 'question', label: 'Question' }, { key: 'answer', label: 'Answer', multiline: true }]
            },
            { type: 'advanced', label: 'Advanced', hint: 'Design' }
        ]
    });

    // Tabs — inline item list (DB-backed via /api/tab-items). title + content.
    K.register({
        type: 'tabs', title: 'Tabs', badge: 'Tb',
        sections: [
            {
                type: 'itemlist', label: 'Tabs', endpoint: 'tab-items', addLabel: 'Add tab', ai: true,
                fields: [{ key: 'title', label: 'Title' }, { key: 'content', label: 'Content', multiline: true }]
            },
            { type: 'advanced', label: 'Advanced', hint: 'Icons, design' }
        ]
    });

    // Testimonials — inline item list (DB-backed via /api/testimonial-items).
    //   Client photo stays in the full editor.
    K.register({
        type: 'testimonials', title: 'Testimonials', badge: 'Ts',
        sections: [
            {
                type: 'itemlist', label: 'Testimonials', endpoint: 'testimonial-items', addLabel: 'Add testimonial', ai: true,
                fields: [
                    { key: 'name', label: 'Name' },
                    { key: 'content', label: 'Quote', multiline: true },
                    { key: 'client_role', label: 'Role' },
                    { key: 'client_company', label: 'Company' }
                ]
            },
            { type: 'advanced', label: 'Advanced', hint: 'Photos, design, source' }
        ]
    });

    // Team cards — inline item list (DB-backed via /api/teamcard-items).
    //   Member photo stays in the full editor.
    K.register({
        type: 'teamcard', title: 'Team', badge: 'Tm',
        sections: [
            {
                type: 'itemlist', label: 'Members', endpoint: 'teamcard-items', addLabel: 'Add member', ai: true,
                fields: [
                    { key: 'name', label: 'Name' },
                    { key: 'role', label: 'Role' },
                    { key: 'bio', label: 'Bio', multiline: true }
                ]
            },
            { type: 'advanced', label: 'Advanced', hint: 'Photos, design' }
        ]
    });

    // Data-source / builder modules — no inline-editable quick properties
    // (their content is chosen / built in the full editor). The panel still
    // adds the quick Duplicate / Delete actions + a labeled shortcut to the
    // right editor, consistent with every other module handle.
    K.register({
        type: 'custom_fields', title: 'Custom fields', badge: 'Cu',
        sections: [{ type: 'advanced', label: 'Manage fields', hint: 'Add, edit & remove fields' }]
    });
    K.register({
        type: 'posts', title: 'Posts', badge: 'Po',
        sections: [{ type: 'advanced', label: 'Manage posts', hint: 'Choose posts & layout' }]
    });
    K.register({
        type: 'shop/products', title: 'Products', badge: 'Pr',
        sections: [{ type: 'advanced', label: 'Choose products', hint: 'Source, layout & filters' }]
    });

    // Pictures — inline image gallery (Media-backed via /api/picture-items).
    //   Add opens the shared media picker; delete + reorder on hover.
    K.register({
        type: 'pictures', title: 'Gallery', badge: 'Pi',
        sections: [
            { type: 'imagelist', label: 'Images', endpoint: 'picture-items', addLabel: 'Add image' },
            { type: 'advanced', label: 'Advanced', hint: 'Source, design' }
        ]
    });

    // Skills — inline item list (JSON-option backed via /api/skill-items).
    //   skill (text) + percent (number) + style (select).
    K.register({
        type: 'skills', title: 'Skills', badge: 'Sk',
        sections: [
            {
                type: 'itemlist', label: 'Skills', endpoint: 'skill-items', addLabel: 'Add skill',
                fields: [
                    { key: 'skill', label: 'Skill' },
                    { key: 'percent', label: 'Percent', type: 'number', placeholder: '0–100' },
                    {
                        key: 'style', label: 'Color', type: 'select',
                        options: [
                            { label: 'Primary', value: 'primary' }, { label: 'Success', value: 'success' },
                            { label: 'Info', value: 'info' }, { label: 'Warning', value: 'warning' }, { label: 'Danger', value: 'danger' }
                        ]
                    }
                ]
            },
            { type: 'advanced', label: 'Advanced', hint: 'Design' }
        ]
    });

    // Contact form — button_text, thank_you_message, autoresponder, newsletter.
    //   Fields + email settings → Advanced.
    K.register({
        type: 'contact_form', title: 'Contact form', badge: 'Cf',
        sections: [
            { type: 'text', label: 'Button text', key: 'button_text', placeholder: 'Send' },
            { type: 'text', label: 'Thank-you message', key: 'thank_you_message', placeholder: 'Thanks — we’ll be in touch' },
            { type: 'toggle', label: 'Autoresponder', key: 'email_autorespond_enable', onValue: '1', offValue: '0' },
            { type: 'toggle', label: 'Newsletter opt-in', key: 'newsletter_subscription', onValue: '1', offValue: '0' },
            { type: 'advanced', label: 'Advanced', hint: 'Fields, email settings' }
        ]
    });
})();
