# Laravel Action Classes Reference

## Never Create Records Directly in Controllers

**CRITICAL RULE**: Controllers should NEVER directly create, update, get or delete database records. All data persistence logic must be delegated to Action classes that are dependency injected into controller methods.

### Why Action Classes?

- **Separation of Concerns**: Controllers orchestrate HTTP concerns; Actions handle business operations
- **Reusability**: Actions can be reused across controllers, commands, jobs, and tests
- **Testability**: Easy to unit test business logic without HTTP layer
- **Single Responsibility**: Each Action does one thing well
- **Type Safety**: Clear contracts via dependency injection
- **Maintainability**: Changes to business logic are isolated to Action classes

## Correct Implementation

### ✅ CORRECT: Use Action Classes

```php
// app/Actions/CreateUserAction.php
namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final readonly class CreateUserAction
{
    public function execute(array $data): User
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }
}

// app/Http/Controllers/Auth/RegisteredUserController.php
namespace App\Http\Controllers\Auth;

use App\Actions\CreateUserAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class RegisteredUserController extends Controller
{
    public function __construct(
        private readonly CreateUserAction $createUser,
    ) {}

    public function store(RegisterRequest $request): RedirectResponse
    {
        $user = $this->createUser->execute($request->validated());

        event(new Registered($user));

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
```

## Action Class Patterns

### Simple Create Action

```php
namespace App\Actions;

use App\Models\Post;

final readonly class CreatePostAction
{
    public function execute(array $data): Post
    {
        return Post::create([
            'title' => $data['title'],
            'content' => $data['content'],
            'user_id' => $data['user_id'],
            'published_at' => $data['published_at'] ?? null,
        ]);
    }
}
```

### Update Action with Dependencies

```php
namespace App\Actions;

use App\Models\User;
use App\Services\AvatarService;

final readonly class UpdateUserProfileAction
{
    public function __construct(
        private AvatarService $avatarService,
    ) {}

    public function execute(User $user, array $data): User
    {
        if (isset($data['avatar'])) {
            $data['avatar_url'] = $this->avatarService->upload($data['avatar']);
            unset($data['avatar']);
        }

        $user->update($data);

        return $user->fresh();
    }
}
```

### Complex Action with Multiple Operations

```php
namespace App\Actions;

use App\Models\Order;
use App\Models\User;
use App\Services\PaymentService;
use App\Services\NotificationService;
use Illuminate\Support\Facades\DB;

final readonly class ProcessOrderAction
{
    public function __construct(
        private PaymentService $paymentService,
        private NotificationService $notificationService,
    ) {}

    public function execute(User $user, array $orderData, string $paymentToken): Order
    {
        return DB::transaction(function () use ($user, $orderData, $paymentToken) {
            $order = Order::create([
                'user_id' => $user->id,
                'total' => $orderData['total'],
                'status' => 'pending',
            ]);

            $order->items()->createMany($orderData['items']);

            $payment = $this->paymentService->charge(
                amount: $order->total,
                token: $paymentToken,
            );

            $order->update([
                'payment_id' => $payment->id,
                'status' => 'paid',
            ]);

            $this->notificationService->sendOrderConfirmation($order);

            return $order->load('items');
        });
    }
}
```

### Delete Action with Cleanup

```php
namespace App\Actions;

use App\Models\User;
use Illuminate\Support\Facades\Storage;

final readonly class DeleteUserAccountAction
{
    public function execute(User $user): void
    {
        // Clean up user's files
        if ($user->avatar_path) {
            Storage::delete($user->avatar_path);
        }

        // Delete related records
        $user->posts()->delete();
        $user->comments()->delete();

        // Delete the user
        $user->delete();
    }
}
```

## Action Organization

### File Structure

```
app/
├── Actions/
│   ├── User/
│   │   ├── CreateUserAction.php
│   │   ├── UpdateUserAction.php
│   │   ├── DeleteUserAction.php
│   │   └── SuspendUserAction.php
│   ├── Post/
│   │   ├── CreatePostAction.php
│   │   ├── UpdatePostAction.php
│   │   ├── PublishPostAction.php
│   │   └── UnpublishPostAction.php
│   └── Order/
│       ├── ProcessOrderAction.php
│       ├── CancelOrderAction.php
│       └── RefundOrderAction.php
```

### Naming Conventions

- **Action Classes**: Verb + Noun + `Action` (e.g., `CreateUserAction`, `ProcessPaymentAction`)
- **Method Name**: Always use `execute()` for consistency
- **File Location**: `app/Actions/[Domain]/[ActionName]Action.php`
- **Namespace**: `App\Actions\[Domain]`

## Summary

- **ALWAYS** use Action classes for database operations
- **NEVER** create/update/delete records directly in controllers
- Use dependency injection to provide Actions to controllers
- Keep Actions focused on a single responsibility
- Use `execute()` as the method name for consistency
- Organize Actions by domain in subdirectories
- Test Actions independently from HTTP layer
- Actions handle persistence; Services handle external I/O
