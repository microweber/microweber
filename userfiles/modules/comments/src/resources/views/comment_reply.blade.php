<form>
    <div class="row">
        <div class="col">
            <label>Name:</label>
            <input type="text" class="form-control" required />
        </div>
        <div class="col">
            <label>Email:</label>
            <input type="email" class="form-control" required />
        </div>
    </div>
    <div class="mt-2">
        <label>Comment:</label>
        <textarea class="form-control" required></textarea>
    </div>
    <div class="mt-2">
        <input type="hidden" value="{{ content_id() }}" />

        @if(isset($reply_to_comment_id))
        <input type="hidden" value="{{ $reply_to_comment_id }}" />
        @endif

        <button type="submit" class="btn btn-outline-primary">
            Post comment
        </button>
    </div>
</form>
