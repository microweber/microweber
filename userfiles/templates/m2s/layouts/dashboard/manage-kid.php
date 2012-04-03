<script>
$(document).ready(function(){
      $("#manage_kids_form").validate();
});
</script>

<div id="wall">
  <h2>Manage your kids</h2>
  <br />

  <mw module="users/sub_users_list"
  parent_id="<? print user_id(); ?>"
  no_subusers_text="You haven't added any kids yet."
  no_subusers_link_text="Click here to add your kid"
  no_subusers_link="<? print site_url('dashboard/action:add-kid') ?>"  />
  
  
  
</div>
