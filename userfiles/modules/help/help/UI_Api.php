
   <style>

   #UITU{height: 5px;overflow: hidden;box-shadow:0 0 4px #BBBBBB;}#UITU.active{height: auto;padding: 30px;#}

   .helptable > tbody > tr > td{
        padding: 20px;
        border: 1px solid #ccc;
   }

  .helptable{
     border-collapse: collapse;
     table-layout: fixed;
   }

   .helptable > tbody > tr:nth-child(2n+1) > td{
     background: white;
   }

   .helptable > tbody > tr > td:last-child textarea{
     width: 150px;
   }



   #demo-icons *{
     float: none;
   }

</style>






<div class="mw_simple_tabs mw_tabs_layout_stylish" style="padding: 22px;">
  <ul style="border:none;" class="mw_simple_tabs_nav ">
    <li><a class="active" href="javascript:;">CSS</a></li>
    <li><a href="javascript:;">JavaScrict</a></li>
    <li><a href="javascript:;">WYSIWYG</a></li>
    <li><a href="javascript:;">Modals and pedefined Modals</a></li>
  </ul>
  <div class="tab">

     <table width="100%" class="helptable" cellspacing="0">
   <col width="70%" />
   <col width="30%" />
   <tbody>


    <tr>
      <td>
        <a href="javascript:;" class="mw-ui-btn">Button</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-blue">Button</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-green">Button</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-red">Button</a>
     </td>
      <td><?php highlight_string ('<a href="javascript:;" class="mw-ui-btn">Button</a>'); ?></td>
    </tr>
    <tr>
      <td>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium">Medium</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-blue">Medium</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-green">Medium</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium mw-ui-btn-red">Medium</a>
      </td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium">Medium</a></textarea></td>
    </tr>
    <tr>
      <td>

        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small">Small</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small  mw-ui-btn-blue">Small</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small  mw-ui-btn-green">Small</a>
        <a href="javascript:;" class="mw-ui-btn mw-ui-btn-small  mw-ui-btn-red">Small</a>

      </td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn mw-ui-btn-small">Small</a></textarea></td>
    </tr>

    <tr>
        <td><a href="javascript:;" class="mw-ui-btn mw-ui-btn-hover">Button on hover</a></td>
        <td><textarea><a href="javascript:;" class="mw-ui-btn mw-ui-btn-hover">Button on hover</a></textarea></td>
    </tr>
    <tr>
        <td><a href="javascript:;" class="mw-ui-btn mw-ui-btn-hover mw-ui-btn-hover-blue">Button on hover - Blue</a></td>
        <td><textarea><a href="javascript:;" class="mw-ui-btn mw-ui-btn-hover mw-ui-btn-hover-blue">Button on hover - Blue </a></textarea></td>
    </tr>
    <tr>
        <td><a href="javascript:;" class="mw-ui-link">Simple link</a></td>
        <td><textarea><a href="javascript:;" class="mw-ui-link">Simple link</a></textarea></td>
    </tr>
    <tr>
        <td><a href="javascript:;" class="mw-ui-btn-action">Action Button</a></td>
        <td><textarea><a href="javascript:;" class="mw-ui-action">Action Button</a></textarea></td>
    </tr>



    <tr>
        <td><a href="javascript:;" class="mw-ui-btn mw-btn-single-ico"><span class="ico iproduct"></span></a></td>
        <td><textarea><a href="javascript:;" class="mw-ui-btn mw-btn-single-ico"><span class="ico iproduct"></span></a></textarea></td>
    </tr>

    <tr>
      <td><a href="javascript:;" class="mw-ui-btn"><span class="ico iorder"></span><span>Button with icon</span></a></td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn"><span class="ico iorder"></span><span>Button with icon</span></a></textarea></td>
    </tr>

    <tr>
      <td>

          <select class="mw-ui-field">
            <option value="dropdown" selected="selected">Dropdown</option>
          </select>
 
    </td>
      <td><textarea>
          <select class="mw-ui-field">
            <option value="dropdown" selected="selected">Dropdown</option>
          </select>
       </textarea></td>
    </tr>
    <tr>
    <td>
    <h2>Button with Dropdown</h2>

    <div class="mw-ui-dropdown">
        <a class="mw-ui-btn">Button with Dropdown<span class="ico idownarr right"></span></a>
        <div class="mw-dropdown-content">
        <ul class="mw-dropdown-list">
            <li><a href="javascript:;">Item</a></li>
            <li><a href="javascript:;">Other Item</a></li>
            <li><a href="javascript:;">Other Other Item</a></li>

        </ul>
</div>
        </div>

    </td>
    <td></td>
    </tr>

    <tr>
      <td>
          <select class="mw-ui-simple-dropdown">
            <option value="dropdown" selected="selected">Simple Dropdown</option>
          </select>
    </td>
      <td><textarea>
          <select class="mw-ui-simple-dropdown">
            <option value="dropdown" selected="selected">Simple Dropdown</option>
          </select>
      </textarea></td>
    </tr>




    <tr>
      <td>
          <label class="mw-ui-check"><input type="checkbox" /><span></span></label>
    </td>
      <td><textarea><label class="mw-ui-check"><input type="checkbox" /><span></span></label></textarea></td>
    </tr>
    <tr>
      <td>

      <label class="mw-ui-check"><input type="radio" /><span></span></label>

    </td>
      <td><textarea><label class="mw-ui-check"><input type="checkbox" /><span></span></label></textarea></td>
    </tr>
    <tr>
      <td>
      <input type="text" class="mw-ui-field" value="Text field" />
    </td>
      <td><textarea><input type="text" class="mw-ui-field" /></textarea></td>
    </tr>

    <tr>
      <td>
      <input type="text" class="mw-ui-searchfield" value="Search" />
    </td>
      <td><textarea><input type="text" class="mw-ui-searchfield" value="Search" /></textarea></td>
    </tr>







    <tr>
      <td>

      <textarea class="mw-ui-field">Text Area</textarea>
    </td>
      <td><textarea><textarea class="mw-ui-field"></textarea></textarea></td>
    </tr>







    <tr>
      <td>

      <div class="mw-notification mw-success">
    <div>
      <span class="ico icheck"></span>
      <span>Success</span>
    </div>
  </div>
    </td>
      <td><textarea><div class="mw-notification mw-success">
    <div>
      <span class="ico icheck"></span>
      <span>Success</span>
    </div>
  </div></textarea></td>
    </tr>

    <tr>
      <td>

      <div class="mw-notification mw-warning">
    <div>
    Warning
    </div>
  </div>
    </td>
      <td><textarea><div class="mw-notification mw-warning">
    <div>
    Warning
    </div>
  </div></textarea></td>
    </tr>
    <tr>
      <td>

      <div class="mw-notification mw-error">
    <div>
    Error
    </div>
  </div>
    </td>
      <td><textarea><div class="mw-notification mw-error">
    <div>
    Error
    </div>
  </div></textarea></td>
    </tr>

    <tr>
      <td>

      <div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable"><span class="mw-switch-handle"></span>
                <label>ON<input type="radio" name="x" /></label>
                <label>OFF<input type="radio" name="x"  /></label>
            </div>
    </td>
      <td><textarea><div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable"><span class="mw-switch-handle"></span>
                <label>ON<input type="radio" name="x" /></label>
                <label>OFF<input type="radio" name="x"  /></label>
            </div></textarea></td>
    </tr>











    <tr>
      <td colspan="2">

      <h2>Icons</h2>
<div id="demo-icons">
<span class="ico ipage"></span>
<span class="ico ipage2"></span>
<span class="ico ieditpage"></span>
<span class="ico ipost"></span>
<span class="ico icategory"></span>
<span class="ico iproduct"></span>
<span class="ico iorder"></span>
<span class="ico iorder-big"></span>
<span class="ico icomment"></span>
<span class="ico imanage-website"></span>
<span class="ico imanage-module"></span>
<span class="ico iupgrade"></span>
<span class="ico inotification"></span>
<span class="ico iupload"></span>
<span class="ico itruck"></span>
<span class="ico ireport"></span>
<span class="ico isuggest"></span>
<span class="ico ihelp"></span>
<span class="ico iusers"></span>
<span class="ico ilive"></span>
<span class="ico ilogout"></span>
<span class="ico ipromo"></span>
<span class="ico ioptions"></span>
<span class="ico iupdate"></span>
<span class="ico iSingleText"></span>
<span class="ico iPText"></span>
<span class="ico iRadio"></span>
<span class="ico iName"></span>
<span class="ico iPhone"></span>
<span class="ico iWebsite"></span>
<span class="ico iEmail"></span>
<span class="ico iUpload"></span>
<span class="ico iNumber"></span>
<span class="ico iChk"></span>
<span class="ico iDropdown"></span>
<span class="ico iDate"></span>
<span class="ico iTime"></span>
<span class="ico iAddr"></span>
<span class="ico iPrice"></span>
<span class="ico iSpace"></span>
<span class="ico iAdd"></span>
<span class="ico iRemove"></span>
<span class="ico iAdd2"></span>
<span class="ico iRemove2"></span>
<span class="ico icheck"></span>
<span class="ico iDone"></span>
<span class="ico iplus"></span>
<span class="ico iset"></span>
<span class="ico ilaquo"></span>
<span class="ico ifullscreen"></span>
<span class="ico no-fullscreen"></span>
<span class="ico ismall_warn"></span>
<span class="ico irefresh"></span>
<span class="ico ipage_arr_up"></span>
<span class="ico iMove"></span>
<span class="ico iusers-big"></span>
<span class="ico backico"></span>
<span class="ico iexcell"></span>
<span class="ico icotype-page"></span>
<span class="ico icotype-dynamicpage"></span>
<span class="ico icotype-shop"></span>



        <span class="mw-ui-arr mw-ui-arr-up"></span>
        <span class="mw-ui-arr mw-ui-arr-down"></span>
        <span class="mw-ui-arr mw-ui-arr-left"></span>
        <span class="mw-ui-arr mw-ui-arr-right"></span>
</div>
    </td>

    </tr>


    <tr>

        <td>
            <h2>Combining fields and icons</h2>

            <div class="mw-ui-field mw-ico-field">
                <span class="ico iplay"></span>
                <input type="text"  class="mw-ui-invisible-field" />
            </div>

            <div class="mw-ui-field mw-ico-field">
                <span class="ico ireport"></span>
                <input type="text"  class="mw-ui-invisible-field" />
            </div>

            <div class="mw-ui-field mw-ico-field">
                <span class="ico iWebsite"></span>
                <input type="text"  class="mw-ui-invisible-field" />
            </div>

            <div class="mw-ui-field mw-ico-field">
                <span class="ico iorder"></span>
                <input type="text"  class="mw-ui-invisible-field" />
            </div>





        </td>
        <td></td>
    </tr>


    <tr>
    <td colspan="2">

    <h5>Klasove</h5>

    <ol style="font: bold 14px Lucida Console" onclick="mw.wysiwyg.select_element(event.target);">
        <li>unselectable</li>
        <li>mw_clear</li>
        <li>vSpace</li>
        <li>left</li>
        <li>right</li>
        <li>semi_hidden</li>
    </ol>

    </td>
    </tr>



    <tr>
        <td>
            <div class="mw-admin-side-nav">
                <li><a href="javascript:;">Side nav</a></li>
                <li><a href="javascript:;" class="active">Side nav active</a></li>
                <li><a href="javascript:;">Side nav</a></li>
            </div>
        </td>
        <td>Admin side nav</td>
    </tr>

    <tr>
        <td>

        <h2>Vetical Navigations</h2>

        <ul class="mw-quick-links left">
           <li>
              <a href="javascipt:;">
              <span class="ico ipage"></span>
                <span>Link</span>
              </a>
           </li>
           <li>
              <a href="javascipt:;">
              <span class="ico ipage"></span>
                <span>Link</span>
              </a>
           </li>
        </ul>

        <ul class="mw-quick-links mw-quick-links-green left">
           <li>
              <a href="javascipt:;">
                <span class="mw-ui-btn-plus">&nbsp;</span>
                <span class="ico ipage"></span>
                <span>Link</span>
              </a>
           </li>
           <li>
              <a href="javascipt:;">
                <span class="mw-ui-btn-plus">&nbsp;</span>
                <span class="ico ireport"></span>
                <span>Link</span>
              </a>
           </li>
        </ul>

        <ul class="mw-quick-links mw-quick-links-blue left">
           <li>
              <a href="javascipt:;">
                <span class="mw-ui-btn-plus">&nbsp;</span>
                <span class="ico ipage"></span>
                <span>Link</span>
              </a>
           </li>
           <li>
              <a href="javascipt:;">
                <span class="mw-ui-btn-plus">&nbsp;</span>
                <span class="ico ireport"></span>
                <span>Link</span>
              </a>
           </li>
        </ul>


        </td>
        <td></td>

    </tr>
    <tr>

    <td>
        <h2>Progress Bar</h2>
        <div class="mw-ui-progress">
            <div class="mw-ui-progress-bar" style="width: 60%"></div>
            <div class="mw-ui-progress-info">Status: <span class="mw-ui-progress-percent">60%</span></div>
        </div>
    </td>
    <td></td>

    </tr>



    <tr>
        <td>
            <div class="mw-ui-box"><div class="mw-ui-box-header"><span class="ico ireport"></span><span>Hi I'm 'O' Box</span></div>Lorem Ipsum</div>

        </td>
        <td>The 'O' Box</td>
    </tr>


    <tr>
    <td><table cellspacing="0" cellpadding="0" class="mw-ui-admin-table" width="550">
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
        <td><a href="javascript:;" class="mw-ui-admin-table-show-on-hover mw-ui-btn">View on hover</a></td>
    </tr>
  </tbody>
</table></td>
    <td></td>
    </tr>


   </tbody>

  </table>

  </div>
  <div class="tab">

    <table width="100%" cellpadding="0" cellspacing="0" class="helptable">
    <col width="70%" />
   <col width="30%" />
      <tr>
        <td>
           <h2>Accordion applied to a box</h2>
            <div class="mw-ui-box mw-ui-box-accordion">
                <div class="mw-ui-box-header" onclick="mw.tools.accordion(this.parentNode);">
                    <span class="ico iTime"></span><span>Accordion</span>
                </div>
                <div class="mw-ui-box-content mw-accordion-content">
                    Accordion content
                </div>
            </div>





        </td>
        <td></td>
    </tr>
      <tr>
        <td>
        <h2>Accordion without stlyes</h2>
           <div>
                <div onclick="mw.tools.accordion(this.parentNode);">
                    Basic Accordion
                </div>
                <div class="mw-accordion-content">
                    Basic Accordion content
                </div>
            </div>
        </td>
        <td></td>
      </tr>
      <tr>
        <td><h2>Accodion applied to an unordered list </h2>
        <ul>
                <li>
                    <span onclick="mw.tools.accordion(this.parentNode);">List Accordion</span>
                    <ul class="mw-accordion-content hidden">
                        <li>List Accordion content</li>
                    </ul>
                </li>
            </ul>
        </td>
        <td></td>
      </tr>

      <tr>
        <td id="qwewqeqw" style="padding: 20px 0;">
          <script>
          $(document).ready(function(){
            mw.simpletabs();
          });
          </script>
          <h2>Tabs</h2>
          <div class="mw_simple_tabs mw_tabs_layout_stylish" id="tabsDEMO">
            <ul class="mw_simple_tabs_nav" style="margin: 0;">
              <li><a href="javascript:;" class="active">Tab 1</a></li>
              <li><a href="javascript:;">Tab 2</a></li>
            </ul>
            <div class="tab">tab 1</div>
            <div class="tab">tab 2</div>
          </div>

        </td>
        <td>
            Stylish <input name="qwewqeqw" type="radio" checked="checked" onchange="$('#tabsDEMO').attr('class', 'mw_simple_tabs mw_tabs_layout_stylish')" />
            Simple <input name="qwewqeqw" type="radio" onchange="$('#tabsDEMO').attr('class', 'mw_simple_tabs mw_tabs_layout_simple')" />
        </td>
    </tr>

      <tr>
        <td>

        <script>
         $(document).ready(function(){
            mw.tools.dropdown();
         })


        </script>

        <h2>Dropdowns</h2>
        <div data-value=""  class="mw_dropdown mw_dropdown_type_navigation">
            <span class="mw_dropdown_val" style="width: 200px;">Default</span>
            <div class="mw_dropdown_fields">
                <ul>
                    <li><a href="javascript:;">Value</a></li>
                    <li><a href="javascript:;">OtheValue</a></li>
                </ul>
            </div>
        </div>
         <hr />
        <div data-value=""  class="mw_dropdown mw_dropdown_type_navigation mw_dropdown_autocomplete">
            <span class="mw_dropdown_val" style="width: 200px;">With textfield</span>
            <input type="text" class="mw-ui-field dd_search" style="width: 208px;">
            <div class="mw_dropdown_fields">
                <ul>
                    <li><a href="javascript:;">Value</a></li>
                    <li><a href="javascript:;">OtheValue</a></li>
                </ul>
            </div>
        </div>

       <div class="mw_clear"></div>

     <hr />

        <div data-value="" title="" class="mw_dropdown mw_dropdown_type_wysiwyg">
                <span class="mw_dropdown_val_holder">
                    <span class="dd_rte_arr"></span>
                    <span class="mw_dropdown_val">Simple</span>
                </span>
              <div class="mw_dropdown_fields" style="display: block;">
                <ul>
                  <li value="Value1"><a href="#">Value 1</a></li>
                  <li value="Value2"><a href="#">Value 2</a></li>
                  <li value="Value3"><a href="#">Value 3</a></li>
                </ul>
</div>
            </div>
           <div class="mw_clear"></div>
            <hr />

             <div class="mw_clear"></div>
            <h2>Dropdown API</h2>

            <dl class="help-api">

                <dt>$(".my-dropdown").setDropdownValue(value, trigger_change)</dt>
                <dd>Sets value for a dropdown. If second parameter is true the 'change' event will be triggered.</dd>
                <dt>$(".my-dropdown").getDropdownValue()</dt>
                <dd>Gets value for a dropdown. </dd>

                <dt>Example usage of change event</dt>
                <dd>
                <pre>
$(".my-dropdown").bind("change", function(){
   var val = $(this).getDropdownValue();
});
</pre>
                </dd>


            </dl>

        </td>
        <td></td>
      </tr>
      <tr>
        <td>
         <h2>Tag</h2>

        </td>
        <td></td>
      </tr>
      <tr>
        <td>
            <h2></h2>

        </td>
        <td></td>
      </tr>
    </table>
  </div>
  <div class="tab">

   <table width="100%" cellpadding="0" cellspacing="0" class="helptable">
      <col width="70%" />
      <col width="30%" />
   <tr>
      <td>
      <textarea id="editorDEMO">Editor</textarea>
      <script>
        var editor = mw.tools.iframe_editor(mwd.getElementById('editorDEMO'));
        editor.style.width = '100%';
        editor.style.height = '250px';
        editor.style.resize = 'both';
      </script>
    </td>
      <td><textarea>&lt;textarea id="editorDEMO"&gt;Editor&lt;/textarea&gt;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &lt;script&gt;var editor = mw.tools.iframe_editor(mwd.getElementById('editorDEMO')); editor.style.width = '100%';&lt;/script&gt;</textarea></td>
    </tr>

    <tr>
        <td>
            <h2>API</h2>

            <h3>mw.wysiwyg</h3>
            <br />

             <dl class="help-api">
                <dt>mw.wysiwyg.execCommand('command')</dt>
                <dd>Executes a command</dd>

                <dt>mw.wysiwyg.applier(<strong>tag</strong>, <strong>classname</strong>)</dt>
                <dd>Wraps selected text in a specified tag and a classname.</dd>
                <dt>mw.wysiwyg.insert_html(html)</dt><dd>Replaces selection with an specified html</dd>
                <dt>mw.wysiwyg.save_selection()</dt><dd>Saves current selection.</dd>
                <dt>mw.wysiwyg.restore_selection()</dt><dd>Restores last saved selection</dd>
                <dt>mw.wysiwyg.select_all(node)</dt><dd>Selects all the content inside an element</dd>
                <dt>mw.wysiwyg.select_element(node)</dt><dd>Selects an element</dd>
                <dt></dt><dd></dd>
             </dl>

            </td>
        <td></td>
    </tr>


   </table>







  </div>

  <div class="tab">



   <table width="100%" cellpadding="0" cellspacing="0" class="helptable">

   <tr>
      <td>
             <h2>Modals</h2>
  <a href="javascript:;" class="mw-ui-btn" onclick="mw.tools.modal.init({html:'Test <span onclick=\'parent.mw.tools.modal.overlay()\' class=\'mw-ui-btn\'>add overlay</span>'})">Default</a>
  <div class="vSpace"></div>
  <a href="javascript:;" class="mw-ui-btn" onclick="mw.tools.modal.init({html:'Test', template:'mw_modal_simple'})">Custom Template</a>
  <div class="vSpace"></div>
  <a href="javascript:;" class="mw-ui-btn" onclick="testMedia()">Media upload with callback</a>
  <script>

testMedia = function(){
mw.tools.modal.frame({
          url:"rte_image_editor#someFUNC",
          name:"mw_rte_image",
          width:430,
          height:230,
          template:'mw_modal_simple',
          overlay:true
        });

}


   someFUNC  = function(url){
     alert("Uploaded: " + url)
   }

  </script>


    <div class="vSpace"></div>

      <div class="vSpace"></div>
  <a href="javascript:;" class="mw-ui-btn" onclick="testlink()">Link management</a>
  <script>

testlink = function(){
mw.tools.modal.frame({
          url:"rte_link_editor#someFUNC2",
          name:"qwet",
          width:430,
          height:230,
          template:'mw_modal_simple'
        });

}


   someFUNC2  = function(url){
     alert("Uploaded: " + url)
   }

  </script>


    <div class="vSpace"></div>


  <h2>API</h2>

    <pre id="modal_test">
mw.tools.modal.init({
    html:'Test',
    width:600,
    height:300,
    template:'some_template_name',
    title:'Some title',
    name:'uniqe_modal',
    overlay:true or false;
});

mw.tools.modal.frame({
    html:'Test',
    width:600,
    height:300,
    template:'some_template_name',
    title:'Some title',
    name:'uniqe_modal',
    overlay:true or false;
});

    </pre>

      </td>

     </tr>
    </table>
  </div>
</div>











  <div class="hr">&nbsp;</div>





