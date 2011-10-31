<!-- The application "window" -->

<div id="application">
  <? include('nav.php'); ?>
  <? $view = url_param('view'); ?>
  <? if(is_file(TEMPLATE_DIR. "layouts/members/".$view.'.php')) : ?>
  <? include TEMPLATE_DIR. "layouts/members/".$view.'.php'; ?>
  <? else : ?>
  <!-- Secondary navigation -->
  <nav id="secondary">
    <ul>
      <li class="current"><a href="#maintab">Main tab</a></li>
      <li><a href="#secondtab">Explanation tabs</a></li>
      <li><a href="#thirdtab">Optional third tab</a></li>
    </ul>
  </nav>
  <!-- The content -->
  <section id="content_tabs">
    <div class="tab" id="maintab">
      <h2>Forms</h2>
      <form class="wymupdate">
        <div class="column left">
          <section>
            <label for="label"> Label* </label>
            <div>
              <input id="label" name="label" type="text" placeholder="Only letters" class="required" />
            </div>
          </section>
          <section>
            <label for="username"> Username* <small>The username must consist of at least 3 characters</small> </label>
            <div>
              <input type="text" id="username" name="username" placeholder="Only letters" class="required" minlength="3" />
              <a href="#" class="button icon loop">Check availability</a> </div>
          </section>
          <section>
            <label for="password"> Password* <small>The password must consist of at least 6 characters</small> </label>
            <div>
              <input placeholder="At least 8 characters" name="password" id="password" type="password" class="required" minlength="6" />
              <input name="confirm_password" type="password" placeholder="Confirm password" />
            </div>
          </section>
          <section>
            <label for="address"> Address </label>
            <div> Please enter your <em>real</em> address.<br />
              <br />
              <input name="address" id="address" placeholder="Street" type="text" class="large" />
              <input placeholder="#" type="text" class="small" />
            </div>
          </section>
          <section>
            <label> Postal code </label>
            <div>
              <input placeholder="1234" type="text" class="small" />
              <input placeholder="AB" type="text" class="xsmall" />
            </div>
          </section>
        </div>
        <div class="column right">
          <section>
            <label for="textarea"> Textarea* <small>This textarea is freely resizable</small> </label>
            <div>
              <textarea class="required" id="textarea" name="textarea"></textarea>
            </div>
          </section>
          <section>
            <label for="tags"> Tags <small>Comma separated</small> </label>
            <div>
              <input type="text" class="tags" name="tags" id="tags" value="awesome,tags" />
            </div>
          </section>
          <section>
            <label> Dropdown menu <small>You can add groups</small> </label>
            <div>
              <select>
                <option>Option 1</option>
                <option>Option 2</option>
                <option>Option 3</option>
              </select>
            </div>
          </section>
          <section>
            <label> Radio buttons </label>
            <div>
              <div class="column left">
                <input type="radio" name="radio" id="button1" />
                <label for="button1">Radio button 1</label>
                <input type="radio" name="radio" id="button2" />
                <label for="button2">Radio button 2</label>
                <input type="radio" name="radio" id="button3" />
                <label for="button3">Radio button 3</label>
              </div>
              <div class="column right">
                <input type="radio" name="radio" id="button4" />
                <label for="button4">Radio button 4</label>
                <input type="radio" name="radio" id="button5" />
                <label for="button5">Radio button 5</label>
                <input type="radio" name="radio" id="button6" />
                <label for="button6">Radio button 6</label>
              </div>
              <div class="clear"></div>
            </div>
          </section>
          <section>
            <label>Check boxes</label>
            <div>
              <div class="column left">
                <input type="checkbox" name="checkbox" id="checkbox1" />
                <label for="checkbox1">Checkbox 1</label>
                <input type="checkbox" name="checkbox" id="checkbox2" />
                <label for="checkbox2">Checkbox 2</label>
                <input type="checkbox" name="checkbox" id="checkbox3" />
                <label for="checkbox3">Checkbox 3</label>
              </div>
              <div class="column right">
                <input type="checkbox" name="checkbox" id="checkbox4" />
                <label for="checkbox4">Checkbox 4</label>
                <input type="checkbox" name="checkbox" id="checkbox5" />
                <label for="checkbox5">Checkbox 5</label>
              </div>
              <div class="clear"></div>
            </div>
          </section>
        </div>
        <div class="clear"></div>
        <h2>Really awesome WYSIWYM-editor</h2>
        <textarea class="wysiwym"></textarea>
        <br />
        <p>
          <input type="submit" class="button primary submit" value="Submit" />
          <a href="#" class="button">Cancel</a> </p>
      </form>
      <div class="column right twothird">
        <table class="datatable">
          <thead>
            <tr>
              <th>Username</th>
              <th>Real name</th>
              <th>Email</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>Interfico</td>
              <td>Bram Jetten</td>
              <td>mail@bramjetten.nl</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>Thedude</td>
              <td>The Big Leboski</td>
              <td>realbig@dude.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>What up</td>
              <td>This is my name</td>
              <td>email@email.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>Another Guy</td>
              <td>His real name</td>
              <td>hisfake@email.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>Interfico</td>
              <td>Bram Jetten</td>
              <td>mail@bramjetten.nl</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>Thedude</td>
              <td>The Big Leboski</td>
              <td>realbig@dude.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>What up</td>
              <td>This is my name</td>
              <td>email@email.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>Another Guy</td>
              <td>His real name</td>
              <td>hisfake@email.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>Interfico</td>
              <td>Bram Jetten</td>
              <td>mail@bramjetten.nl</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>Thedude</td>
              <td>The Big Leboski</td>
              <td>realbig@dude.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>What up</td>
              <td>This is my name</td>
              <td>email@email.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
            <tr>
              <td>Another Guy</td>
              <td>His real name</td>
              <td>hisfake@email.com</td>
              <td><span class="button-group"> <a href="#" class="button icon edit">Edit</a> <a href="#" class="button icon remove danger">Remove</a> </span></td>
            </tr>
          </tbody>
        </table>
      </div>
      <h2>Quick actions</h2>
      <a href="#" class="button icon edit"> Create a new blogpost </a> <a href="#" class="button icon settings"> Update system </a> <a href="#" class="button icon user"> Manage users </a> <a href="#" class="button icon rss"> Notify some people </a>
      <div class="column left twothird">
        <h2>Recent visitors</h2>
        <table class="areachart">
          <thead>
            <tr>
              <td></td>
              <th scope="col">Jan</th>
              <th scope="col">Feb</th>
              <th scope="col">Mar</th>
              <th scope="col">Apr</th>
              <th scope="col">May</th>
              <th scope="col">Jun</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <th scope="row">2009</th>
              <td>24</td>
              <td>29</td>
              <td>47</td>
              <td>56</td>
              <td>23</td>
              <td>12</td>
            </tr>
            <tr>
              <th scope="row">2010</th>
              <td>12</td>
              <td>18</td>
              <td>23</td>
              <td>64</td>
              <td>43</td>
              <td>35</td>
            </tr>
            <tr>
              <th scope="row">2011</th>
              <td>8</td>
              <td>43</td>
              <td>48</td>
              <td>32</td>
              <td>12</td>
              <td>56</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="column right third">
        <h2>Create a new blogpost</h2>
        <form>
          <section>
            <label>Title*</label>
            <div>
              <input type="text" placeholder="Required" class="required" />
            </div>
          </section>
          <section>
            <label>Category</label>
            <div>
              <select>
                <option>Webdesign</option>
                <option>Graphic Design</option>
                <option>Literature</option>
              </select>
            </div>
          </section>
          <section>
            <label for="description">Short description*</label>
            <div>
              <textarea name="description" id="description" class="required"></textarea>
            </div>
          </section>
          <section>
            <label for="post">Post*</label>
            <div>
              <textarea id="post" name="post" class="required"></textarea>
            </div>
          </section>
          <section>
            <input type="submit" class="button primary big" value="Create post" />
          </section>
        </form>
      </div>
      <div class="clear"></div>
    </div>
    <div class="tab" id="secondtab">
      <h2>Explanation tabs</h2>
      <p>Your secondary menu should look something like this:</p>
      <pre>
&lt;nav id="secondary"&gt;
  &lt;ul&gt;
    &lt;li class="current"&gt;&lt;a href="#maintab"&gt;Main tab&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="#secondtab"&gt;Explanation tabs&lt;/a&gt;&lt;/li&gt;
    &lt;li&gt;&lt;a href="#thirdtab"&gt;Optional third tab&lt;/a&gt;&lt;/li&gt;
  &lt;/ul&gt;
&lt;/nav&gt;</pre>
      <p>If you use class="current" on a &lt;li&gt;-tag, that tab will be the default.</p>
      <p>In your content area, you must use the following structure:</p>
      <pre>
&lt;div class="tab" id="maintab"&gt;
&lt;/div&gt;

&lt;div class="tab" id="secondtab"&gt;
&lt;/div&gt;

&lt;div class="tab" id="thirdtab"&gt;
&lt;/div&gt;
</pre>
    </div>
    <div class="tab" id="thirdtab">
      <h2>Third tab</h2>
    </div>
  </section>
  <? endif; ?>
</div>
<!--<a class="sitebtn sitebtn_darkblue right" href="javascript:mw.users.LogOut();"><span>Logout</span></a>-->
