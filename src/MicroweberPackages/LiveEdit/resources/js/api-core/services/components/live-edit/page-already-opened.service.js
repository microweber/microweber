export class MWPageAlreadyOpened {

    constructor() {

        mw.top().app.broadcast.on('refreshFocusedPage', data => {
            if(!data.url ) {
                return;
            }

            if(data.url === this.getScopeURL()) {
                if(data.skipIdentity && data.skipIdentity.includes(mw.top().app.broadcast.getIdentity())) {
                    return;
                }
                this.scopeRefresh();
            }
        })
        mw.top().app.broadcast.on('saveAndGoToAdmin', data => {

            if(!data.url ) {
                return;
            }

            if(data.url === this.getScopeURL() && mw.top().app.documentFocus.isActive()) {
                this.scopeRefresh();
            }
        })
        mw.top().app.broadcast.on('closePage', data => {
            if(!data.url ) {
                return;
            }

            if(data.url === this.getScopeURL()) {
                if(data.skipIdentity && data.skipIdentity.includes(mw.top().app.broadcast.getIdentity())) {
                    return;
                }
                mw.top().win.location.href = mw.top().settings.adminUrl;
            }
        })
    }

    scopeRefresh() {
        if(mw.top().app && mw.top().app.canvas) {
            return mw.top().app.canvas.refresh();
        }
        return location.reload();
    }

    getScopeURL() {
        if(mw.top().app && mw.top().app.canvas) {
            return mw.top().app.canvas.getUrl();
        }
        return location.href;
    }

    async saveAndBack() {

    }

    async handle(url) {
        return new Promise((resolve) => {
            const ok = mw.element(`
                <button class="btn btn-primary" data-action="save">${mw.lang('Edit here')}</button>
            `);
            const cancel = mw.element(`<button class="btn">${mw.lang('Cancel')}</button>`);

            const dialogContent = `
                <div class="alert alert-warning" role="alert">
                    <div class="d-flex">
                        <div>
                            <h4 class="alert-title">${mw.lang('This page is already opened in another tab')}!</h4>
                        </div>
                    </div>
                </div>

            `;

            const dlg = mw.dialog({
                overlay: true,
                content: dialogContent,
                footer: [cancel.get(0), ok.get(0)],
                id: 'canvasURLAlreadyOpened',
                closeButton: false,
                title: mw.lang('Warning')
            })

            ok.on('click', async function(){
                dlg.remove();
                mw.top().app.broadcast.message('closePage', {url, skipIdentity: [mw.top().app.broadcast.getIdentity()]}); // close the page in the other tab
                resolve(true)
            });
            cancel.on('click', function(){
                dlg.remove();
                resolve(false);

            });
        })
    }
}
