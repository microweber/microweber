(function () {

    var IconLoader = function (store) {
        var scope = this;

        var defaultVersion = '-1';

        var common = {
            'fontAwesome': {
                cssSelector: '.fa',
                detect: function (target) {
                    return target.classList.includes('fa');
                },
                render: function (icon, target) {
                    target.classList.add('fa');
                    target.classList.add(icon);
                },
                remove: function (target) {
                    target.classList.remove('fa');
                    var exception= ['fa-lg', 'fa-2x', 'fa-3x', 'fa-4x', 'fa-5x', 'fa-fw', 'fa-spin', 'fa-pule', 'fa-rotate-90',
                        'fa-rotate-180', 'fa-rotate-270', 'fa-flip-horizontal', 'fa-flip-vertical'];
                    mw.tools.classNamespaceDelete(target, 'fa-', undefined, undefined, exception);
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        const icons = ['0','1','2','3','4','5','500px','6','7','8','9','a','address-book','address-card','adjust','adn','align-center','align-justify','align-left','align-right','amazon','ambulance','anchor','anchor-circle-check','anchor-circle-exclamation','anchor-circle-xmark','anchor-lock','android','angellist','angle-double-down','angle-double-left','angle-double-right','angle-double-up','angle-down','angle-left','angle-right','angle-up','angles-down','angles-left','angles-right','angles-up','ankh','apple','apple-whole','archive','archway','area-chart','arrow-circle-down','arrow-circle-left','arrow-circle-o-down','arrow-circle-o-left','arrow-circle-o-right','arrow-circle-o-up','arrow-circle-right','arrow-circle-up','arrow-down','arrow-down-1-9','arrow-down-9-1','arrow-down-a-z','arrow-down-long','arrow-down-short-wide','arrow-down-up-across-line','arrow-down-up-lock','arrow-down-wide-short','arrow-down-z-a','arrow-left','arrow-left-long','arrow-pointer','arrow-right','arrow-right-arrow-left','arrow-right-from-bracket','arrow-right-long','arrow-right-to-bracket','arrow-right-to-city','arrow-rotate-left','arrow-rotate-right','arrow-trend-down','arrow-trend-up','arrow-turn-down','arrow-turn-up','arrow-up','arrow-up-1-9','arrow-up-9-1','arrow-up-a-z','arrow-up-from-bracket','arrow-up-from-ground-water','arrow-up-from-water-pump','arrow-up-long','arrow-up-right-dots','arrow-up-right-from-square','arrow-up-short-wide','arrow-up-wide-short','arrow-up-z-a','arrows','arrows-alt','arrows-down-to-line','arrows-down-to-people','arrows-h','arrows-left-right','arrows-left-right-to-line','arrows-rotate','arrows-spin','arrows-split-up-and-left','arrows-to-circle','arrows-to-dot','arrows-to-eye','arrows-turn-right','arrows-turn-to-dots','arrows-up-down','arrows-up-down-left-right','arrows-up-to-line','arrows-v','asterisk','at','atom','audio-description','austral-sign','award','b','baby','baby-carriage','backward','backward-fast','backward-step','bacon','bacteria','bacterium','bag-shopping','bahai','baht-sign','balance-scale','ban','ban-smoking','bandage','bangladeshi-taka-sign','bar-chart','barcode','bars','bars-progress','bars-staggered','baseball','baseball-bat-ball','basket-shopping','basketball','bath','battery-empty','battery-full','battery-half','battery-quarter','battery-three-quarters','bed','bed-pulse','beer','beer-mug-empty','behance','behance-square','bell','bell-concierge','bell-o','bell-slash','bell-slash-o','bezier-curve','bicycle','binoculars','biohazard','birthday-cake','bitbucket','bitbucket-square','bitcoin-sign','black-tie','blender','blender-phone','blog','bold','bolt','bolt-lightning','bomb','bone','bong','book','book-atlas','book-bible','book-bookmark','book-journal-whills','book-medical','book-open','book-open-reader','book-quran','book-skull','book-tanakh','bookmark','bookmark-o','border-all','border-none','border-top-left','bore-hole','bottle-droplet','bottle-water','bowl-food','bowl-rice','bowling-ball','box','box-archive','box-open','box-tissue','boxes-packing','boxes-stacked','braille','brain','brazilian-real-sign','bread-slice','bridge','bridge-circle-check','bridge-circle-exclamation','bridge-circle-xmark','bridge-lock','bridge-water','briefcase','briefcase-medical','broom','broom-ball','brush','btc','bucket','bug','bug-slash','bugs','building','building-circle-arrow-right','building-circle-check','building-circle-exclamation','building-circle-xmark','building-columns','building-flag','building-lock','building-ngo','building-o','building-shield','building-un','building-user','building-wheat','bullhorn','bullseye','burger','burst','bus','bus-simple','business-time','buysellads','c','cable-car','cake-candles','calculator','calendar','calendar-check','calendar-check-o','calendar-day','calendar-days','calendar-minus','calendar-minus-o','calendar-o','calendar-plus','calendar-plus-o','calendar-times-o','calendar-week','calendar-xmark','camera','camera-retro','camera-rotate','campground','candy-cane','cannabis','capsules','car','car-battery','car-burst','car-on','car-rear','car-side','car-tunnel','caravan','caret-down','caret-left','caret-right','caret-square-o-down','caret-square-o-left','caret-square-o-right','caret-square-o-up','caret-up','carrot','cart-arrow-down','cart-flatbed','cart-flatbed-suitcase','cart-plus','cart-shopping','cash-register','cat','cc','cc-amex','cc-diners-club','cc-discover','cc-jcb','cc-mastercard','cc-paypal','cc-stripe','cc-visa','cedi-sign','cent-sign','certificate','chain-broken','chair','chalkboard','chalkboard-user','champagne-glasses','charging-station','chart-area','chart-bar','chart-column','chart-diagram','chart-gantt','chart-line','chart-pie','chart-simple','check','check-circle','check-circle-o','check-double','check-square','check-square-o','check-to-slot','cheese','chess','chess-bishop','chess-board','chess-king','chess-knight','chess-pawn','chess-queen','chess-rook','chevron-circle-down','chevron-circle-left','chevron-circle-right','chevron-circle-up','chevron-down','chevron-left','chevron-right','chevron-up','child','child-combatant','child-dress','child-reaching','children','chrome','church','circle','circle-arrow-down','circle-arrow-left','circle-arrow-right','circle-arrow-up','circle-check','circle-chevron-down','circle-chevron-left','circle-chevron-right','circle-chevron-up','circle-dollar-to-slot','circle-dot','circle-down','circle-exclamation','circle-h','circle-half-stroke','circle-info','circle-left','circle-minus','circle-nodes','circle-notch','circle-o','circle-o-notch','circle-pause','circle-play','circle-plus','circle-question','circle-radiation','circle-right','circle-stop','circle-thin','circle-up','circle-user','circle-xmark','city','clapperboard','clipboard','clipboard-check','clipboard-list','clipboard-question','clipboard-user','clock','clock-o','clock-rotate-left','clone','closed-captioning','cloud','cloud-arrow-down','cloud-arrow-up','cloud-bolt','cloud-download','cloud-meatball','cloud-moon','cloud-moon-rain','cloud-rain','cloud-showers-heavy','cloud-showers-water','cloud-sun','cloud-sun-rain','cloud-upload','clover','code','code-branch','code-commit','code-compare','code-fork','code-merge','code-pull-request','codepen','coffee','cog','cogs','coins','colon-sign','columns','comment','comment-dollar','comment-dots','comment-medical','comment-nodes','comment-o','comment-slash','comment-sms','commenting','commenting-o','comments','comments-dollar','comments-o','compact-disc','compass','compass-drafting','compress','computer','computer-mouse','connectdevelop','contao','cookie','cookie-bite','copy','copyright','couch','cow','creative-commons','credit-card','crop','crop-simple','cross','crosshairs','crow','crown','crutch','cruzeiro-sign','css3','cube','cubes','cubes-stacked','cutlery','d','dashcube','database','delete-left','delicious','democrat','desktop','deviantart','dharmachakra','diagram-next','diagram-predecessor','diagram-project','diagram-successor','diamond','diamond-turn-right','dice','dice-d20','dice-d6','dice-five','dice-four','dice-one','dice-six','dice-three','dice-two','digg','disease','display','divide','dna','dog','dollar-sign','dolly','dong-sign','door-closed','door-open','dot-circle-o','dove','down-left-and-up-right-to-center','down-long','download','dragon','draw-polygon','dribbble','dropbox','droplet','droplet-slash','drum','drum-steelpan','drumstick-bite','drupal','dumbbell','dumpster','dumpster-fire','dungeon','e','ear-deaf','ear-listen','earth-africa','earth-americas','earth-asia','earth-europe','earth-oceania','egg','eject','elevator','ellipsis','ellipsis-h','ellipsis-v','ellipsis-vertical','empire','envelope','envelope-circle-check','envelope-o','envelope-open','envelope-open-text','envelope-square','envelopes-bulk','equals','eraser','ethernet','eur','euro-sign','exchange','exclamation','exclamation-circle','exclamation-triangle','expand','expeditedssl','explosion','external-link','external-link-square','eye','eye-dropper','eye-low-vision','eye-slash','eyedropper','f','face-angry','face-dizzy','face-flushed','face-frown','face-frown-open','face-grimace','face-grin','face-grin-beam','face-grin-beam-sweat','face-grin-hearts','face-grin-squint','face-grin-squint-tears','face-grin-stars','face-grin-tears','face-grin-tongue','face-grin-tongue-squint','face-grin-tongue-wink','face-grin-wide','face-grin-wink','face-kiss','face-kiss-beam','face-kiss-wink-heart','face-laugh','face-laugh-beam','face-laugh-squint','face-laugh-wink','face-meh','face-meh-blank','face-rolling-eyes','face-sad-cry','face-sad-tear','face-smile','face-smile-beam','face-smile-wink','face-surprise','face-tired','facebook','facebook-official','facebook-square','fan','fast-backward','fast-forward','faucet','faucet-drip','fax','feather','feather-pointed','female','ferry','fighter-jet','file','file-archive-o','file-arrow-down','file-arrow-up','file-audio','file-audio-o','file-circle-check','file-circle-exclamation','file-circle-minus','file-circle-plus','file-circle-question','file-circle-xmark','file-code','file-code-o','file-contract','file-csv','file-excel','file-excel-o','file-export','file-fragment','file-half-dashed','file-image','file-image-o','file-import','file-invoice','file-invoice-dollar','file-lines','file-medical','file-o','file-pdf','file-pdf-o','file-pen','file-powerpoint','file-powerpoint-o','file-prescription','file-shield','file-signature','file-text','file-text-o','file-video','file-video-o','file-waveform','file-word','file-word-o','file-zipper','files-o','fill','fill-drip','film','filter','filter-circle-dollar','filter-circle-xmark','fingerprint','fire','fire-burner','fire-extinguisher','fire-flame-curved','fire-flame-simple','firefox','fish','fish-fins','flag','flag-checkered','flag-o','flag-usa','flask','flask-vial','flickr','floppy-disk','floppy-o','florin-sign','folder','folder-closed','folder-minus','folder-o','folder-open','folder-open-o','folder-plus','folder-tree','font','font-awesome','fonticons','football','forumbee','forward','forward-fast','forward-step','foursquare','franc-sign','frog','frown-o','futbol','futbol-o','g','gamepad','gas-pump','gauge','gauge-high','gauge-simple','gauge-simple-high','gavel','gbp','gear','gears','gem','genderless','get-pocket','gg','gg-circle','ghost','gift','gifts','git','git-square','github','github-alt','github-square','glass','glass-water','glass-water-droplet','glasses','globe','golf-ball-tee','google','google-plus','google-plus-square','google-wallet','gopuram','graduation-cap','gratipay','greater-than','greater-than-equal','grip','grip-lines','grip-lines-vertical','grip-vertical','group-arrows-rotate','guarani-sign','guitar','gun','h','h-square','hacker-news','hammer','hamsa','hand','hand-back-fist','hand-dots','hand-fist','hand-holding','hand-holding-dollar','hand-holding-droplet','hand-holding-hand','hand-holding-heart','hand-holding-medical','hand-lizard','hand-lizard-o','hand-middle-finger','hand-o-down','hand-o-left','hand-o-right','hand-o-up','hand-paper-o','hand-peace','hand-peace-o','hand-point-down','hand-point-left','hand-point-right','hand-point-up','hand-pointer','hand-pointer-o','hand-rock-o','hand-scissors','hand-scissors-o','hand-sparkles','hand-spock','hand-spock-o','handcuffs','hands','hands-asl-interpreting','hands-bound','hands-bubbles','hands-clapping','hands-holding','hands-holding-child','hands-holding-circle','hands-praying','handshake','handshake-angle','handshake-simple','handshake-simple-slash','handshake-slash','hanukiah','hard-drive','hashtag','hat-cowboy','hat-cowboy-side','hat-wizard','hdd-o','head-side-cough','head-side-cough-slash','head-side-mask','head-side-virus','header','heading','headphones','headphones-simple','headset','heart','heart-circle-bolt','heart-circle-check','heart-circle-exclamation','heart-circle-minus','heart-circle-plus','heart-circle-xmark','heart-crack','heart-o','heart-pulse','heartbeat','helicopter','helicopter-symbol','helmet-safety','helmet-un','hexagon-nodes','hexagon-nodes-bolt','highlighter','hill-avalanche','hill-rockslide','hippo','history','hockey-puck','holly-berry','home','horse','horse-head','hospital','hospital-o','hospital-user','hot-tub-person','hotdog','hotel','hourglass','hourglass-end','hourglass-half','hourglass-o','hourglass-start','house','house-chimney','house-chimney-crack','house-chimney-medical','house-chimney-user','house-chimney-window','house-circle-check','house-circle-exclamation','house-circle-xmark','house-crack','house-fire','house-flag','house-flood-water','house-flood-water-circle-arrow-right','house-laptop','house-lock','house-medical','house-medical-circle-check','house-medical-circle-exclamation','house-medical-circle-xmark','house-medical-flag','house-signal','house-tsunami','house-user','houzz','hryvnia-sign','html5','hurricane','i','i-cursor','ice-cream','icicles','icons','id-badge','id-card','id-card-clip','igloo','ils','image','image-portrait','images','inbox','indent','indian-rupee-sign','industry','infinity','info','info-circle','inr','instagram','internet-explorer','ioxhost','italic','j','jar','jar-wheat','jedi','jet-fighter','jet-fighter-up','joint','joomla','jpy','jsfiddle','jug-detergent','k','kaaba','key','keyboard','keyboard-o','khanda','kip-sign','kit-medical','kitchen-set','kiwi-bird','krw','l','land-mine-on','landmark','landmark-dome','landmark-flag','language','laptop','laptop-code','laptop-file','laptop-medical','lari-sign','lastfm','lastfm-square','layer-group','leaf','leanpub','left-long','left-right','lemon','lemon-o','less-than','less-than-equal','level-down','level-up','life-ring','lightbulb','lightbulb-o','line-chart','lines-leaning','link','link-slash','linkedin','linkedin-square','linux','lira-sign','list','list-alt','list-check','list-ol','list-ul','litecoin-sign','location-arrow','location-crosshairs','location-dot','location-pin','location-pin-lock','lock','lock-open','locust','long-arrow-down','long-arrow-left','long-arrow-right','long-arrow-up','lungs','lungs-virus','m','magic','magnet','magnifying-glass','magnifying-glass-arrow-right','magnifying-glass-chart','magnifying-glass-dollar','magnifying-glass-location','magnifying-glass-minus','magnifying-glass-plus','male','manat-sign','map','map-location','map-location-dot','map-marker','map-o','map-pin','map-signs','marker','mars','mars-and-venus','mars-and-venus-burst','mars-double','mars-stroke','mars-stroke-h','mars-stroke-right','mars-stroke-up','mars-stroke-v','martini-glass','martini-glass-citrus','martini-glass-empty','mask','mask-face','mask-ventilator','masks-theater','mattress-pillow','maxcdn','maximize','meanpath','medal','medium','medkit','meh-o','memory','menorah','mercury','message','meteor','microchip','microphone','microphone-lines','microphone-lines-slash','microphone-slash','microscope','mill-sign','minimize','minus','minus-circle','minus-square','minus-square-o','mitten','mobile','mobile-button','mobile-retro','mobile-screen','mobile-screen-button','money','money-bill','money-bill-1','money-bill-1-wave','money-bill-transfer','money-bill-trend-up','money-bill-wave','money-bill-wheat','money-bills','money-check','money-check-dollar','monument','moon','moon-o','mortar-pestle','mosque','mosquito','mosquito-net','motorcycle','mound','mountain','mountain-city','mountain-sun','mouse-pointer','mug-hot','mug-saucer','music','n','naira-sign','network-wired','neuter','newspaper','newspaper-o','not-equal','notdef','note-sticky','notes-medical','o','object-group','object-ungroup','odnoklassniki','odnoklassniki-square','oil-can','oil-well','om','opencart','openid','opera','optin-monster','otter','outdent','p','pagelines','pager','paint-brush','paint-roller','paintbrush','palette','pallet','panorama','paper-plane','paper-plane-o','paperclip','parachute-box','paragraph','passport','paste','pause','paw','paypal','peace','pen','pen-clip','pen-fancy','pen-nib','pen-ruler','pen-to-square','pencil','pencil-square','pencil-square-o','people-arrows','people-carry-box','people-group','people-line','people-pulling','people-robbery','people-roof','pepper-hot','percent','person','person-arrow-down-to-line','person-arrow-up-from-line','person-biking','person-booth','person-breastfeeding','person-burst','person-cane','person-chalkboard','person-circle-check','person-circle-exclamation','person-circle-minus','person-circle-plus','person-circle-question','person-circle-xmark','person-digging','person-dots-from-line','person-dress','person-dress-burst','person-drowning','person-falling','person-falling-burst','person-half-dress','person-harassing','person-hiking','person-military-pointing','person-military-rifle','person-military-to-person','person-praying','person-pregnant','person-rays','person-rifle','person-running','person-shelter','person-skating','person-skiing','person-skiing-nordic','person-snowboarding','person-swimming','person-through-window','person-walking','person-walking-arrow-loop-left','person-walking-arrow-right','person-walking-dashed-line-arrow-right','person-walking-luggage','person-walking-with-cane','peseta-sign','peso-sign','phone','phone-flip','phone-slash','phone-square','phone-volume','photo-film','picture-o','pie-chart','pied-piper','pied-piper-alt','piggy-bank','pills','pinterest','pinterest-p','pinterest-square','pizza-slice','place-of-worship','plane','plane-arrival','plane-circle-check','plane-circle-exclamation','plane-circle-xmark','plane-departure','plane-lock','plane-slash','plane-up','plant-wilt','plate-wheat','play','play-circle','play-circle-o','plug','plug-circle-bolt','plug-circle-check','plug-circle-exclamation','plug-circle-minus','plug-circle-plus','plug-circle-xmark','plus','plus-circle','plus-minus','plus-square','plus-square-o','podcast','poo','poo-storm','poop','power-off','prescription','prescription-bottle','prescription-bottle-medical','print','pump-medical','pump-soap','puzzle-piece','q','qq','qrcode','question','question-circle','quote-left','quote-right','r','radiation','radio','rainbow','random','ranking-star','rebel','receipt','record-vinyl','rectangle-ad','rectangle-list','rectangle-xmark','recycle','reddit','reddit-square','refresh','registered','renren','repeat','reply','reply-all','republican','restroom','retweet','ribbon','right-from-bracket','right-left','right-long','right-to-bracket','ring','road','road-barrier','road-bridge','road-circle-check','road-circle-exclamation','road-circle-xmark','road-lock','road-spikes','robot','rocket','rotate','rotate-left','rotate-right','route','rss','rss-square','rub','ruble-sign','rug','ruler','ruler-combined','ruler-horizontal','ruler-vertical','rupee-sign','rupiah-sign','s','sack-dollar','sack-xmark','safari','sailboat','satellite','satellite-dish','scale-balanced','scale-unbalanced','scale-unbalanced-flip','school','school-circle-check','school-circle-exclamation','school-circle-xmark','school-flag','school-lock','scissors','screwdriver','screwdriver-wrench','scroll','scroll-torah','sd-card','search','search-minus','search-plus','section','seedling','sellsy','server','shapes','share','share-alt','share-alt-square','share-from-square','share-nodes','share-square','share-square-o','sheet-plastic','shekel-sign','shield','shield-cat','shield-dog','shield-halved','shield-heart','shield-virus','ship','shirt','shirtsinbulk','shoe-prints','shop','shop-lock','shop-slash','shopping-cart','shower','shrimp','shuffle','shuttle-space','sign-hanging','sign-in','sign-out','signal','signature','signs-post','sim-card','simplybuilt','sink','sitemap','skull','skull-crossbones','skyatlas','skype','slack','slash','sleigh','sliders','slideshare','smile-o','smog','smoking','snowflake','snowman','snowplow','soap','socks','solar-panel','sort','sort-alpha-asc','sort-alpha-desc','sort-amount-asc','sort-amount-desc','sort-asc','sort-desc','sort-down','sort-numeric-asc','sort-numeric-desc','sort-up','soundcloud','spa','space-shuttle','spaghetti-monster-flying','spell-check','spider','spinner','splotch','spoon','spotify','spray-can','spray-can-sparkles','square','square-arrow-up-right','square-binary','square-caret-down','square-caret-left','square-caret-right','square-caret-up','square-check','square-envelope','square-full','square-h','square-minus','square-nfi','square-o','square-parking','square-pen','square-person-confined','square-phone','square-phone-flip','square-plus','square-poll-horizontal','square-poll-vertical','square-root-variable','square-rss','square-share-nodes','square-up-right','square-virus','square-xmark','stack-exchange','stack-overflow','staff-snake','stairs','stamp','stapler','star','star-and-crescent','star-half','star-half-o','star-half-stroke','star-o','star-of-david','star-of-life','steam','steam-square','step-backward','step-forward','sterling-sign','stethoscope','sticky-note','sticky-note-o','stop','stopwatch','stopwatch-20','store','store-slash','street-view','strikethrough','stroopwafel','stumbleupon','stumbleupon-circle','subscript','subway','suitcase','suitcase-medical','suitcase-rolling','sun','sun-o','sun-plant-wilt','superscript','swatchbook','synagogue','syringe','t','table','table-cells','table-cells-column-lock','table-cells-large','table-cells-row-lock','table-cells-row-unlock','table-columns','table-list','table-tennis-paddle-ball','tablet','tablet-button','tablet-screen-button','tablets','tachograph-digital','tachometer','tag','tags','tape','tarp','tarp-droplet','tasks','taxi','teeth','teeth-open','television','temperature-arrow-down','temperature-arrow-up','temperature-empty','temperature-full','temperature-half','temperature-high','temperature-low','temperature-quarter','temperature-three-quarters','tencent-weibo','tenge-sign','tent','tent-arrow-down-to-line','tent-arrow-left-right','tent-arrow-turn-left','tent-arrows-down','tents','terminal','text-height','text-slash','text-width','th','th-large','th-list','thermometer','thumb-tack','thumbs-down','thumbs-o-down','thumbs-o-up','thumbs-up','thumbtack','thumbtack-slash','ticket','ticket-simple','timeline','times','times-circle','times-circle-o','tint','toggle-off','toggle-on','toilet','toilet-paper','toilet-paper-slash','toilet-portable','toilets-portable','toolbox','tooth','torii-gate','tornado','tower-broadcast','tower-cell','tower-observation','tractor','trademark','traffic-light','trailer','train','train-subway','train-tram','transgender','transgender-alt','trash','trash-arrow-up','trash-can','trash-can-arrow-up','trash-o','tree','tree-city','trello','triangle-exclamation','tripadvisor','trophy','trowel','trowel-bricks','truck','truck-arrow-right','truck-droplet','truck-fast','truck-field','truck-field-un','truck-front','truck-medical','truck-monster','truck-moving','truck-pickup','truck-plane','truck-ramp-box','try','tty','tumblr','tumblr-square','turkish-lira-sign','turn-down','turn-up','tv','twitch','twitter','twitter-square','u','umbrella','umbrella-beach','underline','undo','universal-access','university','unlock','unlock-alt','unlock-keyhole','up-down','up-down-left-right','up-long','up-right-and-down-left-from-center','up-right-from-square','upload','usd','user','user-astronaut','user-check','user-clock','user-doctor','user-gear','user-graduate','user-group','user-injured','user-large','user-large-slash','user-lock','user-md','user-minus','user-ninja','user-nurse','user-pen','user-plus','user-secret','user-shield','user-slash','user-tag','user-tie','user-times','user-xmark','users','users-between-lines','users-gear','users-line','users-rays','users-rectangle','users-slash','users-viewfinder','utensils','v','van-shuttle','vault','vector-square','venus','venus-double','venus-mars','vest','vest-patches','viacoin','vial','vial-circle-check','vial-virus','vials','video','video-camera','video-slash','vihara','vimeo','vimeo-square','vine','virus','virus-covid','virus-covid-slash','virus-slash','viruses','vk','voicemail','volcano','volleyball','volume-down','volume-high','volume-low','volume-off','volume-up','volume-xmark','vr-cardboard','w','walkie-talkie','wallet','wand-magic','wand-magic-sparkles','wand-sparkles','warehouse','water','water-ladder','wave-square','web-awesome','weibo','weight-hanging','weight-scale','weixin','whatsapp','wheat-awn','wheat-awn-circle-exclamation','wheelchair','wheelchair-move','whiskey-glass','wifi','wikipedia-w','wind','window-maximize','window-minimize','window-restore','windows','wine-bottle','wine-glass','wine-glass-empty','won-sign','wordpress','worm','wrench','x','x-ray','xing','xing-square','xmark','xmarks-lines','y','y-combinator','yahoo','yelp','yen-sign','yin-yang','youtube','youtube-play','youtube-square','z'];
                        resolve(icons.map(i => `fa-${i}`));
                    });
                },
                name: 'Font Awesome',
                load:  mw.settings.libs_url + 'font-awesome/font-awesome.css',
                unload: function () {
                    document.querySelector('link[href*="font-awesome"]').remove();
                },
                version: '4.7.0'
            },
            'materialIcons': {
                cssSelector: '.material-icons',
                detect: function (target) {
                    return target.classList.contains('material-icons');
                },
                render: function (icon, target) {
                    target.classList.add('material-icons');
                    target.innerHTML = (icon);
                },
                remove: function (target) {
                    mw.tools.removeClass(target, 'material-icons');
                    target.innerHTML = '';
                 },
                icons: function () {
                    return new Promise(function (resolve) {
                        $.get(mw.settings.modules_url + 'microweber/api/material.icons.js',function (data) {
                            resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Material Icons',
                load: mw.settings.libs_url + 'material_icons' + '/material_icons.css',
                unload: function () {
                    top.document.querySelector('link[href*="material_icons.css"]').remove();
                },
                version: 'mw'
            },
            'iconsMindLine': {
                cssSelector: '[class*="mw-micon-"]:not([class*="mw-micon-solid-"])',
                detect: function (target) {
                    return target.className.includes('mw-micon-') && !target.className.includes('mw-micon-solid-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {

                    mw.tools.classNamespaceDelete(target, 'mw-micon-', undefined, undefined, []);
                },
                icons: function () {
                    var scope = this;
                    var parse = function (cssLink) {
                        if(!cssLink.sheet){
                            return;
                        }
                        var icons = cssLink.sheet.cssRules;
                         var l = icons.length, i = 0, mindIcons = [];
                         for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mw-micon-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mindIcons.push(cls);
                            }
                        }
                        return mindIcons
                    };
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="mw-icons-mind/line"]');
                        if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link) {
                            resolve(parse(link));
                        });
                    });
                },
                name: 'Icons Mind Line',

                load:  mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mw-icons-mind/line/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/line/style"]').remove();
                },
                version: 'mw_local'
            },
            'iconsMindSolid': {
                cssSelector: '[class*="mw-micon-solid-"]',
                detect: function (target) {
                    return target.className.includes('mw-micon-solid-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-micon-solid-', undefined, undefined, []);
                },
                icons: function () {
                    var scope = this;
                    var parse = function (cssLink) {
                        if(!cssLink.sheet){
                            return;
                        }
                        var icons = cssLink.sheet.cssRules;
                        var l = icons.length, i = 0, mindIcons = [];
                        for (; i < l; i++) {
                            var sel = icons[i].selectorText;
                            if (!!sel && sel.indexOf('.mw-micon-solid-') === 0) {
                                var cls = sel.replace(".", '').split(':')[0];
                                mindIcons.push(cls);
                            }
                        }
                        return mindIcons
                    };
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="mw-icons-mind/solid"]');
                         if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link) {
                            resolve(parse(link));
                        });
                    });
                },
                name: 'Icons Mind Solid',

                load:  mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mw-icons-mind/solid/style.css',
                unload: function () {
                    document.querySelector('link[href*="mw-icons-mind/solid/style"]').remove();
                },
                version: 'mw_local'
            },

            'materialDesignIcons': {
                cssSelector: '.mdi',
                detect: function (target) {
                    return target.classList.includes('mdi');
                },
                render: function (icon, target) {
                    target.classList.add('mdi');
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mdi-', undefined, undefined, []);
                    target.classList.remove('mdi');
                },
                icons: function () {
                    var scope = this;
                    var load = function (cb) {
                        var cssLink = mw.top().win.document.querySelector('link[href*="materialdesignicons"]');
                        if(cssLink) {
                            cb.call(undefined, cssLink);
                        }  else {
                            $.get(scope.load, function (data) {
                                cssLink = document.createElement('link');
                                cssLink.type = 'text/css';
                                cssLink.rel = 'stylesheet';
                                cssLink.href = scope.load;
                                $(document.head).append(cssLink);
                                cb.call(undefined, cssLink);
                            });
                        }
                    };
                    return new Promise(function (resolve) {
                        load(function (link){
                            if(!link || !link.sheet) {
                                resolve([]);
                                return;
                            }
                            var icons = link.sheet.cssRules;
                            var l = icons.length, i = 0, mdiIcons = [];
                            for (; i < l; i++) {
                                var sel = icons[i].selectorText;
                                if (!!sel && sel.indexOf('.mdi-') === 0) {
                                    var cls = sel.replace(".", '').split(':')[0];
                                    mdiIcons.push(cls);
                                }
                            }
                            resolve(mdiIcons);
                        });
                    });
                },
                name: 'Material Design Icons',
                // load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                load:  mw.settings.site_url + 'vendor/microweber-packages/frontend-assets-libs/mdi/css/materialdesignicons.css',
                unload: function () {
                    document.querySelector('link[href*="materialdesignicons"]').remove();
                },
                version: 'mw_local'
            },
            'mwIcons': {
                cssSelector: '[class*="mw-icon-"]',
                detect: function (target) {
                    return target.className.includes('mw-icon-');
                },
                render: function (icon, target) {
                    target.classList.add(icon);
                },
                remove: function (target) {
                    mw.tools.classNamespaceDelete(target, 'mw-icon-', undefined, undefined, []);
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        $.get(mw.settings.modules_url + 'microweber/api/microweber.icons.js',function (data) {
                            resolve(JSON.parse(data));
                        });
                    });
                },
                name: 'Microweber Icons',
                load:  mw.settings.modules_url + 'microweber/css/fonts/materialdesignicons/css/materialdesignicons.min.css',
                unload: function () {
                    document.querySelector('link[href*="materialdesignicons"]').remove();
                },
                version: 'mw_local'
            },
            SVGIcons: {
                cssSelector: 'svg[viewBox]',
                detect: function (target) {
                    return target.nodeName === 'SVG'
                },
                render: function (icon, target) {
                     target.innerHTML = icon.source;
                     var svg = target.querySelector('svg');
                     if (svg) {
                         svg.setAttribute('width', '1em');
                         svg.setAttribute('fill', 'currentColor');
                         svg.setAttribute('height', '1em');
                         svg.style.width = '1em';
                         svg.style.height = '1em';
                         svg.style.fill = 'currentColor';
                     }
                },
                remove: function (target) {
                    target.innerHTML = ''
                },
                icons: function () {
                    return new Promise(function (resolve) {
                        if(window.TemplateVectorIcons) {
                            resolve(TemplateVectorIcons)
                        } else {
                            $.getScript(mw.settings.template_url + 'template_icons.js', function (){
                                resolve(TemplateVectorIcons)
                            })
                        }

                    });
                },
                name: 'Vector Icons',
                load:  null,
                unload: function () {

                },
                version: 'mw_local'
            },
        };

        var storage = function () {
            if(!mw.top().__IconStorage) {
                mw.top().__IconStorage = [];
            }
            return mw.top().__IconStorage;
        };

        this.storage = store || storage;


        var iconSetKey = function (options) {
            return options.name + options.version;
        };

        var iconSetPush = function (options) {
            if(!storage().find(function (a) {return iconSetKey(options) === iconSetKey(a); })) {
                return storage().push(options);
            }
            return false;
        };

        var addFontIconSet = function (options) {
            options.version = options.version || defaultVersion;
            iconSetPush(options);

            if (typeof options.load === 'string') {
                mw.require(options.load);
                if(mw !== mw.top()) {
                    mw.top().require(options.load);
                }
            } else if (typeof options.load === 'function') {
                options.load();
            }
        };
        var addIconSet = function (conf) {

            if(typeof conf === 'string') {
                if (common[conf]) {
                    conf = common[conf];
                } else {
                    console.warn(conf + ' is not defined.');
                    return;
                }
            }
             if(!conf) return;
            conf.type = conf.type || 'font';
            if (conf.type === 'font') {
                return addFontIconSet(conf);
            }
        };

        this.addIconSet = function (conf) {
            addIconSet(conf);
            return this;
        };

        this.removeIconSet = function (name, version) {
            var str = storage();
            var item = str.find(function (a) { return a.name === name && (!version || a.version === version); });
            if (item) {
                if (item.unload) {
                    item.unload();
                }
                str.splice(str.indexOf(item), 1);
            }
        };


        this.init = function () {
            storage().forEach(function (iconSet){
                scope.addIconSet(iconSet);
            });
        };

    };

    mw.iconLoader = function (options) {
        return new IconLoader(options);
    };


})();


(function (){

    var IconPicker = function (options) {
        options = options || {};
        var loader = mw.iconLoader();
        var defaults = {
            iconsPerPage: 40,
            iconOptions: {
                size: true,
                color: true,
                reset: false
            }
        };


        this.settings = mw.object.extend(true, {}, defaults, options);

        var scope = this;
        var tabAccordionBuilder = function (items) {
            var res = {root: mw.element('<div class="mw-tab-accordion" data-options="tabsSize: medium, tabsColor: tab" />'), items: []};
            items.forEach(function (item){
                var el = mw.element('<div class="mw-accordion-item px-3" />');
                var content = mw.element('<div class="mw-accordion-content px-1">' +(item.content || '') +'</div>');
                var title = mw.element('<div class="mw-ui-box-header mw-accordion-title">' + item.title +'</div>');
                el.append(title);
                el.append(content);
                content.css({
                    overflowX: 'hidden'
                })

                res.root.append(el);
                res.items.push({
                    title: title,
                    content: content,
                    root: el,
                });
            });
            setTimeout(function (){
                if(mw.components) {
                    mw.components._init();
                }
            }, 10);
            return res;
        };

        var createUI = function () {
            var root = mw.element({
                props: { className: 'mw-icon-selector-root mx-auto w-100 my-3' }
            });
            var iconsBlockHolder, tabs, optionsHolder, iconsHolder;
            if(scope.settings.iconOptions) {
                tabs = tabAccordionBuilder([
                    {title: 'Icons'},
                    {title: 'Options'},

                ]);
                iconsBlockHolder = tabs.items[0].content;
                optionsHolder = tabs.items[1].content;
                root.append(tabs.root)
            } else {
                iconsBlockHolder = mw.element().addClass('px-3');
                root.append(iconsBlockHolder);
            }
            iconsHolder = mw.element().addClass('mw-icon-picker-icons-holder');
            iconsBlockHolder.append(iconsHolder);
            return {
                root: root,
                tabs: tabs,
                iconsBlockHolder: iconsBlockHolder,
                iconsHolder: iconsHolder,
                optionsHolder: optionsHolder
            };
        };


        var _e = {};


        this.on = function (e, f) { _e[e] ? _e[e].push(f) : (_e[e] = [f]) };
        this.dispatch = function (e, f) { _e[e] ? _e[e].forEach(function (c){ c.call(this, f); }) : ''; };

        var actionNodes = {};

        var createOptions = function (holder) {



            if(holder && scope.settings.iconOptions) {
                if(scope.settings.iconOptions.size) {




                    var currentSize = 20;

                    if(scope.settings.target) {
                        currentSize = parseFloat(getComputedStyle(scope.settings.target).fontSize)
                    }


                    holder.html( `
                        <div class="mw-icon-selector-flex my-2">
                            <label class="mw-icon-selector-control-label live-edit-label px-0">${mw.lang('Size')}</label>
                        </div>
                        <div class="mwiconlist-settings-section-block-item input-group input-group-flat" >
                            <input class="mw-icon-selector-form-control-range" value="${currentSize}" type="range" min="8" max="200">
                            <input class="form-control" value="${currentSize}" type="number" min="8" max="200">
                            <span class="input-group-text">
                                <kbd>px</kbd>
                            </span>
                        </div>
                    `);


                    var sizeinput = mw.element(`.form-control`, holder);
                    var sizeinput2 = mw.element(`.mw-icon-selector-form-control-range`, holder);

                    actionNodes.size = sizeinput;
                    sizeinput.on('input', function () {
                        scope.dispatch('sizeChange', sizeinput.get(0).value);
                        sizeinput2.val(sizeinput.get(0).value);
                    });
                    sizeinput2.on('input', function () {
                        sizeinput.val(sizeinput2.get(0).value);
                        scope.dispatch('sizeChange', sizeinput.get(0).value);
                    });


                }
                if(scope.settings.iconOptions.color) {
                    var cel = mw.element(`
                        <div class="mwiconlist-settings-section-block-item">
                            <label class="mw-icon-selector-control-label live-edit-label px-0 mb-2 ps-2">Choose color</label>
                            <button class="mw-icon-selector-form-control mw-icon-selector-2-column" type="button" style="

                                width: 30px;
                                height: 30px;
                                border-radius: 30px;
                                background: transparent;
                                font-size: 0px;
                                cursor: pointer;
                                border: 1px solid rgb(202, 202, 202);
                                outline: none;

                            "></button>
                        </div>
                    `);
                    var cinput = mw.element('button', cel);
                    actionNodes.color = cinput;


                     cel.append(cinput);

                     cinput.on('click', (e) => {
                        const dlg = mw.dialog({
                            width: 260,
                            overlay: 'rgba(0,0,0,.2)',
                            overlayClose: true
                        })
                        var cpOptions = {

                            element: dlg.dialogContainer,
                            position: 'bottom-center',
                            onchange: function (color) {
                                scope.dispatch('colorChange', color);
                                cinput.css({backgroundColor: color})
                            },

                        };
                        if(scope.settings.target) {
                            cpOptions.value = getComputedStyle(scope.settings.target).color
                        }
                        mw.colorPicker(cpOptions);
                     })

                     cinput.css({backgroundColor: getComputedStyle(scope.settings.target).color})


                    holder.append(cel);
                }
                if(scope.settings.iconOptions.imageReplace) {
                    var rel = mw.element(`
                    <div class="mwiconlist-settings-section-block-item">
                        <label class="mw-icon-selector-control-label live-edit-label px-0 mb-2 ps-2">${mw.lang('Replace with image')}</label>
                        ${mw.top().app.iconService.icon('upload', {width: '25px'})}
                    </div>`);

                    rel.on('click', function () {
                        mw.filePickerDialog( (url) => {

                            if(url) {
                                scope.dispatch('iconReplaced', {
                                    type: 'image',
                                    url: url,
                                });
                            }

                        });
                    });

                    holder.append(rel);
                }
                if(scope.settings.iconOptions.reset) {
                    var rel = mw.element(`
                    <div class="mwiconlist-settings-section-block-item">
                        <label class="mw-icon-selector-control-label live-edit-label px-0 ps-2">${mw.lang('Reset icon options')}</label>
                    </div>`);
                    var rinput = mw.element(`
                        <button type="button" style="min-width: 150px" class="btn btn-outline-secondary ">
                        ${mw.lang('Reset')}
                        </button>
                    `);
                    rinput.on('click', function () {
                        scope.dispatch('reset', rinput.get(0).value);

                    });
                    rel.append(rinput);
                    holder.append(rel);
                }
            }
        };

        var _prepareIconsLists = function (c) {
            var sets = loader.storage();
            var all = sets.length;
            var i = 0;
             sets.forEach(function (set){
                 if (!set._iconsLists) {
                     (function (aset){
                         aset.icons().then(function (data){
                             aset._iconsLists = data;
                             i++;
                             if(i === all) c.call(sets, sets);
                         });
                     })(set);
                 } else {
                     i++;
                     if(i === all) c.call(sets, sets);
                 }

            });
        };


        var createPaging = function(length, page){
            page = page || 1;
            var max = 999;
            var pages = Math.min(Math.ceil(length/scope.settings.iconsPerPage), max);
            var paging = document.createElement('ul');
            paging.className = 'pagination mw-live-edit-pagination-tabler d-flex flex-wrap mx-auto mt-3';
            if(scope.settings.iconsPerPage >= length ) {
                return paging;
            }
            var active = false;
            for ( var i = 1; i <= pages; i++) {
                var el = document.createElement('a');
                el.innerHTML = i;
                el._value = i;
                if(page === i) {
                    el.className = 'active';
                    active = i;
                }
                el.classList.add('page-link');
                el.onclick = function () {
                    comRender({page: this._value });
                };
                var elLi = document.createElement('li');
                elLi.className = 'page-item';

                elLi.appendChild(el);


                paging.appendChild(elLi);
            }
            var all = paging.querySelectorAll('li');
            for (var i = active - 3; i < active + 2; i++){
                if(all[i]) {
                    all[i].className += ' mw-paging-visible-range';
                }
            }


            if(active < pages) {
                var next = document.createElement('a');
                next.innerHTML = '&raquo;';
                next._value = active+1;
                next.className = 'mw-paging-visible-range mw-paging-next';
                next.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M9 6l6 6l-6 6"></path></svg>';
                $(paging).append(next);
                next.onclick = function () {
                     comRender({page: this._value });
                }
            }
            if(active > 1) {
                var prev = document.createElement('a');
                prev.className = 'mw-paging-visible-range mw-paging-prev';
                prev.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M15 6l-6 6l6 6"></path></svg>';
                prev._value = active-1;
                $(paging).prepend(prev);
                prev.onclick = function () {
                     comRender({page: this._value });
                };
            }

            return paging;
        };

        var searchField = function () {
            var time = null;
            scope.searchField =  mw.element({
                tag: 'input',
                props: {
                    className: 'form-control-live-edit-input',
                    placeholder: 'Search',
                    oninput: function () {
                        clearTimeout(time);
                        time = setTimeout(function (){
                            comRender();
                        }, 123);
                    }
                }
            });

            var searchFieldWrapper = mw.element('<div class="form-control-live-edit-label-wrapper m-0 mt-2"></div>');
            var searchFieldSpanEffect = mw.element('<span class="form-control-live-edit-bottom-effect"></span>');
            searchFieldWrapper.append(scope.searchField);
            searchFieldWrapper.append(searchFieldSpanEffect);

            return searchFieldWrapper;
        };

        var comRender = function (options) {
            options = options || {};
            options = mw.object.extend({}, {
                set: scope.selectField.get(0).options[scope.selectField.get(0).selectedIndex]._value,
                term: scope.searchField.get(0).value
            }, options);
            scope.ui.iconsHolder.empty().append(renderSearchResults(options));
        };

        var searchSelector = function () {
            var sel = mw.element('<select class="form-select form-control-live-edit-input" />');
            scope.selectField = sel;
            loader.storage().forEach(function (item) {
                var el = document.createElement('option');
                el._value = item;
                el.innerHTML = item.name;
                sel.append(el);
            });
            sel.on('change', function (){
                comRender()
            });

            var searchSelectorWrapper = mw.element('<div class="form-control-live-edit-label-wrapper m-0 mt-2"></div>');
            searchSelectorWrapper.append(sel);

            return searchSelectorWrapper;
        };

        var search = function (conf) {
            conf = conf || {};
            conf.set = conf.set ||  loader.storage()[0];
            conf.page = conf.page || 1;
            conf.term = (conf.term || '').trim().toLowerCase();

            if (!conf.set._iconsLists) {
                return;
            }

            var all = conf.set._iconsLists.filter(function (f){ return (f.name || f).toLowerCase().indexOf(conf.term) !== -1; });

            var off = scope.settings.iconsPerPage * (conf.page - 1);
            var to = off + Math.min(all.length - off, scope.settings.iconsPerPage);

            return mw.object.extend({}, conf, {
                data: all.slice(off, to),
                all: all,
                off: off
            });
            /*for ( var i = off; i < to; i++ ) {

            }*/
        };

        var renderSearchResults = function (conf) {
            var res = search(conf);

            if(!res) return;
            var pg = createPaging(res.all.length, res.page);
            var root = mw.element();
            if(!res.data.length) {
                root.append( mw.element(`<div class="alert" role="alert"><h5 class="text-secondary fw-normal">No results for <strong>${conf.term}</strong></h5></div>`));
            }
            res.data.forEach(function (iconItem){
                var icon = mw.element({
                    tag: 'span',
                    props: {
                        className: 'mwiconlist-icon',
                        onclick: function (e) {
                            scope.dispatch('select', {
                                icon: iconItem,
                                renderer: res.set.render,
                                render: function () {
                                    var sets = loader.storage();
                                    sets.forEach(function (set) {
                                        set.remove(scope.target)
                                    })
                                    return res.set.render(iconItem, scope.target);
                                }
                            });
                            setTimeout(function (){
                                mw.trigger('iconInserted')
                            })
                        }
                    }
                });
                root.append(icon);
                res.set.render(iconItem, icon.get(0));
            });
            root.append(pg)
            return root;
        };

        var createIconsBlock = function () {
            mw.spinner({element: scope.ui.iconsHolder.get(0), size: 30}).show();
            _prepareIconsLists(function (){
                comRender();
                mw.spinner({element: scope.ui.iconsHolder.get(0)}).hide();
            });
        };

        this.create = function () {
            this.ui = createUI();
            createOptions(this.ui.optionsHolder);
            this.ui.iconsBlockHolder.prepend(searchField());

            this.ui.iconsBlockHolder.prepend(searchSelector());
            createIconsBlock();

        };

        this.get = function () {
            return this.ui.root.get(0);
        };

        this.dialog = function (method) {
            if(method === 'hide') {
                this._dialog.hide();
                return;
            }
            if(!this._dialog) {
                this._dialog = mw.top().dialog({content: this.get(), title: 'Select icon', closeButtonAction: 'hide', width: 350,overlayClose: true});

                this._dialog.dialogContainer.style.padding = '0px';
                this._dialog.overlay.style.backgroundColor = 'transparent';

                mw.components._init();
            }
            this._dialog.show();
            return this._dialog;
        };

        this.destroy = function () {
            this.get().remove()
            if(this._dialog) {
                this._dialog.remove();
            }
            if(this._tooltip) {
                this._tooltip.remove();
            }
        };

        this.target = null;

        this.tooltip = function (target) {
            this.target = target;
            if(target === 'hide' && this._tooltip) {
                this._tooltip.style.display = 'none';
            } else {
                if (!this._tooltip) {
                    this._tooltip = mw.tooltip({
                        content: this.get(),
                        element: target,
                        position: 'bottom-center',
                    });
                } else {
                    mw.tools.tooltip.setPosition(this._tooltip, target, 'bottom-center');
                }

                this._tooltip.style.display = 'block';
                if(target.nodeType === 1) {
                    var css = getComputedStyle(target);
                    $('[type="number"],[type="range"]', this._tooltip).val(parseFloat(css.fontSize));

                    $('[type="color"]', this._tooltip).val(mw.color.rgbOrRgbaToHex(css.color));
                }

            }

            mw.components._init();
            return this._tooltip;
        };

        this.init = function () {
            this.create();
        };

        this.promise = function () {
            return new Promise(function (resolve){
               scope.on('select', function (data) {
                   resolve(data);
               });
            });
        };

        this.init();

    };


    // task-2026-06-04-ico2x — singleton/coalesce guard. Double-clicking an
    // icon in the live-edit canvas runs the open path twice (the dblclick
    // dispatch + the trailing click), which previously spawned TWO stacked
    // "Select icon" dialogs. Coalesce rapid re-opens (<600ms) into the same
    // picker instance so only ONE icon-picker modal ever exists at a time.
    // A genuine later open (>600ms) still gets a fresh picker.
    var _mwActiveIconPicker = null;
    var _mwLastIconPickerAt = 0;
    mw.iconPicker = function (options) {
        var now = (typeof Date !== 'undefined' && Date.now) ? Date.now() : (+new Date());
        if (_mwActiveIconPicker && (now - _mwLastIconPickerAt) < 600) {
            // Re-entrant double-open: reuse the picker already being opened
            // instead of creating a second dialog on top of it.
            _mwLastIconPickerAt = now;
            return _mwActiveIconPicker;
        }
        _mwLastIconPickerAt = now;
        _mwActiveIconPicker = new IconPicker(options);
        return _mwActiveIconPicker;
    };

})();
