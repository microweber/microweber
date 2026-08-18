<style>
    .js-modal-livewire.mw-livewire-modal {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
    }

    .js-modal-livewire.mw-livewire-modal.active {
        display: block;
    }

    .js-modal-livewire.mw-livewire-modal[data-mw-modal-no-backdrop="1"] {
        background-color: transparent;
    }

    .js-modal-livewire-content.mw-livewire-modal-content {
        margin: auto;
        background-color: #fff;
        width: 100%;
        overflow: auto;
        position: relative;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.18);
    }

    @media only screen and (min-width: 600px) {
        .js-modal-livewire.mw-livewire-modal {
            padding-top: 100px;
        }

        .js-modal-livewire-content.mw-livewire-modal-content {
            max-width: 480px;
            max-height: calc(100vh - 100px);
        }
    }

    @media only screen and (min-width: 768px) {
        .js-modal-livewire.mw-livewire-modal {
            padding-top: 8%;
        }

        .js-modal-livewire-content.mw-livewire-modal-content {
            max-height: calc(100vh - 100px);
            overflow: auto;
        }
    }

    .mw-modal-close-x {
        position: absolute;
        top: 8px;
        right: 8px;
        width: 44px;
        height: 44px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border: 0;
        background: transparent;
        color: #475569;
        font-size: 24px;
        line-height: 1;
        cursor: pointer;
        border-radius: 6px;
        z-index: 2;
        padding: 0;
    }

    .mw-modal-close-x:hover {
        background: rgba(0, 0, 0, 0.06);
        color: #0f172a;
    }

    .mw-modal-close-x:focus-visible {
        outline: 2px solid #6366f1;
        outline-offset: 2px;
        background: rgba(99, 102, 241, 0.08);
    }

    /* Bare / mw.dialog skins — chrome comes from mw.dialog, not this overlay. */
    .js-modal-livewire.mw-livewire-modal-bare,
    .js-modal-livewire.mw-livewire-modal-mw-dialog {
        position: static;
        width: auto;
        height: auto;
        overflow: visible;
        background-color: transparent;
        padding: 0;
    }

    .js-modal-livewire.mw-livewire-modal-bare .mw-livewire-modal-content,
    .js-modal-livewire.mw-livewire-modal-mw-dialog .mw-livewire-modal-content {
        margin: 0;
        box-shadow: none;
        background: transparent;
        border-radius: 0;
        max-width: none;
        max-height: none;
        overflow: visible;
    }

    .js-modal-livewire.mw-livewire-modal-mw-dialog.mw-dialog-fallback,
    .js-modal-livewire.mw-livewire-modal-bare.mw-dialog-fallback {
        position: fixed;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
        padding-top: 8%;
    }

    .js-modal-livewire.mw-livewire-modal-mw-dialog.mw-dialog-fallback .mw-livewire-modal-content,
    .js-modal-livewire.mw-livewire-modal-bare.mw-dialog-fallback .mw-livewire-modal-content {
        margin: auto;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.18);
        max-width: 800px;
        max-height: calc(100vh - 100px);
        overflow: auto;
        padding: 16px;
    }
</style>
