<?php

namespace MicroweberPackages\Modules\Comments\Http\LiveWire;

use Livewire\Component;
use MicroweberPackages\Modules\Comments\Models\Comment;

class UserCommentListComponent extends Component {

        public $relId;
        public $state = [];

        public function mount($relId = false)
        {
            $this->relId = $relId;
        }

        public function render()
        {
            $getComments = Comment::where('reply_to_comment_id', null)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('comments::livewire.user-comment-list-component', [
                'comments' => $getComments,
            ]);
        }

}
