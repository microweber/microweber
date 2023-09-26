



mw.top().app.on('moduleSettings.loaded', (eventData) => {

    console.log('moduleSettings.loaded');
    console.log(eventData);

    mw.top().app.on('moduleSettings.editItemById', (item) => {
        console.log('moduleSettings.itemChanged');
        console.log(item);
    });
    mw.top().app.on('moduleSettings.editItemById', (itemId) => {
        console.log('moduleSettings.editItemById');
        console.log(itemId);
    });


    mw.top().app.on('moduleSettings.mouseoverItemId', (itemId) => {
        console.log('moduleSettings.mouseoverItemId');
        console.log(itemId);
    });


});
