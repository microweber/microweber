<script>
    function CommentForm() {
        return {
            commentFormData: {
                comment_name: "",
                comment_email: "",
                comment_body: "",
                rel_id: "",
                reply_to_comment_id: "",
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
                @include('comments::comment_reply')
            </div>
        </div>
    </div>

    <div class="container py-2 text-dark">
        <div class="row d-flex justify-content-center">
            <div class="col-md-11 col-lg-9 col-xl-7">

                @foreach($comments as $comment)

                    @include('comments::comment_preview', [
                               'comment' => $comment,
                           ])


                @endforeach

            </div>
        </div>
    </div>
</section>
