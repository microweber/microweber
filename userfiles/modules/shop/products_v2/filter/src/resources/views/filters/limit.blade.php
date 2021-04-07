<div class="col-md-6">
    <select class="form-control" name="limit">
        <option value="1" @if ($limit==1) selected="selected" @endif>Limit: 1</option>
        <option value="2" @if ($limit==2) selected="selected" @endif>Limit: 2</option>
        <option value="3" @if ($limit==3) selected="selected" @endif>Limit: 3</option>
        <option value="5" @if ($limit==5) selected="selected" @endif>Limit: 5</option>
        <option value="10" @if ($limit==10) selected="selected" @endif>Limit: 10</option>
        <option value="15" @if ($limit==15) selected="selected" @endif>Limit: 15</option>
        <option value="20" @if ($limit==20) selected="selected" @endif>Limit: 20</option>
    </select>
</div>
