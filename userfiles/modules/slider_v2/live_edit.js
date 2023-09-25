



mw.top().app.on('onModuleSettingsLoaded', (eventData) => {

    console.log('onModuleSettingsLoaded');
    console.log(eventData);

    mw.top().app.on('onItemChanged', (item) => {
        console.log('onItemChanged')
        // alert('onItemChanged')
        console.log(item)
        // this.switchToSlideByItemId(item.itemId);
    });
    mw.top().app.on('editItemById', (itemId) => {
        console.log('editItemById')
        console.log(itemId)
    });


});
