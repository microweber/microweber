<?php

declare(strict_types=1);

namespace MicroweberPackages\DisposableEmailChecker\Validators;

use Illuminate\Validation\Validator;
use MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract;
use MicroweberPackages\DisposableEmailChecker\Facades\DisposableEmailChecker;

class NotDisposableEmailValidator
{
    /**
     * Validate that the given email does not belong to a disposable email provider.
     *
     * @param  string  $attribute
     * @param  mixed   $value
     * @param  array<int, string>  $parameters
     * @param  Validator  $validator
     * @return bool
     */
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool
    {
        if (!is_string($value)) {
            return true;
        }

        $enabled = config('disposable-email-checker.enabled', true);

        if (!$enabled) {
            return true;
        }

        /** @var DisposableEmailCheckerContract $checker */
        $checker = DisposableEmailChecker::getFacadeRoot();

        return !$checker->isDisposable($value);
    }
}