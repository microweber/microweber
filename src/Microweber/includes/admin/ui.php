
<style>
#UITU{height: 5px;overflow: hidden;box-shadow:0 0 4px #BBBBBB;}#UITU.active{height: auto;padding: 30px;#} #qweqwe98989898 > tbody > tr > td{
        padding: 20px;
        border: 1px solid #ccc;
   }

   #qweqwe98989898 > tbody > tr:nth-child(2n+1) > td{
     background: white;
   }

   #qweqwe98989898 > tbody > tr > td:last-child > textarea{
     width: 150px;
   }

</style>
<textarea style="position:absolute;top:10px;left:230px;width:350px;"  onchange="eval(this.value);" placeholder="Console" cols="40" rows="1"></textarea>
<div style="position: absolute;top:10px;right: 10px">
  <label class="mw-ui-label left">Rel&nbsp;</label>
  <input type="text" onkeyup="$('.element-current').attr('rel', this.value);" />
</div>
<meter min="0" max="100" low="40" high="90" optimum="100" value="91">A+</meter>
<span class="mw-ui-btn" onclick="mw.dump();">DUMP!</span>
<table width="800" id="qweqwe98989898" style="margin: auto;border-collapse: collapse">
  <col width="60%" />
  <col width="40%" />
  <tbody>

    <tr>
        <td colspan="2"><h1>Javascript UI</h1></td>

    </tr>

    <tr>
        <td><h2>Modal</h2>

<pre>
   mw.modal({
      content:   Required: String or Node Element or jquery Object
      width:     Optional: ex: 400 or "85%", default 600
      height:    Optional: ex: 400 or "85%", default 500
      draggable: Optional: Boolean, default true
      overlay:   Optional: Boolean, default false
      title:     Optional: String for the title of the modal
      template:  Optional: String
      id:        Optional: String: set this to protect from multiple instances
    });
</pre>

        <span class="mw-ui-btn" onclick="mw.modal({content:'Test!'});">Run Example</span>   </td>

        <td><textarea>mw.modal({content:'Test!'});</textarea></td>
    </tr>





    <tr>
        <td><h2>Text Editor</h2>

        <pre>mw.editor(Node);</pre>

        <h3>Example</h3>


        <script>
            $(document).ready(function(){
              var editor = mw.editor(document.querySelector('#WYSIWYGEXAMPLE'));
              $(editor).css({width:650,height:300})
            });
        </script>

        <textarea id="WYSIWYGEXAMPLE"></textarea>
         </td>
         <td>
              <textarea>mw.editor(document.querySelector('#some_element'));</textarea>
         </td>
    </tr>

     <tr>
        <td><h2>Gallery</h2>
        <script>

          GALLERYEXAMPLE = [
            {img:"http://static.tumblr.com/07849649bb8718c39c77e5acad91d6ec/qv70mpt/2KZmo81on/tumblr_static_nordic-forest-wall-mural.jpg", description:"Cool Forest"},
            {img:"http://31.media.tumblr.com/df82a96355c8c7717a3b233812db35ae/tumblr_mxj1y9GN6o1szcxfio1_1280.png", description:"Fastest Car"},
            {img:"http://fc04.deviantart.net/fs71/f/2012/206/7/1/nature__s_eternal_glory_by_florentcourty-d58jkkw.jpg", description:"Sun rise"}
          ];



        </script>

        <span class="mw-ui-btn" onclick="mw.gallery(GALLERYEXAMPLE);">Run Example</span>

        </td>


        <td><textarea>mw.gallery();</textarea></td>

     </tr>



     <!-- --------------------------------------------------------------------------------------------------------------------------- -->



    <tr>
        <td colspan="2"><h1>CSS UI</h1></td>

    </tr>
    <tr>
        <td>

            <h2>Columns</h2>

                <div class="mw-ui-row">
                    <div class="mw-ui-col">Column 1</div>
                    <div class="mw-ui-col">Column 2</div>
                    <div class="mw-ui-col">Column 3</div>
                </div>

        </td>

        <td><textarea><div class="mw-ui-row">
                    <div class="mw-ui-col">Column 1</div>
                    <div class="mw-ui-col">Column 2</div>
                    <div class="mw-ui-col">Column 3</div>
                </div></textarea></td>
    </tr>
    <tr>
      <td><button class="mw-ui-btn">Button</button></td>
      <td><textarea><button class="mw-ui-btn">Button</button>
</textarea></td>
    </tr>
    <tr>
      <td><input type="submit" class="mw-ui-btn" value="Submit" /></td>
      <td><textarea><input type="submit" class="mw-ui-btn" value="Submit" />
</textarea></td>
    </tr>
    <tr>
      <td><a href="javascript:;" class="mw-ui-btn">Link</a></td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn">Link</a></textarea></td>
    </tr>
    <tr>
      <td><a href="javascript:;" class="mw-ui-btn mw-ui-btn-small">Small Button</a></td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn mw-ui-btn-small">Small Button</a></textarea></td>
    </tr>
    <tr>
      <td><a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium">medium Button</a></td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn mw-ui-btn-medium">medium Button</a></textarea></td>
    </tr>
    <tr>
      <td><a href="javascript:;" class="mw-ui-btn-rect">Rect link</a></td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn-rect">Rect link</a></textarea></td>
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
      <td><a href="javascript:;" class="mw-ui-btn-rect mw-btn-single-ico"><span class="ico iorder"></span></a></td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn-rect mw-btn-single-ico"><span class="ico iorder"></span></a></textarea></td>
    </tr>
    <tr>
      <td><a href="javascript:;" class="mw-ui-btn"><span class="ico iorder"></span><span>Button with icon</span></a></td>
      <td><textarea><a href="javascript:;" class="mw-ui-btn"><span class="ico iorder"></span><span>Button with icon</span></a></textarea></td>
    </tr>
    <tr>
      <td><div class="mw-ui-select">
          <select >
            <option value="dropdown" selected="selected">Dropdown</option>
          </select>
        </div></td>
      <td><textarea><div class="mw-ui-select">
          <select >
            <option value="dropdown" selected="selected">Dropdown</option>
          </select>
       </div>
</textarea></td>
    </tr>
    <tr>
      <td><select class="mw-ui-simple-dropdown">
          <option value="dropdown" selected="selected">Simple Dropdown</option>
        </select></td>
      <td><textarea>
          <select class="mw-ui-simple-dropdown">
            <option value="dropdown" selected="selected">Simple Dropdown</option>
          </select>
      </textarea></td>
    </tr>
    <tr>
      <td><label class="mw-ui-check">
          <input type="checkbox" />
          <span></span></label></td>
      <td><textarea><label class="mw-ui-check"><input type="checkbox" /><span></span></label>
</textarea></td>
    </tr>
    <tr>
      <td><label class="mw-ui-check">
          <input type="radio" />
          <span></span></label></td>
      <td><textarea><label class="mw-ui-check"><input type="checkbox" /><span></span></label>
</textarea></td>
    </tr>
    <tr>
      <td><input type="text" class="mw-ui-field" value="Text field" /></td>
      <td><textarea><input type="text" class="mw-ui-field" />
</textarea></td>
    </tr>
    <tr>
      <td><input type="text" class="mw-ui-searchfield" value="Search" /></td>
      <td><textarea><input type="text" class="mw-ui-searchfield" value="Search" />
</textarea></td>
    </tr>
    <tr>
      <td><textarea class="mw-ui-field">Text Area</textarea></td>
      <td><textarea><textarea class="mw-ui-field"></textarea>
</textarea></td>
    </tr>
    <tr>
      <td><div class="mw-notification mw-success">
          <div> <span class="ico icheck"></span> <span>Success</span> </div>
        </div></td>
      <td><textarea><div class="mw-notification mw-success">
    <div>
      <span class="ico icheck"></span>
      <span>Success</span>
    </div>
  </div>
</textarea></td>
    </tr>
    <tr>
      <td><div class="mw-notification mw-warning">
          <div> Warning </div>
        </div></td>
      <td><textarea><div class="mw-notification mw-warning">
    <div>
    Warning
    </div>
  </div>
</textarea></td>
    </tr>
    <tr>
      <td><div class="mw-notification mw-error">
          <div> Error </div>
        </div></td>
      <td><textarea><div class="mw-notification mw-error">
    <div>
    Error
    </div>
  </div>
</textarea></td>
    </tr>
    <tr>
      <td><div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable"><span class="mw-switch-handle"></span>
          <label>ON
            <input type="radio" name="x" />
          </label>
          <label>OFF
            <input type="radio" name="x"  />
          </label>
        </div></td>
      <td><textarea><div onmousedown="mw.switcher._switch(this);" class="mw-switcher unselectable"><span class="mw-switch-handle"></span>
                <label>ON<input type="radio" name="x" /></label>
                <label>OFF<input type="radio" name="x"  /></label>
            </div>
</textarea></td>
    </tr>
    <tr>

    <td><div class="mw-onoff ">
                                    <label>No
                                        <input type="radio" value="n" class="semi_hidden is_active_n" name="is_active">
                                    </label>
                                    <label>Yes
                                        <input type="radio" checked="checked" value="y" class="semi_hidden is_active_y" name="is_active">
                                    </label>
                                </div></td>
    <td><textarea><div class="mw-onoff ">
                                    <label>No
                                        <input type="radio" value="n" class="semi_hidden is_active_n" name="is_active">
                                    </label>
                                    <label>Yes
                                        <input type="radio" checked="checked" value="y" class="semi_hidden is_active_y" name="is_active">
                                    </label>
                                </div></textarea></td>

    </tr>
    <tr>
      <td colspan="2"><h5>Ikoni</h5>
        <span class="ico ipage"></span> <span class="ico ipost"></span> <span class="ico icategory"></span> <span class="ico iproduct"></span> <span class="ico iorder"></span> <span class="ico icomment"></span> <span class="ico imanage-website"></span> <span class="ico imanage-module"></span> <span class="ico iupgrade"></span> <span class="ico inotification"></span> <span class="ico iupload"></span> <span class="ico ireport"></span> <span class="ico isuggest"></span> <span class="ico ihelp"></span> <span class="ico iusers"></span> <span class="ico itruck"></span> <span class="ico ilive"></span> <span class="ico ilogout"></span> <span class="ico iSingleText"></span> <span class="ico iPText"></span> <span class="ico iRadio"></span> <span class="ico iName"></span> <span class="ico iPhone"></span> <span class="ico iWebsite"></span> <span class="ico iEmail"></span> <span class="ico iUpload"></span> <span class="ico iNumber"></span> <span class="ico iChk"></span> <span class="ico iDropdown"></span> <span class="ico iDate"></span> <span class="ico iTime"></span> <span class="ico iAddr"></span> <span class="ico iPrice"></span> <span class="ico iSpace"></span> <span class="ico iAdd"></span> <span class="ico iRemove"></span> <span class="ico iMove"></span> <span class="ico ipromo"></span> <span class="ico ioptions"></span> <span class="ico icheck"></span> <span class="ico iset"></span> <span class="ico ilaquo"></span> <span class="ico iplus"></span> <span class="mw-ui-arr mw-ui-arr-up"></span> <span class="mw-ui-arr mw-ui-arr-down"></span> <span class="mw-ui-arr mw-ui-arr-left"></span> <span class="mw-ui-arr mw-ui-arr-right"></span></td>
    </tr>
    <tr>
      <td colspan="2"><h5>Klasove</h5>
        <ol style="font: bold 14px Lucida Console" onclick="mw.wysiwyg.select_element(event.target);">
          <li>unselectable</li>
          <li>mw_clear</li>
          <li>vSpace</li>
          <li>left</li>
          <li>right</li>
          <li>semi_hidden</li>
        </ol></td>
    </tr>
    <tr>
      <td id="qwewqeqw" style="padding: 20px 0;"><script>
          $(document).ready(function(){
            mw.simpletabs();
          });
          </script>
        <div class="mw_simple_tabs mw_tabs_layout_stylish">
          <ul class="mw_simple_tabs_nav" style="margin: 0;">
            <li><a href="javascript:;" class="active">Comments</a></li>
            <li><a href="javascript:;">Settings</a></li>
          </ul>
          <div class="tab">tab 1</div>
          <div class="tab">tab 2 :)</div>
        </div></td>
      <td> Stylish
        <input name="qwewqeqw" type="radio" checked="checked" onchange="$('#qwewqeqw .mw_simple_tabs').attr('class', 'mw_simple_tabs mw_tabs_layout_stylish')" />
        Simple
        <input name="qwewqeqw" type="radio" onchange="$('#qwewqeqw .mw_simple_tabs').attr('class', 'mw_simple_tabs mw_tabs_layout_simple')" /></td>
    </tr>
    <tr>
      <td><div class="mw-admin-side-nav">
          <li><a href="javascript:;">Side nav</a></li>
          <li><a href="javascript:;" class="active">Side nav active</a></li>
          <li><a href="javascript:;">Side nav</a></li>
        </div></td>
      <td>Admin side nav</td>
    </tr>
    <tr>
      <td><div class="mw-o-box">
          <div class="mw-o-box-header"><span class="ico ireport"></span><span>Hi I'm 'O' Box</span></div>
          Lorem Ipsum</div></td>
      <td>The 'O' Box</td>
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
      <td><a href="javascript:;" class="mw-ui-admin-table-show-on-hover mw-ui-btn">View on hover</a></td>
    </tr>
  </tbody>
</table>
        </td>
    </tr>
  </tbody>
</table>
<div class="hr">&nbsp;</div>

 