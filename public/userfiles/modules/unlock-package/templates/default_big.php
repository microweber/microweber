<?php

/*

type: layout

name: Default

description: Default

*/

?>

<style>
    .unlock-package-wrapper * {
        font-family: 'Jost', sans-serif;

    }

    .unlock-package-wrapper input {
        background-color: #f5f5f5;
        border: 1px solid black;
    }

    .unlock-package-wrapper .get-license-btn {
        padding:  10px 30px;
        border-radius: 30px;
        background-color: #f26227;
        color: #fff;
    }

    .unlock-package-wrapper .unlock-big-template-btn {
        padding:  10px 30px;
        border-radius: 30px;
        background-color: #fff;
        color: #000;
        border: 2px solid #f26227;
    }

    .unlock-big-template-btn:hover {
        background-color: #f26227;
        color: #fff;
    }

    .unlock-package-columns {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
        justify-content: start;
        padding-top: 30px;
    }

    .font-weight-bold {
        font-weight: 700;
    }

    .font-weight-normal {
        font-weight: 500;
    }

    .unlock-package-right-side-img {
       background-size: contain;
        background-position: top;
        background-repeat: no-repeat;
        height: 300px;
        width: auto;
    }

    .text-orange {
        color: #f26227;
    }
</style>
<div class="mh-100vh unlock-package-wrapper row mx-auto align-items-center justify-content-center">

    <div class="col-md-8 unlock-package-columns ">

       <div class="px-5">
           <h1 class="font-weight-bold">Unlock The power of <span class="text-orange">BIG Template</span></h1>
           <h4>Use all layouts to make an awesome websites!</h4>
           <h4>Buy license key "Big Template" and unlock 300 more layouts,</h4>
           <h4>only for $59 per year or $169 lifetime license</h4>

           <br><br>
           <a class="btn btn-primary get-license-btn" target="_blank" href="https://whitelabel.microweber.com/big">Get License</a>

           <br> <br>
               <form class="d-flex align-items-center">
                   <input type="text" id="js-unlock-package-license-key-<?php echo $params['id'];?>" name="local_key" autocomplete="on" autofocus="true" class="form-control my-3" placeholder="License key">
                   <div class="ms-md-3">
                       <button id="js-unlock-package-save-license-<?php echo $params['id'];?>" type="button" class="btn btn-outline-primary unlock-big-template-btn">Unlock</button>
                   </div>
               </form>


           <p class="font-weight-bold">Have a problem with your White Label license key?
               <a class="text-orange" href="javascript:;" onmousedown="mw.contactForm();">Contact us.</a>
           </p>
       </div>

    </div>
    <div class="col-md-4 px-0 unlock-package-columns pt-0" style="background-color: #f5f5f5;">

      <div class="unlock-package-right-side-img" style="background-image: url('<?php print modules_url(); ?>unlock-package/assets/img/right-banner.jpg')">

          <div class="ps-5" style="padding-top: 200px;">
              <h1 class="d-flex gap-2">
                  <span class="font-weight-bold">350+</span>
                  <span class="font-weight-normal">Layouts</span>
              </h1>
              <h2 class="d-flex gap-2">
                  <span class="font-weight-bold">20</span>
                  <span class="font-weight-normal">Categories</span>
              </h2>
              <h3 class="d-flex gap-2">
                  <span class="font-weight-bold">75</span>
                  <span class="font-weight-normal">Modules</span>
              </h3>
              <h4>Fresh Updates</h4>
              <h4>Live Support</h4>
              <h4>Theme & Layouts Customization</h4>
              <h4> And Much More ...</h4>
          </div>
      </div>



    </div>
</div>

