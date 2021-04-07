<div class="col-md-6">
    <select class="form-control" name="orderBy">
        <option value="id,desc" @if ($orderBy=='id,desc') selected="selected" @endif>Sort: Newest</option>
        <option value="id,asc" @if ($orderBy=='id,asc') selected="selected" @endif>Sort: Oldest</option>
    </select>
</div>
