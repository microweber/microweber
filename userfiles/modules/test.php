<section id="grid-system">
  <div class="page-header">
    <h1>Grid system <small>Rock the standard 940px or roll your own</small></h1>
  </div>
  <div class="row">
    <div class="span4">
      <h2>Default grid</h2>
      <p>The default grid system provided as part of Bootstrap is a 940px wide 16-column grid. It’s a flavor of the popular 960 grid system, but without the additional margin/padding on the left and right sides.</p>
    </div>
    <div class="span12">
      <h3>Example grid markup</h3>
      <p>As shown here, a basic layout can be created with two "columns," each spanning a number of the 16 foundational columns we defined as part of our grid system. See the examples below for more variations.</p>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span6"</span><span class="tag">&gt;</span></li><li class="L2"><span class="pln">    ...</span></li><li class="L3"><span class="pln">  </span><span class="tag">&lt;/div&gt;</span></li><li class="L4"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span10"</span><span class="tag">&gt;</span></li><li class="L5"><span class="pln">    ...</span></li><li class="L6"><span class="pln">  </span><span class="tag">&lt;/div&gt;</span></li><li class="L7"><span class="tag">&lt;/div&gt;</span></li></ol></pre>
    </div>
  </div><!-- /row -->
  <div title="16 column layout" class="row show-grid">
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
    <div class="span1">1</div>
  </div><!-- /row -->
  <div title="8 column layout" class="row show-grid">
    <div class="span2">2</div>
    <div class="span2">2</div>
    <div class="span2">2</div>
    <div class="span2">2</div>
    <div class="span2">2</div>
    <div class="span2">2</div>
    <div class="span2">2</div>
    <div class="span2">2</div>
  </div><!-- /row -->
  <div title="Example uncommon layout" class="row show-grid">
    <div class="span3">3</div>
    <div class="span3">3</div>
    <div class="span3">3</div>
    <div class="span3">3</div>
    <div class="span3">3</div>
    <div class="span1 column">1</div>
  </div><!-- /row -->
  <div title="Four column layout" class="row show-grid">
    <div class="span4">4</div>
    <div class="span4">4</div>
    <div class="span4">4</div>
    <div class="span4">4</div>
  </div><!-- /row -->
  <div title="Default three column layout" class="row show-grid">
    <div class="span-one-third">1/3</div>
    <div class="span-one-third">1/3</div>
    <div class="span-one-third">1/3</div>
  </div><!-- /row -->
  <div title="One-third and two-thirds layout" class="row show-grid">
    <div class="span-one-third">1/3</div>
    <div class="span-two-thirds">2/3</div>
  </div><!-- /row -->
  <div title="Irregular three column layout" class="row show-grid">
    <div class="span4">4</div>
    <div class="span6">6</div>
    <div class="span6">6</div>
  </div><!-- /row -->
  <div title="Half and half" class="row show-grid">
    <div class="span8">8</div>
    <div class="span8">8</div>
  </div><!-- /row -->
  <div title="Example uncommon two-column layout" class="row show-grid">
    <div class="span5">5</div>
    <div class="span11">11</div>
  </div><!-- /row -->
  <div title="Unnecessary single column layout" class="row show-grid">
    <div class="span16">16</div>
  </div><!-- /row -->

  <br>

  <h2>Offsetting columns</h2>
  <div class="row show-grid">
    <div class="span4">4</div>
    <div class="span8 offset4">8 offset 4</div>
  </div><!-- /row -->
  <div class="row show-grid">
    <div class="span-one-third offset-two-thirds">1/3 offset 2/3s</div>
  </div><!-- /row -->
  <div class="row show-grid">
    <div class="span4 offset4">4 offset 4</div>
    <div class="span4 offset4">4 offset 4</div>
  </div><!-- /row -->
  <div class="row show-grid">
    <div class="span5 offset3">5 offset 3</div>
    <div class="span5 offset3">5 offset 3</div>
  </div><!-- /row -->
  <div class="row show-grid">
    <div class="span10 offset6">10 offset 6</div>
  </div><!-- /row -->

  <br>

  <div class="row">
    <div class="span4">
      <h2>Nesting columns</h2>
      <p>Nest your content if you must by creating a <code>.row</code> within an existing column.</p>
    </div>
    <div class="span12">
      <h4>Example of nested columns</h4>
      <div class="row show-grid">
        <div class="span12">
          Level 1 of column
          <div class="row show-grid">
            <div class="span6">
              Level 2
            </div>
            <div class="span6">
              Level 2
            </div>
          </div>
        </div>
      </div>
<pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row"</span><span class="tag">&gt;</span></li><li class="L1"><span class="pln">  </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span12"</span><span class="tag">&gt;</span></li><li class="L2"><span class="pln">    Level 1 of column</span></li><li class="L3"><span class="pln">    </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"row"</span><span class="tag">&gt;</span></li><li class="L4"><span class="pln">      </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span6"</span><span class="tag">&gt;</span></li><li class="L5"><span class="pln">        Level 2</span></li><li class="L6"><span class="pln">      </span><span class="tag">&lt;/div&gt;</span></li><li class="L7"><span class="pln">      </span><span class="tag">&lt;div</span><span class="pln"> </span><span class="atn">class</span><span class="pun">=</span><span class="atv">"span6"</span><span class="tag">&gt;</span></li><li class="L8"><span class="pln">        Level 2</span></li><li class="L9"><span class="pln">      </span><span class="tag">&lt;/div&gt;</span></li><li class="L0"><span class="pln">    </span><span class="tag">&lt;/div&gt;</span></li><li class="L1"><span class="pln">  </span><span class="tag">&lt;/div&gt;</span></li><li class="L2"><span class="tag">&lt;/div&gt;</span></li></ol></pre>
    </div>
  </div>

  <br>

  <div class="row">
    <div class="span4">
      <h2>Roll your own grid</h2>
      <p>Built into Bootstrap are a handful of variables for customizing the default 940px grid system. With a bit of customization, you can modify the size of columns, their gutters, and the container they reside in.</p>
    </div>
    <div class="span12">
      <h3>Inside the grid</h3>
      <p>The variables needed to modify the grid system currently all reside in <code>variables.less</code>.</p>
      <table class="bordered-table zebra-striped">
        <thead>
          <tr>
            <th>Variable</th>
            <th>Default value</th>
            <th>Description</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td><code>@gridColumns</code></td>
            <td>16</td>
            <td>The number of columns within the grid</td>
          </tr>
          <tr>
            <td><code>@gridColumnWidth</code></td>
            <td>40px</td>
            <td>The width of each column within the grid</td>
          </tr>
          <tr>
            <td><code>@gridGutterWidth</code></td>
            <td>20px</td>
            <td>The negative space between each column</td>
          </tr>
          <tr>
            <td><code>@siteWidth</code></td>
            <td><em>Computed sum of all columns and gutters</em></td>
            <td>We use some basic match to count the number of columns and gutters and set the width of the <code>.fixed-container()</code> mixin.</td>
          </tr>
        </tbody>
      </table>
      <h3>Now to customize</h3>
      <p>Modifying the grid means changing the three <code>@grid-*</code> variables and recompiling the Less files.</p>
      <p>Bootstrap comes equipped to handle a grid system with up to 24 columns; the default is just 16. Here's how your grid variables would look customized to a 24-column grid.</p>
      <pre class="prettyprint linenums"><ol class="linenums"><li class="L0"><span class="lit">@gridColumns</span><span class="pun">:</span><span class="pln">       </span><span class="lit">24</span><span class="pun">;</span></li><li class="L1"><span class="lit">@gridColumnWidth</span><span class="pun">:</span><span class="pln">   </span><span class="lit">20px</span><span class="pun">;</span></li><li class="L2"><span class="lit">@gridGutterWidth</span><span class="pun">:</span><span class="pln">   </span><span class="lit">20px</span><span class="pun">;</span></li></ol></pre>
      <p>Once recompiled, you'll be set!</p>
    </div>
  </div>
</section> 
This is test module: <? print rand() ?>
 