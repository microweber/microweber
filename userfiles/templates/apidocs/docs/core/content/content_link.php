<? include TEMPLATE_DIR. "header.php"; ?>
<div class="demo" id="demo-one">
  <div class="description">
    <h2 class="fn">content_link(<strong>$id</strong>)</h2>
    <p>get the url for the given content <strong>id</strong></p>
  </div>
  <!-- .description -->
  <nav class="subnav tabs">
    <ul>
      <li class="active"><a href="#panel-1">Example 1</a></li>
      <li><a href="#panel-2">JavaScript</a></li>
      <li><a href="#panel-3">HTML</a></li>
      <li><a href="#panel-4">CSS</a></li>
    </ul>
  </nav>
  <div class="panels">
    <div class="panel" id="panel-1">

      <pre>&lt;? print content_link(<strong>5</strong>) ?&gt;</pre>
    </div>
    <!-- .panel -->
    <div class="panel" id="panel-2">
      <pre>JavaScript code would go here.</pre>
    </div>
    <!-- .panel -->
    <div class="panel" id="panel-3">
      <pre>Mmmm, nothing like some solid HTML.</pre>
    </div>
    <!-- .panel -->
    <div class="panel" id="panel-4">
      <pre>CSS is nice too!</pre>
    </div>
    <!-- .panel -->
  </div>
  <!-- .panels -->
</div>
<!-- .demo -->
<div class="sep"><span class="left-arrow arrow"></span><span class="right-arrow arrow"></span></div>
<div class="options">
  <div class="description">
    <h3>Available Options  </h3>
  </div>
  <!-- .description -->
  <div class="table-wrap">
    <table width="100%" class="options-table" cellpadding="0" cellspacing="0">
      <thead>
        <tr>
          <th width="20%">Key</th>
          <th width="18%">Default value</th>
          <th>Description</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>id</td>
          <td>false</td>
          <td>The id of the content</td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- .table-wrap -->
</div>


<? include TEMPLATE_DIR. "footer.php"; ?>