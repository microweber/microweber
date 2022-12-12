<div class="pt-0">

    @include('content::admin.content.index-page-category-tree')

    <div class="module-content">

        <module type="categories/edit_category" category_id="{{$id}}"  @if(isset($isShop)) is_shop="1" @endif />

    </div>
</div>
