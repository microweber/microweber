

<form x-data="CommentForm()" @submit.prevent="submitCommentsForm">
    <div class="row">
        <div class="col">
            <label>Name:</label>
            <input type="text" class="form-control" required x-model="commentFormData.comment_name" />
        </div>
        <div class="col">
            <label>Email:</label>
            <input type="email" class="form-control" required x-model="commentFormData.comment_email" />
        </div>
    </div>
    <div class="mt-2">
        <label>Comment:</label>
        <textarea class="form-control" required x-model="commentFormData.comment_body"></textarea>
    </div>
    <div class="mt-2">
        <input type="hidden" x-model="commentFormData.rel_id" value="{{ content_id() }}" />

        @if(isset($reply_to_comment_id))
        <input type="hidden" x-model="commentFormData.reply_to_comment_id" value="{{ $reply_to_comment_id }}" />
        @endif


        <button type="submit" class="btn btn-outline-primary" :disabled="formLoading">
            <span x-show="!formLoading">Post comment</span>
            <span x-show="formLoading">Posting...</span>
        </button>
    </div>
</form>
