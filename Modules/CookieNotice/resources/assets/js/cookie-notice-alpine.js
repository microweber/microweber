export default function cookieNotice(moduleId) {
    return {
        moduleId: moduleId,
        showPanel: true,
        cookies: {},

        init() {

        },






        acceptAll() {
             fetch(route('api.cookie-notice.set-cookie'), {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    id: this.moduleId,
                    choices: Object.fromEntries(
                        Object.keys(this.cookies).map(key => [key, true])
                    )
                })
            }).then(() => {
                this.hide();
            });
        },

        save() {
            this.hide();
        },

        hide() {
            this.showPanel = false;
        },


        togglePanel() {
            this.showPanel = !this.showPanel;
        }
    }
}
