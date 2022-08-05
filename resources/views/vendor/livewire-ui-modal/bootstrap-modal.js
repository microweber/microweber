window.LivewireUIBootstrapModal = () => {
    return {
        show: false,
        showActiveComponent: true,
        activeComponent: false,
        componentHistory: [],
        modalWidth: null ,
        getActiveComponentModalAttribute(key) {
            if (this.$wire.get('components')[this.activeComponent] !== undefined) {
                return this.$wire.get('components')[this.activeComponent]['modalAttributes'][key];
            }
        },
        closeModalOnEscape(trigger) {
            if (this.getActiveComponentModalAttribute('closeOnEscape') === false) {
                return;
            }

            let force = this.getActiveComponentModalAttribute('closeOnEscapeIsForceful') === true;
            this.closeModal(force);
        },
        closeModalOnClickAway(trigger) {
            if (this.getActiveComponentModalAttribute('closeOnClickAway') === false) {
                return;
            }

            this.closeModal(true);
        },
        closeModal(force = false, skipPreviousModals = 0, destroySkipped = false) {
            if(this.show === false) {
                return;
            }

            if (this.getActiveComponentModalAttribute('dispatchCloseEvent') === true) {
                const componentName = this.$wire.get('components')[this.activeComponent].name;
                Livewire.emit('modalClosed', componentName);
            }

            if (this.getActiveComponentModalAttribute('destroyOnClose') === true) {
                Livewire.emit('destroyComponent', this.activeComponent);
            }

            if (skipPreviousModals > 0) {
                for (var i = 0; i < skipPreviousModals; i++) {
                    if (destroySkipped) {
                        const id = this.componentHistory[this.componentHistory.length - 1];
                        Livewire.emit('destroyComponent', id);
                    }
                    this.componentHistory.pop();
                }
            }

            const id = this.componentHistory.pop();

            if (id && force === false) {
                if (id) {
                    this.setActiveModalComponent(id, true);
                } else {
                    this.setShowPropertyTo(false);
                }
            } else {
                this.setShowPropertyTo(false);
            }
        },
        setActiveModalComponent(id, skip = false) {
            this.setShowPropertyTo(true);

            if (this.activeComponent === id) {
                return;
            }

            if (this.activeComponent !== false && skip === false) {
                this.componentHistory.push(this.activeComponent);
            }

            let focusableTimeout = 50;

            if (this.activeComponent === false) {
                this.activeComponent = id
                this.showActiveComponent = true;
                this.modalWidth = this.getActiveComponentModalAttribute('maxWidthClass');
            } else {
                this.showActiveComponent = false;

                focusableTimeout = 400;

                setTimeout(() => {
                    this.activeComponent = id;
                    this.showActiveComponent = true;
                    this.modalWidth = this.getActiveComponentModalAttribute('maxWidthClass');
                }, 300);
            }

            this.$nextTick(() => {
                let focusable = this.$refs[id]?.querySelector('[autofocus]');
                if (focusable) {
                    setTimeout(() => {
                        focusable.focus();
                    }, focusableTimeout);
                }
            });
        },
        focusables() {
            let selector = 'a, button, input, textarea, select, details, [tabindex]:not([tabindex=\'-1\'])'

            return [...this.$el.querySelectorAll(selector)]
                .filter(el => !el.hasAttribute('disabled'))
        },
        firstFocusable() {
            return this.focusables()[0]
        },
        lastFocusable() {
            return this.focusables().slice(-1)[0]
        },
        nextFocusable() {
            return this.focusables()[this.nextFocusableIndex()] || this.firstFocusable()
        },
        prevFocusable() {
            return this.focusables()[this.prevFocusableIndex()] || this.lastFocusable()
        },
        nextFocusableIndex() {
            return (this.focusables().indexOf(document.activeElement) + 1) % (this.focusables().length + 1)
        },
        prevFocusableIndex() {
            return Math.max(0, this.focusables().indexOf(document.activeElement)) - 1
        },
        setShowPropertyTo(show) {
            this.show = show;

            if (show) {
                $(document.body).find('.modal').modal('show');
            } else {
                $(document.body).find('.modal').modal('hide');

                setTimeout(() => {
                    this.activeComponent = false;
                    this.$wire.resetState();
                }, 300);
            }
        },
        init() {
            this.modalWidth = this.getActiveComponentModalAttribute('maxWidthClass');

            Livewire.on('closeModal', (force = false, skipPreviousModals = 0, destroySkipped = false) => {
                this.closeModal(force, skipPreviousModals, destroySkipped);
            });

            Livewire.on('activeModalComponentChanged', (id) => {
                this.setActiveModalComponent(id);
            });
        }
    };
}
