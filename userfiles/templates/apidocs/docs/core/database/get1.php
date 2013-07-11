<? include TEMPLATE_DIR. "header.php"; ?>
<div class="mw_function_holder">
  <div class="description">
    <h2  class="fn">get(<strong>$params</strong>)</h2>
    <p>Function to get database items by <strong>params</strong></p>
    <table class="table table-striped">
      <thead>
        <tr>
          <th>Parameter</th>
          <th>Values</th>
          <th>Description</th>
          <th>Example</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>from <small class="text-warning">(required)</small></td>
          <td>string</td>
          <td>The table name in the database</td>
          <td><code>get("from=content")</code>

          </td>
        </tr>

        <tr>
          <td></td>
          <td></td>
          <td></td>
          <td></td>
        </tr>


        <tr>
          <td>count</td>
          <td>true | false</td>
          <td>get the results count</td>
          <td><code>get("from=content&count=true")</code></td>
        </tr>


           <tr>
          <td>page_count</td>
          <td>true | false</td>
          <td>get the results pages count</td>
          <td><code>get("from=content&page_count=true")</code></td>
        </tr>  <tr>
          <td>curent_page</td>
          <td>int</td>
          <td>the curent results page</td>
          <td><code>get("from=content&curent_page=2")</code></td>
        </tr><tr>
          <td>paging_param</td>
          <td>curent_page</td>
          <td>the url parameter used to limit the results</td>
          <td><code>get("from=content&paging_param=pagenum")</code></td>
        </tr>
      </tbody>
    </table>
  </div>
</div>
<? include TEMPLATE_DIR. "footer.php"; ?>