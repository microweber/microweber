class MWSiteMobileMenuService {


    constructor(options = {}) {
      var defaults = {};
      this.settings = Object.assign({}, defaults, options);
      this.init()
    }

    state = false;

    currentMenu = null;
    
    buildMobileMenu(targetMenu) {
      if(this.currentMenu) {
        this.currentMenu.remove()
      }
      var ul = document.createElement('ul');
      ul.innerHTML = targetMenu.querySelector('ul').innerHTML;
      ul.querySelectorAll('[style],[class]').forEach(node => {
        node.removeAttribute('style')
        node.removeAttribute('class');
      });

      const block =  document.createElement('div');
      const ovl =  document.createElement('div');
      ovl.className = 'mw-vhmbgr-active-overlay';
      block.className = 'mw-vhmbgr-active-popup';
      this.currentMenu = block;

      ovl.addEventListener('click', e => {
        this.mobileMenu(undefined, false)
      })

     
      block.append(ul)
      document.body.append(ovl)
      document.body.append(block)

    }


    mobileMenu (node, state)  {
      var action = 'toggle';
      if(state === true) {
        action = 'add'
      } else if(state === false) {
        action = 'remove'
      }


      if(node) {
        node.classList[action]('mw-vhmbgr-active');
      } else {
        Array.from(document.querySelectorAll('.mw-vhmbgr')).forEach(node => {
          node.classList[action]('mw-vhmbgr-active');
        })
      }

   
      
      document.body.classList[action]('mw-vhmbgr-menu-active');
    }

    init() {
      
        
    
        document.body.addEventListener('click', (e) => {
         this.mobileMenu(undefined, false) 
        });

        const nav = document.querySelector('.mw-vhmbgr--navigation');

        if(nav) {
          this.buildMobileMenu(nav);
        }

        
        Array.from(document.querySelectorAll('.mw-vhmbgr')).forEach(node => {
          node.addEventListener('click', e => {
            this.mobileMenu(node);
            e.preventDefault();
            e.stopPropagation();
          })
        })
       
    }

  }

   

  const MWSiteMobileMenu = ( options, hamburger = 5) => {
    options.threshold = options.threshold || 800;
    options.color = options.color || '#111';
    options.size = options.size || '55px';
    const hamburgers = [

        ` 
        <svg class="mw-vhmbgr mw-vhmbgrRotate mw-vhmbgr1" viewBox="0 0 100 100"><path class="mw-vhmbgr--line mw-vhmbgr--top" d="m 30,33 h 40 c 0,0 9.044436,-0.654587 9.044436,-8.508902 0,-7.854315 -8.024349,-11.958003 -14.89975,-10.85914 -6.875401,1.098863 -13.637059,4.171617 -13.637059,16.368042 v 40"/><path class="mw-vhmbgr--line mw-vhmbgr--middle" d="m 30,50 h 40"/><path class="mw-vhmbgr--line mw-vhmbgr--bottom" d="m 30,67 h 40 c 12.796276,0 15.357889,-11.717785 15.357889,-26.851538 0,-15.133752 -4.786586,-27.274118 -16.667516,-27.274118 -11.88093,0 -18.499247,6.994427 -18.435284,17.125656 l 0.252538,40"/></svg>
        `,
        ` 
        <svg class="mw-vhmbgr mw-vhmbgr2" viewBox="0 0 100 100"><path class="mw-vhmbgr--line mw-vhmbgr--top" d="m 70,33 h -40 c -6.5909,0 -7.763966,-4.501509 -7.763966,-7.511428 0,-4.721448 3.376452,-9.583771 13.876919,-9.583771 14.786182,0 11.409257,14.896182 9.596449,21.970818 -1.812808,7.074636 -15.709402,12.124381 -15.709402,12.124381"/><path class="mw-vhmbgr--line mw-vhmbgr--middle" d="m 30,50 h 40"/><path class="mw-vhmbgr--line mw-vhmbgr--bottom" d="m 70,67 h -40 c -6.5909,0 -7.763966,4.501509 -7.763966,7.511428 0,4.721448 3.376452,9.583771 13.876919,9.583771 14.786182,0 11.409257,-14.896182 9.596449,-21.970818 -1.812808,-7.074636 -15.709402,-12.124381 -15.709402,-12.124381"/></svg>
        `,
        
        ` 
        <svg class="mw-vhmbgr mw-vhmbgrRotate mw-vhmbgr4" viewBox="0 0 100 100"><path class="mw-vhmbgr--line mw-vhmbgr--top" d="m 70,33 h -40 c 0,0 -8.5,-0.149796 -8.5,8.5 0,8.649796 8.5,8.5 8.5,8.5 h 20 v -20"/><path class="mw-vhmbgr--line mw-vhmbgr--middle" d="m 70,50 h -40"/><path class="mw-vhmbgr--line mw-vhmbgr--bottom" d="m 30,67 h 40 c 0,0 8.5,0.149796 8.5,-8.5 0,-8.649796 -8.5,-8.5 -8.5,-8.5 h -20 v 20"/></svg>
        `,
        ` 
        <svg class="mw-vhmbgr mw-vhmbgrRotate180 mw-vhmbgr5" viewBox="0 0 100 100"><path class="mw-vhmbgr--line mw-vhmbgr--top" d="m 30,33 h 40 c 0,0 8.5,-0.68551 8.5,10.375 0,8.292653 -6.122707,9.002293 -8.5,6.625 l -11.071429,-11.071429"/><path class="mw-vhmbgr--line mw-vhmbgr--middle" d="m 70,50 h -40"/><path class="mw-vhmbgr--line mw-vhmbgr--bottom" d="m 30,67 h 40 c 0,0 8.5,0.68551 8.5,-10.375 0,-8.292653 -6.122707,-9.002293 -8.5,-6.625 l -11.071429,11.071429"/></svg>
        `,
        `
        <svg class="mw-vhmbgr mw-vhmbgr6" viewBox="0 0 100 100"><path class="mw-vhmbgr--line mw-vhmbgr--top" d="m 30,33 h 40 c 13.100415,0 14.380204,31.80258 6.899646,33.421777 -24.612039,5.327373 9.016154,-52.337577 -12.75751,-30.563913 l -28.284272,28.284272"/><path class="mw-vhmbgr--line mw-vhmbgr--middle" d="m 70,50 c 0,0 -32.213436,0 -40,0 -7.786564,0 -6.428571,-4.640244 -6.428571,-8.571429 0,-5.895471 6.073743,-11.783399 12.286435,-5.570707 6.212692,6.212692 28.284272,28.284272 28.284272,28.284272"/><path class="mw-vhmbgr--line mw-vhmbgr--bottom" d="m 69.575405,67.073826 h -40 c -13.100415,0 -14.380204,-31.80258 -6.899646,-33.421777 24.612039,-5.327373 -9.016154,52.337577 12.75751,30.563913 l 28.284272,-28.284272"/></svg>
        `,
        `
        <svg class="mw-vhmbgr mw-vhmbgrRotate mw-vhmbgr7" viewBox="0 0 100 100"><path class="mw-vhmbgr--line mw-vhmbgr--top" d="m 70,33 h -40 c 0,0 -6,1.368796 -6,8.5 0,7.131204 6,8.5013 6,8.5013 l 20,-0.0013"/><path class="mw-vhmbgr--line mw-vhmbgr--middle" d="m 70,50 h -40"/><path class="mw-vhmbgr--line mw-vhmbgr--bottom" d="m 69.575405,67.073826 h -40 c -5.592752,0 -6.873604,-9.348582 1.371031,-9.348582 8.244634,0 19.053564,21.797129 19.053564,12.274756 l 0,-40"/></svg>
        `,
        `
        <svg class="mw-vhmbgr mw-vhmbgrRotate mw-vhmbgr8" viewBox="0 0 100 100"><path class="mw-vhmbgr--line mw-vhmbgr--top" d="m 30,33 h 40 c 3.722839,0 7.5,3.126468 7.5,8.578427 0,5.451959 -2.727029,8.421573 -7.5,8.421573 h -20"/><path class="mw-vhmbgr--line mw-vhmbgr--middle" d="m 30,50 h 40"/><path class="mw-vhmbgr--line mw-vhmbgr--bottom" d="m 70,67 h -40 c 0,0 -7.5,-0.802118 -7.5,-8.365747 0,-7.563629 7.5,-8.634253 7.5,-8.634253 h 20"/></svg>

        `,

    ]; 
   

   var  curr = document.getElementById('mw-vhmbgr--style');
   if(curr) {
    curr.remove()
   }
   var  css = document.createElement('style');
   css.id = `mw-vhmbgr--style`;
   css.textContent = `



   .mw-vhmbgr-active-popup,
.mw-vhmbgr-active-overlay{
  opacity: 0;
  visibility: hidden;
  pointer-events: none;
  transition: .5s;
}
body.mw-vhmbgr-menu-active .mw-vhmbgr-active-popup,
body.mw-vhmbgr-menu-active .mw-vhmbgr-active-overlay{
  opacity: 1;
  visibility: visible;
  pointer-events: all;
}


.mw-vhmbgr-active-popup li{
    list-style: none;
  }
  .mw-vhmbgr-active-popup  > ul{
    max-height: calc(100vh - 200px);
    overflow: auto;
    padding: 20px;
  }
  .mw-vhmbgr-active-popup{
    position: fixed;
    z-index: 51;
    top: 100px;
    left: 50%;
    transform: translateX(-50%);
    background-color: #fff;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px;
    min-width: 300px;
 
  }
  .mw-vhmbgr-active-overlay{
    position: fixed;
    z-index: 50;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,.2);
  }
 

.mw-vhmbgr {
  cursor: pointer;
  -webkit-tap-highlight-color: transparent;
  transition: transform 400ms;
  -moz-user-select: none;
  -webkit-user-select: none;
  -ms-user-select: none;
  user-select: none;
  width: var(--size);
  color: var(--color);
}


.mw-vhmbgrRotate.mw-vhmbgr-active {
  transform: rotate(45deg);
}
.mw-vhmbgrRotate180.mw-vhmbgr-active {
  transform: rotate(180deg);
}
.mw-vhmbgr--line {
  fill:none;
  transition: stroke-dasharray 400ms, stroke-dashoffset 400ms;
  stroke: currentColor;
  stroke-width:5.5;
  stroke-linecap:round;
}
.mw-vhmbgr1 .mw-vhmbgr--top {
  stroke-dasharray: 40 139;
}
.mw-vhmbgr1 .mw-vhmbgr--bottom {
  stroke-dasharray: 40 180;
}
.mw-vhmbgr1.mw-vhmbgr-active .mw-vhmbgr--top {
  stroke-dashoffset: -98px;
}
.mw-vhmbgr1.mw-vhmbgr-active .mw-vhmbgr--bottom {
  stroke-dashoffset: -138px;
}
.mw-vhmbgr2 .mw-vhmbgr--top {
  stroke-dasharray: 40 121;
}
.mw-vhmbgr2 .mw-vhmbgr--bottom {
  stroke-dasharray: 40 121;
}
.mw-vhmbgr2.mw-vhmbgr-active .mw-vhmbgr--top {
  stroke-dashoffset: -102px;
}
.mw-vhmbgr2.mw-vhmbgr-active .mw-vhmbgr--bottom {
  stroke-dashoffset: -102px;
}
.mw-vhmbgr3 .mw-vhmbgr--top {
  stroke-dasharray: 40 130;
}
.mw-vhmbgr3 .mw-vhmbgr--middle {
  stroke-dasharray: 40 140;
}
.mw-vhmbgr3 .mw-vhmbgr--bottom {
  stroke-dasharray: 40 205;
}
.mw-vhmbgr3.mw-vhmbgr-active .mw-vhmbgr--top {
  stroke-dasharray: 75 130;
  stroke-dashoffset: -63px;
}
.mw-vhmbgr3.mw-vhmbgr-active .mw-vhmbgr--middle {
  stroke-dashoffset: -102px;
}
.mw-vhmbgr3.mw-vhmbgr-active .mw-vhmbgr--bottom {
  stroke-dasharray: 110 205;
  stroke-dashoffset: -86px;
}
.mw-vhmbgr4 .mw-vhmbgr--top {
  stroke-dasharray: 40 121;
}
.mw-vhmbgr4 .mw-vhmbgr--bottom {
  stroke-dasharray: 40 121;
}
.mw-vhmbgr4.mw-vhmbgr-active .mw-vhmbgr--top {
  stroke-dashoffset: -68px;
}
.mw-vhmbgr4.mw-vhmbgr-active .mw-vhmbgr--bottom {
  stroke-dashoffset: -68px;
}
.mw-vhmbgr5 .mw-vhmbgr--top {
  stroke-dasharray: 40 82;
}
.mw-vhmbgr5 .mw-vhmbgr--bottom {
  stroke-dasharray: 40 82;
}
.mw-vhmbgr5.mw-vhmbgr-active .mw-vhmbgr--top {
  stroke-dasharray: 14 82;
  stroke-dashoffset: -72px;
}
.mw-vhmbgr5.mw-vhmbgr-active .mw-vhmbgr--bottom {
  stroke-dasharray: 14 82;
  stroke-dashoffset: -72px;
}
.mw-vhmbgr6 .mw-vhmbgr--top {
  stroke-dasharray: 40 172;
}
.mw-vhmbgr6 .mw-vhmbgr--middle {
  stroke-dasharray: 40 111;
}
.mw-vhmbgr6 .mw-vhmbgr--bottom {
  stroke-dasharray: 40 172;
}
.mw-vhmbgr6.mw-vhmbgr-active .mw-vhmbgr--top {
  stroke-dashoffset: -132px;
}
.mw-vhmbgr6.mw-vhmbgr-active .mw-vhmbgr--middle {
  stroke-dashoffset: -71px;
}
.mw-vhmbgr6.mw-vhmbgr-active .mw-vhmbgr--bottom {
  stroke-dashoffset: -132px;
}
.mw-vhmbgr7 .mw-vhmbgr--top {
  stroke-dasharray: 40 82;
}
.mw-vhmbgr7 .mw-vhmbgr--middle {
  stroke-dasharray: 40 111;
}
.mw-vhmbgr7 .mw-vhmbgr--bottom {
  stroke-dasharray: 40 161;
}
.mw-vhmbgr7.mw-vhmbgr-active .mw-vhmbgr--top {
  stroke-dasharray: 17 82;
  stroke-dashoffset: -62px;
}
.mw-vhmbgr7.mw-vhmbgr-active .mw-vhmbgr--middle {
  stroke-dashoffset: 23px;
}
.mw-vhmbgr7.mw-vhmbgr-active .mw-vhmbgr--bottom {
  stroke-dashoffset: -83px;
}
.mw-vhmbgr8 .mw-vhmbgr--top {
  stroke-dasharray: 40 160;
}
.mw-vhmbgr8 .mw-vhmbgr--middle {
  stroke-dasharray: 40 142;
  transform-origin: 50%;
  transition: transform 400ms;
}
.mw-vhmbgr8 .mw-vhmbgr--bottom {
  stroke-dasharray: 40 85;
  transform-origin: 50%;
  transition: transform 400ms, stroke-dashoffset 400ms;
}
.mw-vhmbgr8.mw-vhmbgr-active .mw-vhmbgr--top {
  stroke-dashoffset: -64px;
}
.mw-vhmbgr8.mw-vhmbgr-active .mw-vhmbgr--middle {
 
  transform: rotate(90deg);
}
.mw-vhmbgr8.mw-vhmbgr-active .mw-vhmbgr--bottom {
  stroke-dashoffset: -64px;
}

.mw-vhmbgr-wrapper{
  position: relative;
  display: block;
  width: var(--size);
  height: var(--size);
  overflow: hidden;
}

.mw-vhmbgr-wrapper > svg{
  position: absolute;
  width: 200%;
  height: 200%;
  left: -50%;
  top: -50%;
}


   .mw-vhmbgr, .mw-vhmbgr-wrapper {
      --size: ${options.size};
      --color: ${options.color}
    }

   .mw-vhmbgr-wrapper{
    display: none;
   }
    @media (max-width: ${options.threshold}px) {
      .mw-vhmbgr--navigation{
        display: none;
      }
      .mw-vhmbgr-wrapper{
        display: block;
      }
    }
    
   `;
   document.body.append(css);
   document.querySelectorAll('.mw-vhmbgr--navigation').forEach(function(node){
    var mobileMenu = document.createElement('span');
    mobileMenu.className = 'mw-vhmbgr-wrapper';
    mobileMenu.innerHTML = hamburgers[hamburger];
    node.after(mobileMenu);
   });

   new MWSiteMobileMenuService(options);
  };

  if(window.mw) {
    window.mw.MWSiteMobileMenu = MWSiteMobileMenu
  }