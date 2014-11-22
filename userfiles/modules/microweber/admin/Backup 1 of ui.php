

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


<div style="position: fixed;right:0;top:20px;padding:20px;border:1px solid #eee">
<h2>Admin apis</h2>

<h3>Tooltip</h3>
<h4>Data - tip text</h4>
<pre class="tip" data-tip="Some help" data-tipposition="top-center">&lt;div class="tip" data-tip="Some help" data-tipposition="top-center">&lt;/div></pre>
<h4>Data - tip Selector - '.' and '#' are available</h4>
<pre class="tip" data-tip=".demobox" data-tipposition="top-left">&lt;div class="tip" data-tip=".demobox" data-tipposition="top-center">&lt;/div></pre>


</div>





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


            <h2>Simple clean table</h2>
<table cellspacing="0" cellpadding="0" class="mw-ui-table mw-ui-table-basic" width="100%">
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
                <span class=" mw-ui-btn mw-ui-btn-big mw-ui-btn-info">Big Button navigation</span>
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

        <style scoped="scoped">

        .demobox{
          max-width: none;
        }

        .mw-dropdown{
          width: 150px;
          margin-right: 10px;
        }



        </style>

          <div class="mw-dropdown mw-dropdown-default">
            <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-warn mw-ui-btn-big mw-dropdown-val">Choose</span>
            <div class="mw-dropdown-content">
                <ul>
                     <li value="1">Option 1</li>
                     <li value="2">Option 2 !!!</li>
                     <li value="3">Option 3</li>
                </ul>
            </div>
          </div>
          <div class="mw-dropdown mw-dropdown-default">
            <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-info mw-dropdown-val">Choose</span>
            <div class="mw-dropdown-content">
                <ul>
                     <li value="1">Option 1</li>
                     <li value="2">Option 2 !!!</li>
                     <li value="3">Option 3</li>
                </ul>
            </div>
          </div>
          <div class="mw-dropdown mw-dropdown-default">
            <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-medium mw-dropdown-val">Choose</span>
            <div class="mw-dropdown-content">
                <ul>
                     <li value="1">Option 1</li>
                     <li value="2">Option 2 !!!</li>
                     <li value="3">Option 3</li>
                </ul>
            </div>
          </div>

          <div class="mw-dropdown mw-dropdown-default" data-value="2">
            <span class="mw-dropdown-value mw-ui-btn mw-ui-btn-small mw-ui-btn-invert mw-dropdown-val">Choose</span>
          <div class="mw-dropdown-content">
            <ul>
              <li value="1"><a href="javascript:;">Option 1</a></li>
              <li value="2"><a href="javascript:;">Option 2</a></li>
              <li value="3"><a href="javascript:;">Option 3</a></li>
            </ul>
          </div>
          </div>





          <script>
            mw.dropdown();
          </script>
       </div>

    </td>
    </tr>
    <tr>
    <td colspan="2">
       <h2>Rich-text Editor</h2>

       <div id="editor-demo" style="width: 500px;height: 300px;"></div>


       <script>

       mw.editor(mwd.getElementById('editor-demo'));

       </script>

    </td>
    </tr>
    <tr>
    <td colspan="2">
       <h2>Navigations</h2>



         <ul class="mw-ui-navigation" style="width: 150px;">
           <li><a href="javascript:;" class="active">Home</a></li>
           <li><a href="javascript:;">About</a><ul>
                       <li><a href="javascript:;">Lorem Ipsum</a></li>
                       <li><a href="javascript:;">Etiam condimentum</a><ul>
                       <li><a href="javascript:;">Lorem Ipsum</a></li>
                       <li><a href="javascript:;">Etiam condimentum</a></li>
                       <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                       <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                       <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                       <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                     </ul></li>
                       <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                       <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                       <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                       <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                     </ul></li>
           <li><a href="javascript:;">Blog</a></li>
           <li><a href="javascript:;">Forum</a></li>
           <li><a href="javascript:;">Help</a></li>
           <li><a href="javascript:;">Contacts</a></li>
         </ul>
         <br><br>
         <ul class="mw-ui-box mw-ui-navigation" style="width: 150px;">
           <li><a href="javascript:;" class="active">Home</a></li>
           <li>
                <a href="javascript:;">About</a>
                <ul>
                   <li><a href="javascript:;">Lorem Ipsum</a></li>
                   <li><a href="javascript:;">Etiam condimentum</a>
                    <ul>
                       <li><a href="javascript:;">Lorem Ipsum</a></li>
                       <li><a href="javascript:;">Etiam condimentum</a></li>
                       <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                       <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                       <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                       <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                     </ul>
                   </li>
                   <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                   <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                   <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                   <li>
                        <a href="javascript:;">Vestibulum porta eros purus</a>

                   </li>
                 </ul>
           </li>
           <li><a href="javascript:;">Blog</a></li>
           <li><a href="javascript:;">Forum</a></li>
           <li><a href="javascript:;">Help</a></li>
           <li><a href="javascript:;">Contacts</a></li>
         </ul>
         <br><br>
         <ul class="mw-ui-navigation mw-ui-navigation-horizontal">
           <li><a href="javascript:;" class="active">Home<span class="mw-icon-gear"></span></a></li>
           <li>
                <a href="javascript:;">About <span class="mw-icon-dropdown"></span></a>
                <ul>
                 <li><a href="javascript:;">Lorem Ipsum</a></li>
                 <li>
                    <a href="javascript:;">Etiam condimentum</a>
                     <ul>
                       <li><a href="javascript:;">Lorem Ipsum</a></li>
                       <li><a href="javascript:;">Etiam condimentum</a></li>
                       <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                       <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                       <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                       <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
                     </ul>
                 </li>
                 <li><a href="javascript:;" class="active"><span class="mw-icon-gear"></span> Sed aliquam erat id mauri</a></li>
                 <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                 <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                 <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
               </ul>
           </li>
           <li><a href="javascript:;">Blog</a></li>
           <li><a href="javascript:;">Forum</a></li>
           <li><a href="javascript:;">Help</a></li>
           <li><a href="javascript:;">Contacts</a></li>
         </ul>
        <br><br>  <br><br><br><br>
         <ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-horizontal">
           <li>
              <a href="javascript:;" class="active">Home</a>
              <ul>
               <li><a href="javascript:;">Lorem Ipsum</a></li>
               <li>
                <a href="javascript:;">Etiam condimentum</a>
                <ul>
                 <li><a href="javascript:;">Lorem Ipsum</a></li>
                 <li><a href="javascript:;">Etiam condimentum</a></li>
                 <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
                 <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
                 <li><a href="javascript:;">Cras interdum enim dolor</a></li>
                 <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
               </ul>
               </li>
               <li><a href="javascript:;" class="active">Sed aliquam erat id mauri</a></li>
               <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
               <li><a href="javascript:;">Cras interdum enim dolor</a></li>
               <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
             </ul>
           </li>
           <li><a href="javascript:;">About</a></li>
           <li><a href="javascript:;">Blog</a></li>
           <li><a href="javascript:;">Forum</a></li>
           <li>
            <a href="javascript:;">Help</a>
            <ul>
               <li><a href="javascript:;" class="active">Lorem Ipsum</a></li>
               <li><a href="javascript:;">Etiam condimentum</a></li>
               <li><a href="javascript:;">Sed aliquam erat id mauri</a></li>
               <li><a href="javascript:;">Nullam luctus ut libero sit</a></li>
               <li><a href="javascript:;">Cras interdum enim dolor</a></li>
               <li><a href="javascript:;">Vestibulum porta eros purus</a></li>
             </ul>
           </li>
           <li><a href="javascript:;">Contacts</a></li>

         </ul>
    </td>
    </tr>
     <tr>
        <td colspan="2">
            <h2>Inline radios and</h2>
            <ul class="mw-ui-inline-list">
          <li><span>Choose</span></li>
          <li>
            <label class="mw-ui-check">
                <input type="radio" value="pending" name="order_status" checked="checked">
                <span></span><span>Option 1</span>
            </label>
          </li>
          <li>
            <label class="mw-ui-check">
                <input type="radio" value="completed" name="order_status">
                <span></span><span>Option 2</span>
            </label>
          </li>
        </ul>
        <hr>
        <ul class="mw-ui-inline-list">
          <li><span>Choose</span></li>
          <li>
            <label class="mw-ui-check">
                <input type="checkbox" value="pending" name="order_status1" checked="checked">
                <span></span><span>Option 1</span>
            </label>
          </li>
          <li>
            <label class="mw-ui-check">
                <input type="checkbox" value="completed" name="order_status1">
                <span></span><span>Option 2</span>
            </label>
          </li>
        </ul>

        </td>
     </tr>
      <tr>
        <td colspan="2">
            <h2>Modal Window</h2>

            <span class="mw-ui-btn" onclick="mw.modal({})">Default</span>

            <span class="mw-ui-btn" onclick="mw.modal({template:'basic'})">Simple</span>

            <span class="mw-ui-btn" onclick="mw.modalFrame({url:'http://microweber.com'})">Iframe</span>
       </td>
     </tr>

     <tr>
        <td colspan="2">
            <h2>Gallery</h2>

            <span class="mw-ui-btn" onclick="mw.gallery([{img:'http://lorempixel.com/1000/1000/nature/1',description:'Some description'},{img:'http://lorempixel.com/1000/1000/nature/2',description:'Some other description'}])"> Click to launch </span>

       </td>
     </tr>

     <tr>
        <td colspan="2">
            <h2>Accordion</h2>

            <div class="mw-ui-row">
                <div class="mw-ui-col" style="width: 140px;"><div class="mw-ui-col-container"><div id="accordion-example" onclick="mw.accordion(this);">
                Basic example
                <div class="mw-accordion-content">
                    Lorem Ipsum
                </div>
            </div></div></div>
                <div class="mw-ui-col">
                   <div class="mw-ui-col-container"><div id="accordion-example2" class="mw-ui-box">
                <div class="mw-ui-box-header" onclick="mw.accordion('#accordion-example2');">Another Example</div>
                <div class="mw-accordion-content mw-ui-box-content">
                    Lorem Ipsum
                </div>
            </div></div>
                </div>

                <div class="mw-ui-col" style="width: 150px;">
                    <div class="mw-ui-col-container"><span class="mw-ui-btn pull-right" onclick="mw.accordion('#accordion-example2');">Remote control</span></div>
                </div>
            </div>






       </td>
     </tr>
     <tr>
        <td colspan="2">
            <h2>TESTS</h2>



            <input type="" onkeyup="" />






       </td>
     </tr>

  </tbody>
</table>



 