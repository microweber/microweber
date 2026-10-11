<style>
.mw-quick-nav {
    display: none;
    position: absolute;
    top: 100%;
    right: 0;
    margin-top: 4px;
    width: 320px;
    background: #fff;
    border: 1px solid #dadfe5;
    border-radius: 8px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    z-index: 50;
    padding: 8px 0;
    max-height: 420px;
    overflow-y: auto;
}
.mw-quick-nav.is-visible {
    display: block;
}
.mw-quick-nav-title {
    font-size: 11px;
    font-weight: 600;
    color: #4a5568;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    padding: 8px 16px 4px;
}
.mw-quick-nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 8px 16px;
    color: #182433;
    text-decoration: none;
    font-size: 14px;
    transition: background 0.1s;
}
.mw-quick-nav-item:hover {
    background: #f0f4f8;
    color: #182433;
    text-decoration: none;
}
.mw-quick-nav-item svg {
    width: 18px;
    height: 18px;
    color: #4a5568;
    flex-shrink: 0;
}
html.dark .mw-quick-nav {
    background: #1a1f2e;
    border-color: #2d3748;
}
html.dark .mw-quick-nav-title {
    color: #a0aec0;
}
html.dark .mw-quick-nav-item {
    color: #e2e8f0;
}
html.dark .mw-quick-nav-item:hover {
    background: #2d3748;
    color: #e2e8f0;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchField = document.querySelector('.fi-global-search-field');
    if (!searchField) return;

    // Make the search field container position:relative for dropdown positioning
    const searchContainer = document.querySelector('.fi-global-search');
    if (searchContainer) searchContainer.style.position = 'relative';

    // Build quick nav items from sidebar
    const navItems = [];
    const sidebarLinks = document.querySelectorAll('.fi-sidebar-item a.fi-sidebar-item-btn');
    sidebarLinks.forEach(function(link) {
        const labelEl = link.querySelector('.fi-sidebar-item-label');
        const iconEl = link.querySelector('.fi-sidebar-item-icon');
        if (labelEl) {
            navItems.push({
                label: labelEl.textContent.trim(),
                url: link.getAttribute('href'),
                iconHtml: iconEl ? iconEl.outerHTML : ''
            });
        }
    });

    // Also add group buttons that act as links
    const groupBtns = document.querySelectorAll('.fi-sidebar-group-btn');
    groupBtns.forEach(function(btn) {
        const label = btn.querySelector('.fi-sidebar-group-label');
        const icon = btn.querySelector('svg:first-child');
        if (label && label.textContent.trim()) {
            // Group buttons are not links, skip them
        }
    });

    // Limit to top 10
    const topItems = navItems.slice(0, 10);

    // Create dropdown
    const dropdown = document.createElement('div');
    dropdown.className = 'mw-quick-nav';

    let html = '<div class="mw-quick-nav-title">Quick Navigation</div>';
    topItems.forEach(function(item) {
        html += '<a href="' + item.url + '" class="mw-quick-nav-item">' +
            item.iconHtml +
            '<span>' + item.label + '</span>' +
            '</a>';
    });
    dropdown.innerHTML = html;

    // Fix icon sizing in dropdown
    dropdown.querySelectorAll('.fi-sidebar-item-icon').forEach(function(icon) {
        icon.style.width = '18px';
        icon.style.height = '18px';
    });

    searchContainer.appendChild(dropdown);

    // Show/hide on focus
    const input = searchField.querySelector('input');
    if (!input) return;

    let hideTimeout = null;

    input.addEventListener('focus', function() {
        clearTimeout(hideTimeout);
        if (!input.value.trim()) {
            dropdown.classList.add('is-visible');
        }
    });

    input.addEventListener('input', function() {
        if (input.value.trim()) {
            dropdown.classList.remove('is-visible');
        } else {
            dropdown.classList.add('is-visible');
        }
    });

    input.addEventListener('blur', function() {
        hideTimeout = setTimeout(function() {
            dropdown.classList.remove('is-visible');
        }, 200);
    });

    // Keep dropdown open when clicking inside it
    dropdown.addEventListener('mousedown', function(e) {
        clearTimeout(hideTimeout);
    });
});
</script>
