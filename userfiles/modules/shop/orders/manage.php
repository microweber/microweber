<div class="mw-table-sorting-controller">
  <input style="width: 365px;" type="text" id="mw-search-field" class="manage-search" value="Search for posts">
  <div class="mw-table-sorting right">
    <label>Sort By:</label>
    <ul class="unselectable">
      <li><span data-sort-type="created_on" onclick="mw.tools.sort({id:'shop-orders',el:this});">Date</span></li>
      <li><span data-sort-type="first_name" onclick="mw.tools.sort({id:'shop-orders',el:this});">Name(A-Z)</span></li>
      <li><span data-sort-type="amount" onclick="mw.tools.sort({id:'shop-orders',el:this});">Ammout</span></li>
    </ul>
  </div>
</div>
<module type="shop/orders"  id="mw-admin-manage-orders-list"  />
