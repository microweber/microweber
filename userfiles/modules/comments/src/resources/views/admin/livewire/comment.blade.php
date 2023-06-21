<?php

if(!isset($comment) or !$comment) {
    return;
}
?>
<div class="card shadow-sm mb-4 bg-silver comments-card" x-data="{showReplyForm: false}">

    @if($comment->isPending())
        <div class="card-status-start bg-primary"></div>
    @endif

    <div class="card-body">
        <div class="gap-5">

            <div class="d-flex align-items-center gap-2">

                <div>
                    @if($comment->created_by > 0)
                        <img class="rounded-circle shadow-1-strong me-3"
                             src="{{user_picture($comment->created_by, 165, 165)}}" alt="avatar" width="65"
                             height="65" />
                    @else
                        <div class="shadow-1-strong me-3">
                            <i class="fa fa-user-circle-o" style="font-size:42px"></i>
                        </div>
                    @endif
                </div>

                <div class="d-flex justify-content-between gap-5" style="width: 100%;">
                    <div class="">
                        <p class="mb-0">
                            {{$comment->comment_name}}
                        </p>
                        <span class="text-muted">
                            {{$comment->created_at->diffForHumans()}}
                            <span>·</span>
                         {{$comment->comment_email}}
                        </span>
                    </div>

                    @if($comment->isPending())
                        <div>
                        <span class="badge badge-primary bg-primary">
                         {{_e('Waiting for approval')}}
                        </span>
                        </div>
                    @endif
                </div>

            </div>

            <div class="mt-3" style="padding-left:80px">

                <a href="{{content_link($comment->contentId())}}" target="_blank">
                    <p class="mb-3" style="font-weight: bold;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M16 19H3v-2h13v2zm5-10H3v2h18V9zM3 5v2h11V5H3zm14 0v2h4V5h-4zm-6 8v2h10v-2H11zm-8 0v2h5v-2H3z"/></svg>
                        {{$comment->contentTitle()}}
                    </p>
                </a>

                @if($comment->reply_to_comment_id > 0)
                    <div class="mb-2">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item list-group-item-action active" aria-current="true">
                                {{_e('In reply to:')}}
                                <span class="text-muted">
                                   {{str_limit(strip_tags($comment->parentCommentBody()), 80)}}
                                </span>
                            </div>
                        </div>
                    </div>
                @endif

                <div @if($this->isEditing) @else style="display:none" @endif>
                    <x-comments::editors.easymde model="comment.comment_body" />
                    <button type="button" class="btn btn-primary mt-3" wire:click="save">
                        {{_e('Save')}}
                    </button>
                </div>

                <div @if($this->isEditing) style="display:none" @endif>
                    <div class="cursor-pointer" wire:click="preview">
                       <span class="mb-0 text-bold">
                          {!! $comment->commentBodyDisplay() !!}
                        </span>
                    </div>
                </div>

            </div>

            <div style="padding-left:80px">
                <div class="border-top pt-3 mt-3 d-flex gap-4">


                    @if($comment->deleted_at == null)
                        @if ($comment->is_moderated == 1)
                            <button class="mw-admin-action-links text-decoration-none btn btn-link" wire:click="markAsUnmoderated">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 40 40"><path fill="currentColor" d="M21.499 19.994L32.755 8.727a1.064 1.064 0 0 0-.001-1.502c-.398-.396-1.099-.398-1.501.002L20 18.494L8.743 7.224c-.4-.395-1.101-.393-1.499.002a1.05 1.05 0 0 0-.309.751c0 .284.11.55.309.747L18.5 19.993L7.245 31.263a1.064 1.064 0 0 0 .003 1.503c.193.191.466.301.748.301h.006c.283-.001.556-.112.745-.305L20 21.495l11.257 11.27c.199.198.465.308.747.308a1.058 1.058 0 0 0 1.061-1.061c0-.283-.11-.55-.31-.747L21.499 19.994z"/></svg>
                                {{ _e("Unapprove") }}
                            </button>
                        @else
                            <button class="mw-admin-action-links text-decoration-none btn btn-link text-success" wire:click="markAsModerated">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256"><path fill="currentColor" d="m232.49 80.49l-128 128a12 12 0 0 1-17 0l-56-56a12 12 0 1 1 17-17L96 183L215.51 63.51a12 12 0 0 1 17 17Z"/></svg>
                                {{ _e("Approve") }}
                            </button>
                        @endif

                        @if($comment->is_spam == 1)
                            <button class="mw-admin-action-links text-decoration-none btn btn-link" wire:click="markAsNotSpam">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M17.5 2.5L23 12l-5.5 9.5h-11L1 12l5.5-9.5h11Zm-1.153 2H7.653L3.311 12l4.342 7.5h8.694l4.342-7.5l-4.342-7.5ZM11 15h2v2h-2v-2Zm0-8h2v6h-2V7Z"/></svg>
                                &nbsp;{{ _e("Unspam") }}
                            </button>
                        @else
                            <button class="mw-admin-action-links text-decoration-none btn btn-link" wire:click="markAsSpam">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M17.5 2.5L23 12l-5.5 9.5h-11L1 12l5.5-9.5h11Zm-1.153 2H7.653L3.311 12l4.342 7.5h8.694l4.342-7.5l-4.342-7.5ZM11 15h2v2h-2v-2Zm0-8h2v6h-2V7Z"/></svg>
                                &nbsp;{{ _e("Spam") }}
                            </button>
                        @endif
                    @endif

                    @if($comment->deleted_at !== null)
                        <button class="mw-admin-action-links text-decoration-none btn btn-link" wire:click="markAsNotTrash">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 1024 1024"><path fill="currentColor" d="M360 184h-8c4.4 0 8-3.6 8-8v8h304v-8c0 4.4 3.6 8 8 8h-8v72h72v-80c0-35.3-28.7-64-64-64H352c-35.3 0-64 28.7-64 64v80h72v-72zm504 72H160c-17.7 0-32 14.3-32 32v32c0 4.4 3.6 8 8 8h60.4l24.7 523c1.6 34.1 29.8 61 63.9 61h454c34.2 0 62.3-26.8 63.9-61l24.7-523H888c4.4 0 8-3.6 8-8v-32c0-17.7-14.3-32-32-32zM731.3 840H292.7l-24.2-512h487l-24.2 512z"/></svg>
                            {{ _e("Untrash") }}
                        </button>

                        @php
                            $deleteModalData = [
                                'body' => 'Are you sure you want to delete this comment?',
                                'title' => 'Delete this comment',
                                'button_text'=> 'Delete forever',
                                'action' => 'executeCommentDelete',
                                'data'=> $comment->id
                            ];
                        @endphp
                        <button class="mw-admin-action-links text-decoration-none btn btn-link"
                                onclick="Livewire.emit('openModal', 'admin-confirm-modal', {{ json_encode($deleteModalData) }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32"><path fill="currentColor" d="M12 12h2v12h-2zm6 0h2v12h-2z"/><path fill="currentColor" d="M4 6v2h2v20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h2V6zm4 22V8h16v20zm4-26h8v2h-8z"/></svg>
                            {{ _e("Delete forever") }}
                        </button>
                    @else


                        @php
                            $trashModalData = [
                                'body' => 'Are you sure you want to trash this comment?',
                                'title' => 'Trash this comment',
                                'button_text'=> 'Move to trash',
                                'action' => 'executeCommentMarkAsTrash',
                                'data'=> $comment->id
                            ];
                        @endphp
                        <button class="mw-admin-action-links text-decoration-none btn btn-link"
                                onclick="Livewire.emit('openModal', 'admin-confirm-modal', {{ json_encode($trashModalData) }})">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 32 32"><path fill="currentColor" d="M12 12h2v12h-2zm6 0h2v12h-2z"/><path fill="currentColor" d="M4 6v2h2v20a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8h2V6zm4 22V8h16v20zm4-26h8v2h-8z"/></svg>
                            {{ _e("Trash") }}
                        </button>
                    @endif

                    @if($comment->deleted_at == null)
                        <button class="mw-admin-action-links text-decoration-none btn btn-link" wire:click="startEditing">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 256 256"><path fill="currentColor" d="M224.49 76.2L179.8 31.51a12 12 0 0 0-17 0L39.52 154.83a11.9 11.9 0 0 0-3.52 8.48V208a12 12 0 0 0 12 12h44.69a12 12 0 0 0 8.48-3.51L224.48 93.17a12 12 0 0 0 0-17ZM45.66 160L136 69.65L158.34 92L68 182.34ZM44 208v-38.34l21.17 21.17L86.34 212H48a4 4 0 0 1-4-4Zm52 2.34L73.66 188L164 97.65L186.34 120ZM218.83 87.51L192 114.34L141.66 64l26.82-26.83a4 4 0 0 1 5.66 0l44.69 44.68a4 4 0 0 1 0 5.66Z"/></svg>
                            &nbsp;{{ _e("Edit") }}
                        </button>

                        <button @click="showReplyForm = ! showReplyForm" style="cursor:pointer" class="mw-admin-action-links text-decoration-none btn btn-link">
                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"><path fill="currentColor" d="M9 16h7.2l-2.6 2.6L15 20l5-5l-5-5l-1.4 1.4l2.6 2.6H9c-2.2 0-4-1.8-4-4s1.8-4 4-4h2V4H9c-3.3 0-6 2.7-6 6s2.7 6 6 6z"/></svg>
                            {{ _e("Reply") }}
                        </button>

                    @endif

                </div>

                @if($comment->deleted_at == null)
                    <div x-show="showReplyForm" style="display:none; background:#fff;" >
                        <div class="mt-2 mb-4">
                            <div>
                                <livewire:comments::admin-comment-reply wire:key="admin-comment-reply-id-{{$comment->id}}" rel_id="{{$comment->rel_id}}" reply_to_comment_id="{{$comment->id}}" />
                            </div>
                        </div>
                    </div>
                @endif

            </div>

        </div>
    </div>
</div>

