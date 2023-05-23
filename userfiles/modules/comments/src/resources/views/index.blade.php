<div class="container">
    <div class="row">
        <div class="col-md-12">
            <form x-data="CommentForm()" @submit.prevent="submitCommentsForm">
                <div>
                    <label>Name:</label>
                    <input type="text" name="name" required x-model="commentFormData.name" />
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" name="email" required x-model="commentFormData.email" />
                </div>
                <div>
                    <label>Comment:</label>
                    <textarea name="comment" required x-model="commentFormData.message"></textarea>
                </div>
                <button type="submit" :disabled="formLoading">
                    <span x-show="!formLoading">Post comment</span>
                    <span x-show="formLoading">Posting...</span>
                </button>
            </form>
            <script>
                // ...
                function CommentForm() {
                    return {

                        commentFormData: {
                            name: "",
                            email: "",
                            message: "",
                        },

                        formMessage: "",
                        formLoading: false,
                        buttonText: "Submit",

                        submitCommentsForm() {
                            this.formMessage = "";
                            this.formLoading = false;
                            this.buttonText = "Submitting...";
                            fetch(mw.settings.api_url, {
                                method: "POST",
                                headers: {
                                    "Content-Type": "application/json",
                                    Accept: "application/json",
                                },
                                body: JSON.stringify(this.formData),
                            })
                                .then(() => {
                                    this.formData.name = "";
                                    this.formData.email = "";
                                    this.formData.message = "";
                                    this.formMessage = "Form successfully submitted.";
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
        </div>
    </div>
</div>
