<script>
    function CommentForm() {
        return {
            commentFormData: {
                comment_name: "",
                comment_email: "",
                comment_body: "",
                rel_id: "",
            },

            formMessage: "",
            formLoading: false,
            buttonText: "Submit",

            submitCommentsForm() {
                this.formMessage = "";
                this.formLoading = false;
                this.buttonText = "Submitting...";
                fetch(route('api.comment.post_comment'), {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                    },
                    body: JSON.stringify(this.commentFormData),
                })
                    .then(() => {
                        this.formData.comment_name = "";
                        this.formData.comment_email = "";
                        this.formData.comment_body = "";
                        this.formMessage = "Comment successfully submitted.";
                    })
                    .catch(() => {
                        this.formMessage = "Something went wrong.";
                    })
                    .finally(() => {
                        this.formLoading = false;
                        this.buttonText = "Submit";
                    });
            },
        };
    }
</script>

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
        <button type="submit" class="btn btn-outline-primary" :disabled="formLoading">
            <span x-show="!formLoading">Post comment</span>
            <span x-show="formLoading">Posting...</span>
        </button>
    </div>
</form>
