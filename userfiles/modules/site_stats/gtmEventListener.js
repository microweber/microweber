if (typeof window.dataLayer === 'undefined') {
    dataLayer = window.dataLayer || [];
}

if (typeof mwTrackEventPush === 'undefined') {
    function mwTrackEventPush(dataLayerItem) {
        if (typeof window.dataLayer !== 'undefined') {
            dataLayer.push(dataLayerItem);
        }
    }
}



mw.on('trackEvent', function (dataLayerItem) {
    mwTrackEventPush(dataLayerItem);
});


//
//
// var EventManager = function () {
//     var _e = {};
//     this.on = function (e, f) {
//         e[e] ? e[e].push(f) : (_e[e] = [f])
//     };
//     this.dispatch = function (e, f) {
//         e[e] ? e[e].forEach(function (c) {
//             c.call(this, f);
//         }) : '';
//     };
// };
//
// window.eventManager = new EventManager();

// eventManager.dispatch('orderComplete', {some: 'data'})


/*
$('[data-gtm-click]').on('click', function(event) {
    event.preventDefault();
    var self = this;
    dataLayer.push({
        'event': 'productClick',
        'ecommerce': {
            'click': {
                'products': $(self).data('gtm-product')
            }
        },
        'eventCallback': function() {
            document.location = $(self).attr('href');
        }
    });
});*/


