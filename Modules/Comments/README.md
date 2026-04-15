# Comments

User commenting system for pages and posts. Supports nested replies, moderation, spam filtering, multiple editor types, and configurable display settings.

## Key Features

- Threaded/nested comment replies
- Guest and authenticated commenting
- Comment moderation queue
- Spam filtering with keyword blocking and link limits
- Configurable sort order and pagination
- Multiple editor backends (Textarea, EasyMDE markdown)
- Captcha integration
- Email notifications for admins and users
- Avatar generation via UI Avatars
- Policy-based authorization

## Configuration

Defined in `config/config.php`:

| Option | Default | Description |
|---|---|---|
| `enable_comments` | `false` | Enable commenting |
| `allow_replies` | `false` | Allow nested replies |
| `allow_guest_comments` | `false` | Allow unauthenticated comments |
| `enable_moderation` | `false` | Require approval before display |
| `enable_spam_filter` | `false` | Activate spam detection |
| `spam_keywords` | `''` | Comma-separated blocked words |
| `max_links` | `2` | Max links per comment before flagging |
| `sort_order` | `newest` | Display order |
| `comments_per_page` | `10` | Pagination size |
| `min/max_comment_length` | `2/1000` | Length constraints |
| `enable_captcha` | `false` | Require captcha verification |
| `notify_admin` / `notify_users` | `false` | Email notifications |

## Key Classes

| Class | Purpose |
|---|---|
| `Services\CommentsManager` | Core comment operations (`app('comments_manager')`) |
| `Services\AvatarProvider` | User avatar generation |
| `Models\Comment` | Comment model |
| `Models\GatedComment` | Authorization-gated comment model |
| `Policies\CommentPolicy` | Authorization rules |

## Database Tables

- `comments` -- all comments with threading support

## Admin Panel (Filament)

- **CommentResource** -- manage, moderate, and delete comments
- **CommentsModuleSettings** / **CommentsModuleSettingsAdmin** -- configuration pages

## Livewire Components

- `comments::user-comment-list` -- comment thread display
- `comments::user-comment-reply` -- reply form
- `comments::modals.reply-modal` / `edit-modal` / `delete-modal` -- action modals

## Usage

```html
<module type="comments" />
```

```php
$manager = app('comments_manager');
```
