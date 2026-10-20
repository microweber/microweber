/**
 * Storefront booking widget.
 *
 * Enhances any `[data-mw-booking]` container on a bookable product page with a
 * flatpickr calendar (date-range for rooms, or a date + time-slot picker for
 * services), disables fully-booked dates/slots from the public availability
 * feed, live-prices the selection, and adds it to the cart carrying the chosen
 * dates so the order can confirm the booking.
 */
import flatpickr from 'flatpickr';
import 'flatpickr/dist/flatpickr.css';

function money(amount, currency) {
    try {
        return new Intl.NumberFormat(undefined, { style: 'currency', currency: currency || 'USD' }).format(amount);
    } catch (e) {
        return (currency || '$') + ' ' + Number(amount).toFixed(2);
    }
}

function availabilityUrl() {
    const base = (window.mw && window.mw.settings && window.mw.settings.site_url) || '';
    return (base ? base.replace(/\/$/, '') : '') + '/booking/availability';
}

function addToCart(productId, price, fields, onDone) {
    if (window.mw && window.mw.cart && typeof window.mw.cart.add_item === 'function') {
        window.mw.cart.add_item(productId, price, onDone, fields);
        return;
    }
    // Fallback: POST the cart API directly.
    const apiUrl = (window.mw && window.mw.settings && window.mw.settings.api_url) || '/api/';
    const body = new URLSearchParams(Object.assign({ content_id: productId, price: price }, fields));
    fetch(apiUrl + 'update_cart', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded', 'X-Requested-With': 'XMLHttpRequest' },
        body: body.toString(),
        credentials: 'same-origin',
    }).then(r => r.json().catch(() => ({}))).then(d => onDone && onDone(d)).catch(() => onDone && onDone({}));
}

function nightsBetween(a, b) {
    return Math.max(0, Math.round((b - a) / 86400000));
}

function initDateRange(el) {
    const productId = el.getAttribute('data-product-id');
    const unitPrice = parseFloat(el.getAttribute('data-unit-price') || '0');
    const currency = el.getAttribute('data-currency') || 'USD';
    const input = el.querySelector('.mw-booking-range');
    const unitsInput = el.querySelector('.mw-booking-units');
    const priceOut = el.querySelector('.mw-booking-price');
    const btn = el.querySelector('.mw-booking-submit');
    const msg = el.querySelector('.mw-booking-msg');

    const from = new Date();
    const to = new Date();
    to.setFullYear(to.getFullYear() + 1);
    const fmt = d => d.toISOString().slice(0, 10);

    // `fp` is referenced by recompute(); declare it with `let` first so the
    // closure never reads it in the temporal dead zone (flatpickr fires its
    // callbacks synchronously during construction, before assignment).
    let fp = null;

    const recompute = () => {
        if (!fp) return;
        const sel = fp.selectedDates;
        const units = Math.max(1, parseInt(unitsInput ? unitsInput.value : '1', 10) || 1);
        if (sel.length === 2) {
            const nights = nightsBetween(sel[0], sel[1]);
            const price = nights * unitPrice * units;
            if (priceOut) priceOut.textContent = nights > 0 ? (nights + (nights > 1 ? ' nights · ' : ' night · ') + money(price, currency)) : '';
            el.__price = price;
            if (btn) btn.disabled = nights < 1;
        } else {
            if (priceOut) priceOut.textContent = '';
            if (btn) btn.disabled = true;
        }
    };

    fp = flatpickr(input, {
        mode: 'range',
        minDate: 'today',
        dateFormat: 'Y-m-d',
        onChange: recompute,
    });

    fetch(availabilityUrl() + '?product_id=' + productId + '&from=' + fmt(from) + '&to=' + fmt(to), { credentials: 'same-origin' })
        .then(r => r.json()).then(data => {
            if (fp) fp.set('disable', (data && data.unavailable) || []);
        }).catch(() => {});

    if (unitsInput) unitsInput.addEventListener('input', recompute);

    if (btn) {
        btn.addEventListener('click', () => {
            const sel = fp.selectedDates;
            if (sel.length !== 2) return;
            const units = Math.max(1, parseInt(unitsInput ? unitsInput.value : '1', 10) || 1);
            btn.disabled = true;
            if (msg) msg.textContent = '';
            addToCart(productId, el.__price || 0, {
                booking_check_in: fmt(sel[0]),
                booking_check_out: fmt(sel[1]),
                booking_units: units,
                booking_guests: (el.querySelector('.mw-booking-guests') || {}).value || '',
            }, (res) => {
                btn.disabled = false;
                if (res && res.error) {
                    if (msg) msg.textContent = res.error;
                } else {
                    window.location.href = ((window.mw && window.mw.settings && window.mw.settings.site_url) || '').replace(/\/$/, '') + '/cart';
                }
            });
        });
    }
}

function initTimeSlot(el) {
    const productId = el.getAttribute('data-product-id');
    const currency = el.getAttribute('data-currency') || 'USD';
    const unitPrice = parseFloat(el.getAttribute('data-unit-price') || '0');
    const dateInput = el.querySelector('.mw-booking-date');
    const slotSelect = el.querySelector('.mw-booking-slot');
    const btn = el.querySelector('.mw-booking-submit');
    const msg = el.querySelector('.mw-booking-msg');

    const loadSlots = (dateStr) => {
        slotSelect.innerHTML = '';
        fetch(availabilityUrl() + '?product_id=' + productId + '&date=' + dateStr, { credentials: 'same-origin' })
            .then(r => r.json()).then(data => {
                const slots = (data && data.slots) || [];
                if (!slots.length) {
                    const o = document.createElement('option');
                    o.textContent = 'No slots available';
                    o.value = '';
                    slotSelect.appendChild(o);
                    if (btn) btn.disabled = true;
                    return;
                }
                slots.forEach(s => {
                    const o = document.createElement('option');
                    o.value = s.start + '|' + s.end;
                    o.textContent = s.start.slice(11) + ' – ' + s.end.slice(11);
                    slotSelect.appendChild(o);
                });
                if (btn) btn.disabled = false;
            }).catch(() => {});
    };

    flatpickr(dateInput, {
        minDate: 'today',
        dateFormat: 'Y-m-d',
        onChange: (sel, dateStr) => loadSlots(dateStr),
    });

    if (btn) {
        btn.addEventListener('click', () => {
            const val = slotSelect.value;
            if (!val) return;
            const parts = val.split('|');
            btn.disabled = true;
            if (msg) msg.textContent = '';
            addToCart(productId, unitPrice, {
                booking_start: parts[0],
                booking_end: parts[1],
                booking_units: 1,
            }, (res) => {
                btn.disabled = false;
                if (res && res.error) {
                    if (msg) msg.textContent = res.error;
                } else {
                    window.location.href = ((window.mw && window.mw.settings && window.mw.settings.site_url) || '').replace(/\/$/, '') + '/cart';
                }
            });
        });
    }
}

function initAll() {
    document.querySelectorAll('[data-mw-booking]').forEach((el) => {
        if (el.__mwBookingInit) return;
        el.__mwBookingInit = true;
        if (el.getAttribute('data-booking-type') === 'time_slot') {
            initTimeSlot(el);
        } else {
            initDateRange(el);
        }
    });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initAll);
} else {
    initAll();
}

export { initAll };
