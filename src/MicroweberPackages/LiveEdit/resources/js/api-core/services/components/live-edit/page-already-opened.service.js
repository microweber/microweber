export class MWPageAlreadyOpened{
    constructor() {

        mw.top().app.broadcast.on('refreshFocusedPage', data => {
            if(!data.url ) {
                return;
            }

            if(data.url === mw.top().app.canvas.getUrl()) {
                if(data.skipIdentity && data.skipIdentity.includes(mw.top().app.broadcast.getIdentity())) {
                    return;
                }
                mw.top().app.canvas.refresh();
            }
        })
        mw.top().app.broadcast.on('saveAndGoToAdmin', data => {

            if(!data.url ) {
                return;
            }

            if(data.url === mw.top().app.canvas.getUrl() && mw.top().app.documentFocus.isActive()) {
                mw.top().app.canvas.refresh();
            }
        })
        mw.top().app.broadcast.on('closePage', data => {
            if(!data.url ) {
                return;
            }

            if(data.url === mw.top().app.canvas.getUrl()) {
                if(data.skipIdentity && data.skipIdentity.includes(mw.top().app.broadcast.getIdentity())) {
                    return;
                }
                mw.top().win.location.href = mw.settings.adminUrl;
            }
        })
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
