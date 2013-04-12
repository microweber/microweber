
<span class="mw_dlm"></span>
<div style="width: 112px;" data-value="" title="These values will be replaced with the actual content" id="dynamic_vals" class="mw_dropdown mw_dropdown_type_wysiwyg mw_dropdown_type_wysiwyg_blue mw_dropdown_action_dynamic_values">
    <span class="mw_dropdown_val_holder">
        <span class="dd_rte_arr"></span>
        <span class="mw_dropdown_val" style="width: 80px;">E-mail Values</span>
    </span>
  <div class="mw_dropdown_fields">
      <ul>
        <li value="{id}"><a href="javascript:;">ID</a></li>
        <li value="{amount}"><a href="javascript:;">Amount</a></li>
        <li value="{order_status}"><a href="javascript:;">Order Status</a></li>
        <li value="{email}"><a href="javascript:;">Email</a></li>
        <li value="{currency_code}"><a href="javascript:;">Currency Code</a></li>
        <li value="{first_name}"><a href="javascript:;">First Name</a></li>
        <li value="{last_name}"><a href="javascript:;">Last Name</a></li>
        <li value="{email}"><a href="javascript:;">Email</a></li>
        <li value="{city}"><a href="javascript:;">City</a></li>
        <li value="{state}"><a href="javascript:;">State</a></li>
        <li value="{zip}"><a href="javascript:;">ZIP/Post Code</a></li>
        <li value="{address}"><a href="javascript:;">Address</a></li>
        <li value="{phone}"><a href="javascript:;">Phone</a></li>
      </ul>
    </div>
</div>
<script>
    mw.$("#dynamic_vals").change(function(){
        var val = $(this).getDropdownValue();
         mw.wysiwyg.insert_html(val);
    });
</script>