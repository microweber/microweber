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


<section style="background-color: #eee;">


    <div class="container my-5 py-5 text-dark">
        <div class="row d-flex justify-content-center">
            <div class="col-md-11 col-lg-9 col-xl-7">
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
            </div>
        </div>
    </div>

    <div class="container py-2 text-dark">
        <div class="row d-flex justify-content-center">
            <div class="col-md-11 col-lg-9 col-xl-7">

                @foreach($comments as $comment)
                <div class="d-flex flex-start mb-4">
                    <div class="shadow-1-strong me-3">
                        <i class="fa fa-user-circle-o" style="font-size:42px"></i>
                    </div>
                    <div class="card w-100">
                        <div class="card-body p-4">
                            <div class="">
                                <h5>{{$comment->comment_name}}</h5>
                                <p class="text-small">3 hours ago</p>
                                <p class="mt-3 mb-3">
                                    {{$comment->comment_body}}
                                </p>

                                <div class="d-flex justify-content-between align-items-center mt-4">
                                    <div class="d-flex align-items-center">
                                        <a href="#!" class="link-muted me-2"><i class="fas fa-thumbs-up me-1"></i>0</a>
                                        <a href="#!" class="link-muted"><i class="fas fa-thumbs-down me-1"></i>0</a>
                                    </div>
                                    <a href="#!" class="link-muted"><i class="fa fa-reply me-1"></i> Reply</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach

            </div>
        </div>
    </div>
</section>
