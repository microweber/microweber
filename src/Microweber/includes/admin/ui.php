

<style scoped="scoped">
    #ui-info-table{
      width: 80%;
      border: 1px solid #eee;
      margin: auto;
      border-collapse: collapse
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
    }

    #info-icon-list li:hover{
      color: white;
      background: #495074
    }

    #info-icon-list li em{
      display: block;
      text-align: center;
      font-style: normal;
      padding: 3px 0 10px 0;
    }
    #info-icon-list li span{
      font-size: 35px;
    }

    .demobox{
      position: relative;
      overflow: hidden;
      padding: 20px 0;
      max-width: 600px;
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

    <h3>Blue</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-blue">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-blue">Medium</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-blue">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-blue">Big</a>

    <h3>Green</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-green">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green">Medium</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-green">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-green">Big</a>

    <h3>Red</h3>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small mw-ui-btn-red">Small</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-red">Medium</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-red">Normal</a>
    <a href="javascript:;" class="mw-ui-btn mw-ui-btn-big mw-ui-btn-red">Big</a>

    <h3>Button with icon</h3>
    <div class="demobox"><a href="javascript:;" class="mw-ui-btn"><span class="mw-icon-website"></span>Normal</a></div>
    <h3>Button Navigations</h3>
<div class="demobox">    <div class="mw-ui-btn-nav">
        <a href="javascript:;" class="mw-ui-btn">Home</a>
        <a href="javascript:;" class="mw-ui-btn active">About</a>
        <a href="javascript:;" class="mw-ui-btn">Contact</a>
    </div></div>




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
        <table cellspacing="0" cellpadding="0" class="mw-ui-admin-table" width="100%">
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
      <td><a href="javascript:;" class="show-on-hover mw-ui-btn">View on hover</a></td>
    </tr>
  </tbody>
</table>
        </td>


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
        <div class="demobox">
            <label class="mw-ui-label">Normal</label>
            <input type="text" class="mw-ui-field" />
            <span class="mw-ui-btn"><span class="mw-icon-magnify"></span>Button</span>
        </div>
        <div class="demobox">
            <label class="mw-ui-label">Big</label>
            <input type="text" class="mw-ui-field mw-ui-field-big" />
            <span class="mw-ui-btn mw-ui-btn-big"><span class="mw-icon-magnify"></span>Button</span>
        </div>
        <div class="demobox">
            <label class="mw-ui-label">Medium</label>
            <input type="text" class="mw-ui-field mw-ui-field-medium" />
            <span class="mw-ui-btn mw-ui-btn-medium"><span class="mw-icon-magnify"></span>Button</span>
        </div>
       </td>

    </tr>

    </tr>
  </tbody>
</table>
<br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
<div class="hr">&nbsp;</div>

 