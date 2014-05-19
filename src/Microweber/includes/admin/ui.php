

<style scoped="scoped">
    #ui-info-table{
      width: 100%;
      border: 1px solid #eee;
      margin: auto;
      border-collapse: collapse;
      margin-bottom: 150px;
    }
    #ui-info-table > tbody > tr > td,
    #ui-info-table > thead > tr > th{
      border: 1px solid #eee;
      padding: 20px;
    }

    #info-icon-list li{
      list-style: none;
      float: left;
      text-align: center;
      padding: 20px;
      vertical-align: middle;
      cursor: default;
      width: 150px;
      height: 115px;
    }

    #info-icon-list li:hover{
      color: white;
      background: black
    }

    #info-icon-list li em{
      display: block;
      text-align: center;
      font-style: normal;
      padding: 3px 0 10px 0;
    }
    #info-icon-list li span{
      font-size: 41px;
    }

    .demobox{
      position: relative;
      padding: 20px 0;
      max-width: 600px;
    }
    .demobox:after{
      content: ".";
        display: block;
        clear: both;
        visibility: hidden;
        line-height: 0;
        height: 0;
    }

    .demof1 .demobox .mw-ui-field{
      float: left;
      margin-right: 6px;
    }

</style>


<script>


$(window).load(function(){
  var uicss = mwd.querySelector('link[href*="/ui.css"]').sheet.cssRules, l = uicss.length, i = 0, html='';
  for( ;i<l;i++){
    var sel = uicss[i].selectorText;
    if(!!sel && sel.contains('.mw-icon-')){
        var cls = sel.replace(".", '').split(':')[0];
        html +='<li><span class="'+cls+'"></span><em>.'+cls+'</em></li>';
    }
  }
  mw.$('#info-icon-list').html('<ul>'+html+'</ul>');

});


</script>


<table width="800" id="ui-info-table">
  <col width="60%" />
  <col width="40%" />
  <tbody>

    <tr>
      <td colspan="2">
            <h2>Icons</h2>


             <div id="info-icon-list"></div>


       </td>
    </tr>
    <tr>
      <td colspan="2">
            <h2>Buttons</h2>


    <h3>Default</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium">Medium</a>
    <a href="javascript:;" class="mw-ui-btn">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big">Big</a>

    <h3>Invert</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-invert">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-invert">Medium</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-invert">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-invert">Big</a>

    <h3>Info</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-info">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-info">Medium</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-info">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-info">Big</a>

    <h3>Warn</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-warn">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-warn">Medium</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-warn">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-warn">Big</a>

    <h3>Important</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-important">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-important">Medium</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-important">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-important">Big</a>

    <h3>Notification</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-notification">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-notification">Medium</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-notification">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-notification">Big</a>

    <h3>Button with icon</h3>
    <div class="demobox"><a href="javascript:;" class="mw-ui-btn"><span class="mw-icon-website"></span>Normal</a></div>
    <h3>Button Navigations</h3>
    <div class="demobox">
        <div class="mw-ui-btn-nav">
            <a href="javascript:;" class="mw-ui-btn">Home</a>
            <a href="javascript:;" class="mw-ui-btn active">About</a>
            <a href="javascript:;" class="mw-ui-btn">Contact</a>
        </div>
    </div>
    <div class="demobox">
        <div class="mw-ui-btn-vertical-nav">
            <a href="javascript:;" class="mw-ui-btn">Vertical</a>
            <a href="javascript:;" class="mw-ui-btn active">Button</a>
            <a href="javascript:;" class="mw-ui-btn">Navigation</a>
        </div>
    </div>


        <h3>Button Tabs Navigations</h3>
        <div class="demobox" id="demotabsnav">
            <div class="mw-ui-btn-nav mw-ui-btn-nav-tabs">
                <a href="javascript:;" class="mw-ui-btn active">Home</a>
                <a href="javascript:;" class="mw-ui-btn">About</a>
                <a href="javascript:;" class="mw-ui-btn">Contact</a>
            </div>
            <div class="mw-ui-box">
              <div class="mw-ui-box-content">Home - Lorem Ipsum </div>
              <div class="mw-ui-box-content" style="display: none">About - Lorem Ipsum </div>
              <div class="mw-ui-box-content" style="display: none">Contact - Lorem Ipsum </div>
            </div>
            </div>


        <script>
            $(document).ready(function(){
               mw.tabs({
                  nav:'#demotabsnav .mw-ui-btn-nav-tabs a',
                  tabs:'#demotabsnav .mw-ui-box-content'
               });
            });
        </script>

       </td>
    </tr>
    <tr>
      <td colspan="2">
         <h2>Boxes</h2>
         <div class="demobox">
        <div class="mw-ui-box">
          <div class="mw-ui-box-content">Lorem Ipsum </div>
        </div>
        </div>
        <div class="demobox">
        <div class="mw-ui-box">
          <div class="mw-ui-box-header"><span class="mw-icon-gear"></span><span>Box with header and icon</span></div>
          <div class="mw-ui-box-content">Lorem Ipsum </div>
          </div>
          </div>
       </td>

    </tr>
    <tr>
        <td colspan="2">
        <h2>Table</h2>
<table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
  <tbody>
    <tr>
      <td>Lorem</td>
      <td>Ipsum</td>
      <td>Sit</td>
      <td>Amet</td>
      <td>Dolor</td>
      <td>987987</td>
      <td></td>
    </tr>
    <tr>
      <td>Lorem</td>
      <td>Ipsum</td>
      <td>Sit</td>
      <td>Amet</td>
      <td>Dolor</td>
      <td>987987</td>
      <td></td>
    </tr>
  </tbody>
</table>
<h2>Table with header</h2>
<table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
  <thead>
    <tr>
      <th>Table </th>
      <th>Head</th>
      <th>Client</th>
      <th>Country</th>
      <th>City</th>
      <th>Orders</th>
      <th>View</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>Lorem</td>
      <td>Ipsum</td>
      <td>Sit</td>
      <td>Amet</td>
      <td>Dolor</td>
      <td>987987</td>
      <td><a href="javascript:;" class="show-on-hover mw-ui-btn mw-ui-btn-medium">View on hover</a></td>
    </tr>
  </tbody>
</table>


<h2>Table with header and footer</h2>
<table cellspacing="0" cellpadding="0" class="mw-ui-table" width="100%">
  <thead>
    <tr>
      <th>Table </th>
      <th>Head</th>
      <th>Client</th>
      <th>Country</th>
      <th>City</th>
      <th>Orders</th>
      <th>View</th>
    </tr>
  </thead>
  <tfoot>
    <tr>
      <td>Table </td>
      <td>Footer</td>
      <td>Client</td>
      <td>Country</td>
      <td>City</td>
      <td>Orders</td>
      <td>View</td>
    </tr>
  </tfoot>
  <tbody>
    <tr>
      <td>Lorem</td>
      <td>Ipsum</td>
      <td>Sit</td>
      <td>Amet</td>
      <td>Dolor</td>
      <td>987987</td>
      <td></td>
    </tr>
  </tbody>
</table>
        </td>


        </tr>
        <tr>
      <td colspan="2">
            <h2>Progress Bars</h2>


              <div class="demobox">
              <div class="mw-ui-progress">
                  <div style="width: 33%;" class="mw-ui-progress-bar"></div>
                  <div class="mw-ui-progress-info">Uploading</div>
                  <span class="mw-ui-progress-percent">33%</span>
              </div>
       </div>
       <div class="demobox">
              <div class="mw-ui-progress-small">
                <div class="mw-ui-progress-bar" style="width: 40%">

                </div>
              </div>
           </div>

       </td>

    </tr>

    <tr>
      <td colspan="2">
         <h2>Form elements</h2>

        <div class="demobox">
            <label class="mw-ui-label">Field</label>
            <input type="text" class="mw-ui-field" />

        </div>
        <div class="demobox">
            <label class="mw-ui-label">Textarea</label>
            <textarea class="mw-ui-field"></textarea>
        </div>
        <h2>Field sizes and fields with buttons</h2>
        <div class="demof1">
        <div class="demobox">
            <label class="mw-ui-label">Small</label>
            <input type="text" class="mw-ui-field mw-ui-field-small" />
            <select class="mw-ui-field mw-ui-field-small"><option>Option 1</option><option>Option 2</option></select>
            <span class="mw-ui-btn mw-ui-btn-small"><span class="mw-icon-magnify"></span>Button</span>
        </div>
        <div class="demobox">
            <label class="mw-ui-label">Medium</label>
            <input type="text" class="mw-ui-field mw-ui-field-medium" />
            <select class="mw-ui-field mw-ui-field-medium"><option>Option 1</option><option>Option 2</option></select>
            <span class="mw-ui-btn mw-ui-btn-medium"><span class="mw-icon-magnify"></span>Button</span>
        </div>
        <div class="demobox">
            <label class="mw-ui-label">Normal</label>
            <input type="text" class="mw-ui-field" />
            <select class="mw-ui-field"><option>Option 1</option><option>Option 2</option></select>
            <span class="mw-ui-btn"><span class="mw-icon-magnify"></span>Button</span>
        </div>
        <div class="demobox">
            <label class="mw-ui-label">Big</label>
            <input type="text" class="mw-ui-field mw-ui-field-big" />
            <select class="mw-ui-field mw-ui-field-big"><option>Option 1</option><option>Option 2</option></select>
            <span class="mw-ui-btn mw-ui-btn-big"><span class="mw-icon-magnify"></span>Button</span>
        </div>
        </div>

       </td>

    </tr>
   <tr>
    <td colspan="2">
    <h2>Pure CSS radio buttons and checkboxes </h2>
       <div class="demobox">
       <label class="mw-ui-check">
            <input type="checkbox" checked="checked" />
            <span></span>
            <span>Checkbox 1</span>
       </label>
       <label class="mw-ui-check">
            <input type="checkbox" />
            <span></span>
            <span>Checkbox 2</span>
       </label>
       </div>
       <div class="demobox">
       <label class="mw-ui-check">
            <input type="radio" name="demoradio1" checked="checked" />
            <span></span>
            <span>Radio 1</span>
       </label>
       <label class="mw-ui-check">
            <input type="radio" name="demoradio1" />
            <span></span>
            <span>Radio 2</span>
       </label>
       </div>
    </td>
    </tr>
    <tr>

    <td colspan="2">
        <h2>Hover Dropdown menus</h2>
        <div class="mw-ui-row">
            <div class="mw-ui-col" style="width: 120px;">
            <div class="mw-ui-dropdown">
                <span>No tyles</span>
                <div class="mw-ui-dropdown-content">
                    Some option
                </div>
            </div>

            </div>
            <div class="mw-ui-col">
                <div class="mw-ui-dropdown">
                <span class="mw-ui-btn">Button navigation</span>
                <div class="mw-ui-dropdown-content">
                    <div class="mw-ui-btn-vertical-nav">
                         <span class="mw-ui-btn">Option 1</span>
                         <span class="mw-ui-btn">Option 2</span>
                         <span class="mw-ui-btn">Option 3</span>
                    </div>
                </div>
            </div>
            </div>
            <div class="mw-ui-col">
                <div class="mw-ui-dropdown">
                <span class=" mw-ui-btn mw-ui-btn-big">Big Button navigation</span>
                <div class="mw-ui-dropdown-content">
                    <div class="mw-ui-btn-vertical-nav">
                         <span class="mw-ui-btn mw-ui-btn-big">Option 1</span>
                         <span class="mw-ui-btn mw-ui-btn-big">Option 2</span>
                         <span class="mw-ui-btn mw-ui-btn-big">Option 3</span>
                    </div>
                </div>
            </div>
            </div>

        </div>

    </td>

    </tr>

    <tr>
    <td colspan="2">
    <h2>Dropdowns onclick</h2>
       <div class="demobox">
         <div class="mw-dropdown">
            <span class="mw-dropdown-value">
                <span class="mw-ui-btn mw-ui-btn-info mw-dropdown-val">Dropdown on click <span class="mw-icon-dropdown mw-icon-right"></span></span>
            </span>
            <div class="mw-dropdown-content">
                <ul class="mw-ui-btn-vertical-nav" style="width: 100%;">
                     <li value="1"><span class="mw-ui-btn">Option 1</span></li>
                     <li value="2"><span class="mw-ui-btn mw-ui-btn-important">Option 2 !!!</span></li>
                     <li value="3"><span class="mw-ui-btn">Option 3</span></li>
                </ul>
            </div>
          </div>
          <script>
            mw.dropdown()
          </script>
       </div>

    </td>
    </tr>
    <tr>
    <td colspan="2">
    <h2>TESTS</h2>
       <div class="demobox" style="padding: 15px;border:1px solid #dedede;">
           <div class="mw-ui-row" id="RRtest" style="table-layout: auto;">
               <div class="mw-ui-col"><div class="mw-ui-col-container">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin rhoncus turpis libero, sed ullamcorper ante vestibulum in. Mauris tempor nunc rhoncus enim accumsan viverra. Integer semper diam vitae fermentum dictum. Aliquam blandit venenatis nisl. In lobortis magna tincidunt varius eleifend. Etiam turpis velit, porttitor eget erat vel, accumsan pretium metus. Suspendisse venenatis elit eget est consequat dapibus. Proin pharetra turpis sit amet ante consectetur, eget facilisis purus hendrerit. Praesent id risus quis dolor volutpat dapibus sit amet id lorem.</div></div>
               <div class="mw-ui-col"><div class="mw-ui-col-container">Nullam non porta augue, eu congue augue. Donec dapibus eros eget purus blandit adipiscing. Aliquam sit amet facilisis lorem, eget fermentum erat. Curabitur ultrices lectus mauris, mollis congue justo blandit in. In hac habitasse platea dictumst. Maecenas tempor ultrices dui eu euismod. Donec pulvinar lectus quis blandit posuere. Pellentesque vestibulum mattis aliquet. Vestibulum non tincidunt risus. Vestibulum tellus mauris, faucibus nec magna quis, placerat euismod enim. Fusce blandit at lacus vitae feugiat. Sed vel nisl elementum erat sollicitudin aliquet. Pellentesque diam quam, tempor et posuere id, condimentum non nulla. Proin dolor arcu, tincidunt feugiat magna sed, iaculis interdum mauris. Etiam fringilla dapibus libero, sed mollis velit laoreet eu.
            </div></div>    <div class="mw-ui-col"><div class="mw-ui-col-container">Ut commodo sagittis sagittis. Suspendisse porta nibh at sapien adipiscing, non mollis nibh rutrum. Donec sagittis odio eu pharetra viverra. Integer aliquet adipiscing magna. Nam luctus, magna a volutpat laoreet, eros ante pharetra odio, sed eleifend ante purus sed nulla. Suspendisse gravida orci nisl, non congue orci mattis tristique. Mauris lorem justo, scelerisque eget tincidunt eget, consectetur non mauris. In sed nunc accumsan, vulputate nibh eget, rutrum magna. Suspendisse potenti. Vivamus facilisis nisl turpis, scelerisque congue felis tempor vitae. Suspendisse sed faucibus nunc. Nunc non feugiat nisi, in gravida odio. Aliquam erat volutpat. Vivamus cursus bibendum leo eu pulvinar. Nulla interdum neque hendrerit volutpat commodo. Fusce blandit est id risus laoreet, vitae mattis enim eleifend.

</div></div>
           </div>
           <link rel="stylesheet" href="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>
          <script>
            $(document).ready(function(){

                var all = mwd.querySelectorAll("#RRtest .mw-ui-col"), l = all.length, i = 0;
                for( ; i<(l-1); i++){
                  var item = all[i];
                  var cont = item.querySelector('.mw-ui-col-container');
                  var nextCont = all[i+1].querySelector('.mw-ui-col-container');
                  item.style.maxWidth = ((100/l) * (l-1)) + '%';
                  cont.style.maxWidth = "100%";
                  $(cont).resizable({
                    handles:'e',
                    alsoResize:item,
                    resize:function(){
                       this.style.width = 'auto';
                    },
                    stop:function(){
                       this.style.width = 'auto';
                    }
                  })
                }

           })
          </script>
       </div>

    </td>
    </tr>

  </tbody>
</table>



 