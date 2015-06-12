<?php $fonts= json_decode(file_get_contents(__DIR__.DS.'fonts.json'), true); ?>
<?php dd($fonts); ?>
<div class="module-live-edit-settings">
  <h3>Select available fonts</h3>
  <table width="100%" cellspacing="0" cellpadding="0" class="mw-ui-table">
    <thead>
      <tr>
        <th></th>
        <th>Font name</th>
        <th>View</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>Lorem</td>
        <td>Ipsum</td>
        <td><a class="show-on-hover mw-ui-btn mw-ui-btn-invert mw-ui-btn-medium" href="javascript:;">View on hover</a></td>
      </tr>
    </tbody>
  </table>
</div>
