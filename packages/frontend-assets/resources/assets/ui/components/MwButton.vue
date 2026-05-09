<template>
    <!--
      AI-118 / TICKET-CH (cycle-109 2026-05-09): canonical MwButton.
      Replaces ad-hoc <button class="btn"> sites across the admin Vue
      surfaces. Constraints called out by the brief:

        - tabindex prop          → keyboard-focusable; defaults to 0
        - aria-disabled support  → disabled state is announced AND
                                   the click handler short-circuits
                                   (vs. the HTML `disabled` attribute
                                   which strands focus on click)
        - 44×44 minimum touch    → enforced via the .mw-button class
                                   in the scoped style block
        - no inline onclick      → @click handler in the template
                                   instead of an `onclick=`
                                   attribute (CSP-clean by design)

      Usage (one migration site demo — see SetupWizard.vue):
        <MwButton @click="handleClick" aria-label="Save">Save</MwButton>
        <MwButton :disabled="isLoading" :tabindex="0">…</MwButton>
    -->
    <button
        type="button"
        class="mw-button"
        :class="[
            variant ? `mw-button-variant-${variant}` : '',
            size ? `mw-button-size-${size}` : '',
            { 'mw-button-disabled': disabled, 'mw-button-loading': loading },
        ]"
        :aria-disabled="disabled ? 'true' : null"
        :aria-busy="loading ? 'true' : null"
        :tabindex="disabled ? -1 : tabindex"
        :data-mw-action="action"
        @click="onClick"
    >
        <span v-if="loading" class="mw-button-spinner" aria-hidden="true"></span>
        <slot></slot>
    </button>
</template>

<script>
export default {
    name: "MwButton",
    props: {
        // The visual variant — passes through as a class hook so
        // theme overrides can target individual variants.
        variant: {
            type: String,
            default: "primary",
            validator: (v) =>
                ["primary", "secondary", "danger", "success", "ghost"].includes(v),
        },
        // Size hint. The 44×44 minimum is enforced by the base class;
        // sizes scale UP from there (never below).
        size: {
            type: String,
            default: "md",
            validator: (v) => ["sm", "md", "lg"].includes(v),
        },
        // aria-disabled is preferred over HTML `disabled` so SR users
        // hear "dimmed" / "unavailable" instead of the button being
        // skipped entirely (HTML disabled removes from tab order).
        disabled: {
            type: Boolean,
            default: false,
        },
        // Loading hint — sets aria-busy + adds the spinner slot.
        loading: {
            type: Boolean,
            default: false,
        },
        // Per the brief: explicit `tabindex` prop. Default 0
        // (focusable in source order). Set to -1 to skip in tab order
        // while still allowing programmatic focus.
        tabindex: {
            type: [Number, String],
            default: 0,
        },
        // data-mw-action passes through to the rendered DOM so a
        // delegated listener (per cycle-87 pattern) can still target
        // the button without inline onclick handlers.
        action: {
            type: String,
            default: null,
        },
    },
    emits: ["click"],
    methods: {
        onClick(event) {
            // aria-disabled buttons must NOT fire @click. HTML
            // `disabled` would short-circuit this for free; with
            // aria-disabled we have to do it manually.
            if (this.disabled || this.loading) {
                event.preventDefault();
                event.stopPropagation();
                return;
            }
            this.$emit("click", event);
        },
    },
};
</script>

<style scoped>
/* AI-118 / TICKET-CH (cycle-109): 44×44 minimum touch target.
   Per WCAG 2.5.5 / iOS HIG — every interactive element must be at
   least 44×44 CSS pixels so finger taps land reliably. The base
   class enforces it; sizes scale up (sm = 44, md = 48, lg = 56). */
.mw-button {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 44px;
    min-height: 44px;
    padding: 0.5rem 1rem;
    border: 1px solid transparent;
    border-radius: 0.375rem;
    font-weight: 500;
    line-height: 1.5;
    text-align: center;
    text-decoration: none;
    cursor: pointer;
    user-select: none;
    transition: background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, color 0.15s ease-in-out;
}

.mw-button-size-sm {
    min-width: 44px;
    min-height: 44px;
    padding: 0.25rem 0.75rem;
    font-size: 0.875rem;
}
.mw-button-size-md {
    min-width: 48px;
    min-height: 48px;
}
.mw-button-size-lg {
    min-width: 56px;
    min-height: 56px;
    padding: 0.75rem 1.5rem;
    font-size: 1.125rem;
}

/* Variants — delegate the actual colours to a theme stylesheet via
   CSS custom properties so dark-mode + brand colours don't fork
   here. Keys mirror Bootstrap's button-variant defaults. */
.mw-button-variant-primary {
    background-color: var(--mw-button-primary-bg, var(--bs-primary, #0d6efd));
    color: var(--mw-button-primary-color, #fff);
    border-color: var(--mw-button-primary-bg, var(--bs-primary, #0d6efd));
}
.mw-button-variant-secondary {
    background-color: var(--mw-button-secondary-bg, var(--bs-secondary, #6c757d));
    color: var(--mw-button-secondary-color, #fff);
    border-color: var(--mw-button-secondary-bg, var(--bs-secondary, #6c757d));
}
.mw-button-variant-danger {
    background-color: var(--mw-button-danger-bg, var(--bs-danger, #dc3545));
    color: var(--mw-button-danger-color, #fff);
    border-color: var(--mw-button-danger-bg, var(--bs-danger, #dc3545));
}
.mw-button-variant-success {
    background-color: var(--mw-button-success-bg, var(--bs-success, #198754));
    color: var(--mw-button-success-color, #fff);
    border-color: var(--mw-button-success-bg, var(--bs-success, #198754));
}
.mw-button-variant-ghost {
    background-color: transparent;
    color: var(--mw-button-ghost-color, var(--bs-body-color, #212529));
    border-color: transparent;
}

.mw-button:focus-visible {
    outline: 2px solid var(--mw-focus-ring-color, var(--bs-primary, #0d6efd));
    outline-offset: 2px;
}

/* aria-disabled — visual cue that matches the SR-announced state. */
.mw-button-disabled,
.mw-button[aria-disabled="true"] {
    opacity: 0.6;
    cursor: not-allowed;
}

.mw-button-loading {
    cursor: wait;
}
.mw-button-spinner {
    display: inline-block;
    width: 1em;
    height: 1em;
    margin-right: 0.5rem;
    border: 2px solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: mw-button-spin 0.6s linear infinite;
}
@keyframes mw-button-spin {
    to {
        transform: rotate(360deg);
    }
}
</style>
