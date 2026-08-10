<?php

namespace MicroweberPackages\User\Validators;

use Illuminate\Validation\Validator;
use MicroweberPackages\DisposableEmailChecker\Contracts\DisposableEmailCheckerContract;
use MicroweberPackages\DisposableEmailChecker\Facades\DisposableEmailChecker;

/**
 * Legacy CMS validator — delegates to the new disposable-email-checker package.
 */
class TemporaryEmailCheckValidator
{
    public function validate(string $attribute, mixed $value, array $parameters, Validator $validator): bool
    {
        $inputs = $validator->getData();

        $email = $inputs['email'] ?? $value;

        if (!is_string($email)) {
            return true;
        }

        /** @var DisposableEmailCheckerContract $checker */
        $checker = DisposableEmailChecker::getFacadeRoot();

        return !$checker->isDisposable($email);
    }
}