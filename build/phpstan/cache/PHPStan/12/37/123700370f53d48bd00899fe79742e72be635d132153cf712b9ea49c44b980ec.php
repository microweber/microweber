<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Person.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Stripe\Person
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-897cd30346e50c36aef30e032b815406e3f6fbc22e3987c189d64585fcfee007-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Stripe\\Person',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../stripe/stripe-php/lib/Person.php',
      ),
    ),
    'namespace' => 'Stripe',
    'name' => 'Stripe\\Person',
    'shortName' => 'Person',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * This is an object representing a person associated with a Stripe account.
 *
 * A platform can only access a subset of data in a person for an account where <a href="/api/accounts/object#account_object-controller-requirement_collection">account.controller.requirement_collection</a> is <code>stripe</code>, which includes Standard and Express accounts, after creating an Account Link or Account Session to start Connect onboarding.
 *
 * See the <a href="/connect/standard-accounts">Standard onboarding</a> or <a href="/connect/express-accounts">Express onboarding</a> documentation for information about prefilling information and account onboarding steps. Learn more about <a href="/connect/handling-api-verification#person-information">handling identity verification with the API</a>.
 *
 * @property string $id Unique identifier for the object.
 * @property string $object String representing the object\'s type. Objects of the same type share the same value.
 * @property null|string $account The account the person is associated with.
 * @property null|(object{account: null|(object{date: null|int, ip: null|string, user_agent: null|string}&StripeObject)}&StripeObject) $additional_tos_acceptances
 * @property null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject) $address
 * @property null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string, town: null|string}&StripeObject) $address_kana The Kana variation of the person\'s address (Japan only).
 * @property null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string, town: null|string}&StripeObject) $address_kanji The Kanji variation of the person\'s address (Japan only).
 * @property int $created Time at which the object was created. Measured in seconds since the Unix epoch.
 * @property null|(object{day: null|int, month: null|int, year: null|int}&StripeObject) $dob
 * @property null|string $email The person\'s email address. Also available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>stripe</code>.
 * @property null|string $first_name The person\'s first name. Also available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>stripe</code>.
 * @property null|string $first_name_kana The Kana variation of the person\'s first name (Japan only). Also available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>stripe</code>.
 * @property null|string $first_name_kanji The Kanji variation of the person\'s first name (Japan only). Also available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>stripe</code>.
 * @property null|string[] $full_name_aliases A list of alternate names or aliases that the person is known by. Also available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>stripe</code>.
 * @property null|(object{alternatives: null|(object{alternative_fields_due: string[], original_fields_due: string[]}&StripeObject)[], currently_due: string[], errors: (object{code: string, reason: string, requirement: string}&StripeObject)[], eventually_due: string[], past_due: string[], pending_verification: string[]}&StripeObject) $future_requirements Information about the <a href="https://stripe.com/docs/connect/custom-accounts/future-requirements">upcoming new requirements for this person</a>, including what information needs to be collected, and by when.
 * @property null|string $gender The person\'s gender.
 * @property null|bool $id_number_provided Whether the person\'s <code>id_number</code> was provided. True if either the full ID number was provided or if only the required part of the ID number was provided (ex. last four of an individual\'s SSN for the US indicated by <code>ssn_last_4_provided</code>).
 * @property null|bool $id_number_secondary_provided Whether the person\'s <code>id_number_secondary</code> was provided.
 * @property null|string $last_name The person\'s last name. Also available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>stripe</code>.
 * @property null|string $last_name_kana The Kana variation of the person\'s last name (Japan only). Also available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>stripe</code>.
 * @property null|string $last_name_kanji The Kanji variation of the person\'s last name (Japan only). Also available for accounts where <a href="/api/accounts/object#account_object-controller-requirement_collection">controller.requirement_collection</a> is <code>stripe</code>.
 * @property null|string $maiden_name The person\'s maiden name.
 * @property null|StripeObject $metadata Set of <a href="https://stripe.com/docs/api/metadata">key-value pairs</a> that you can attach to an object. This can be useful for storing additional information about the object in a structured format.
 * @property null|string $nationality The country where the person is a national.
 * @property null|string $phone The person\'s phone number.
 * @property null|string $political_exposure Indicates if the person or any of their representatives, family members, or other closely related persons, declares that they hold or have held an important public job or function, in any jurisdiction.
 * @property null|(object{city: null|string, country: null|string, line1: null|string, line2: null|string, postal_code: null|string, state: null|string}&StripeObject) $registered_address
 * @property null|(object{authorizer: null|bool, director: null|bool, executive: null|bool, legal_guardian: null|bool, owner: null|bool, percent_ownership: null|float, representative: null|bool, title: null|string}&StripeObject) $relationship
 * @property null|(object{alternatives: null|(object{alternative_fields_due: string[], original_fields_due: string[]}&StripeObject)[], currently_due: string[], errors: (object{code: string, reason: string, requirement: string}&StripeObject)[], eventually_due: string[], past_due: string[], pending_verification: string[]}&StripeObject) $requirements Information about the requirements for this person, including what information needs to be collected, and by when.
 * @property null|bool $ssn_last_4_provided Whether the last four digits of the person\'s Social Security number have been provided (U.S. only).
 * @property null|(object{ethnicity_details: null|(object{ethnicity: null|string[], ethnicity_other: null|string}&StripeObject), race_details: null|(object{race: null|string[], race_other: null|string}&StripeObject), self_identified_gender: null|string}&StripeObject) $us_cfpb_data Demographic data related to the person.
 * @property null|(object{additional_document?: null|(object{back: null|File|string, details: null|string, details_code: null|string, front: null|File|string}&StripeObject), details?: null|string, details_code?: null|string, document?: (object{back: null|File|string, details: null|string, details_code: null|string, front: null|File|string}&StripeObject), status: string}&StripeObject) $verification
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 47,
    'endLine' => 140,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => 'Stripe\\ApiResource',
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
      0 => 'Stripe\\ApiOperations\\Delete',
    ),
    'immediateConstants' => 
    array (
      'OBJECT_NAME' => 
      array (
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'name' => 'OBJECT_NAME',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'person\'',
          'attributes' => 
          array (
            'startLine' => 49,
            'endLine' => 49,
            'startTokenPos' => 27,
            'startFilePos' => 7845,
            'endTokenPos' => 27,
            'endFilePos' => 7852,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 49,
        'endLine' => 49,
        'startColumn' => 5,
        'endColumn' => 33,
      ),
      'GENDER_FEMALE' => 
      array (
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'name' => 'GENDER_FEMALE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'female\'',
          'attributes' => 
          array (
            'startLine' => 51,
            'endLine' => 51,
            'startTokenPos' => 36,
            'startFilePos' => 7882,
            'endTokenPos' => 36,
            'endFilePos' => 7889,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 51,
        'endLine' => 51,
        'startColumn' => 5,
        'endColumn' => 35,
      ),
      'GENDER_MALE' => 
      array (
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'name' => 'GENDER_MALE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'male\'',
          'attributes' => 
          array (
            'startLine' => 52,
            'endLine' => 52,
            'startTokenPos' => 45,
            'startFilePos' => 7916,
            'endTokenPos' => 45,
            'endFilePos' => 7921,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 52,
        'endLine' => 52,
        'startColumn' => 5,
        'endColumn' => 31,
      ),
      'POLITICAL_EXPOSURE_EXISTING' => 
      array (
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'name' => 'POLITICAL_EXPOSURE_EXISTING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'existing\'',
          'attributes' => 
          array (
            'startLine' => 54,
            'endLine' => 54,
            'startTokenPos' => 54,
            'startFilePos' => 7965,
            'endTokenPos' => 54,
            'endFilePos' => 7974,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 54,
        'endLine' => 54,
        'startColumn' => 5,
        'endColumn' => 51,
      ),
      'POLITICAL_EXPOSURE_NONE' => 
      array (
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'name' => 'POLITICAL_EXPOSURE_NONE',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'none\'',
          'attributes' => 
          array (
            'startLine' => 55,
            'endLine' => 55,
            'startTokenPos' => 63,
            'startFilePos' => 8013,
            'endTokenPos' => 63,
            'endFilePos' => 8018,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 55,
        'endLine' => 55,
        'startColumn' => 5,
        'endColumn' => 43,
      ),
      'VERIFICATION_STATUS_PENDING' => 
      array (
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'name' => 'VERIFICATION_STATUS_PENDING',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'pending\'',
          'attributes' => 
          array (
            'startLine' => 57,
            'endLine' => 57,
            'startTokenPos' => 72,
            'startFilePos' => 8062,
            'endTokenPos' => 72,
            'endFilePos' => 8070,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 57,
        'endLine' => 57,
        'startColumn' => 5,
        'endColumn' => 50,
      ),
      'VERIFICATION_STATUS_UNVERIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'name' => 'VERIFICATION_STATUS_UNVERIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'unverified\'',
          'attributes' => 
          array (
            'startLine' => 58,
            'endLine' => 58,
            'startTokenPos' => 81,
            'startFilePos' => 8116,
            'endTokenPos' => 81,
            'endFilePos' => 8127,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 58,
        'endLine' => 58,
        'startColumn' => 5,
        'endColumn' => 56,
      ),
      'VERIFICATION_STATUS_VERIFIED' => 
      array (
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'name' => 'VERIFICATION_STATUS_VERIFIED',
        'modifiers' => 1,
        'type' => NULL,
        'value' => 
        array (
          'code' => '\'verified\'',
          'attributes' => 
          array (
            'startLine' => 59,
            'endLine' => 59,
            'startTokenPos' => 90,
            'startFilePos' => 8171,
            'endTokenPos' => 90,
            'endFilePos' => 8180,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 59,
        'endLine' => 59,
        'startColumn' => 5,
        'endColumn' => 52,
      ),
    ),
    'immediateProperties' => 
    array (
    ),
    'immediateMethods' => 
    array (
      'instanceUrl' => 
      array (
        'name' => 'instanceUrl',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @return string the API URL for this Stripe account reversal
 */',
        'startLine' => 66,
        'endLine' => 85,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'currentClassName' => 'Stripe\\Person',
        'aliasName' => NULL,
      ),
      'retrieve' => 
      array (
        'name' => 'retrieve',
        'parameters' => 
        array (
          '_id' => 
          array (
            'name' => '_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 37,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          '_opts' => 
          array (
            'name' => '_opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 93,
                'endLine' => 93,
                'startTokenPos' => 256,
                'startFilePos' => 9101,
                'endTokenPos' => 256,
                'endFilePos' => 9104,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 93,
            'endLine' => 93,
            'startColumn' => 43,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param array|string $_id
 * @param null|array|string $_opts
 *
 * @throws Exception\\BadMethodCallException
 */',
        'startLine' => 93,
        'endLine' => 100,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'currentClassName' => 'Stripe\\Person',
        'aliasName' => NULL,
      ),
      'update' => 
      array (
        'name' => 'update',
        'parameters' => 
        array (
          '_id' => 
          array (
            'name' => '_id',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 35,
            'endColumn' => 38,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          '_params' => 
          array (
            'name' => '_params',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 305,
                'startFilePos' => 9590,
                'endTokenPos' => 305,
                'endFilePos' => 9593,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 41,
            'endColumn' => 55,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          '_options' => 
          array (
            'name' => '_options',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 109,
                'endLine' => 109,
                'startTokenPos' => 312,
                'startFilePos' => 9608,
                'endTokenPos' => 312,
                'endFilePos' => 9611,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 109,
            'endLine' => 109,
            'startColumn' => 58,
            'endColumn' => 73,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param string $_id
 * @param null|array $_params
 * @param null|array|string $_options
 *
 * @throws Exception\\BadMethodCallException
 */',
        'startLine' => 109,
        'endLine' => 116,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 17,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'currentClassName' => 'Stripe\\Person',
        'aliasName' => NULL,
      ),
      'save' => 
      array (
        'name' => 'save',
        'parameters' => 
        array (
          'opts' => 
          array (
            'name' => 'opts',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 129,
                'endLine' => 129,
                'startTokenPos' => 356,
                'startFilePos' => 10289,
                'endTokenPos' => 356,
                'endFilePos' => 10292,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 129,
            'endLine' => 129,
            'startColumn' => 26,
            'endColumn' => 37,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param null|array|string $opts
 *
 * @return static the saved resource
 *
 * @throws Exception\\ApiErrorException if the request fails
 *
 * @deprecated The `save` method is deprecated and will be removed in a
 *     future major version of the library. Use the static method `update`
 *     on the resource instead.
 */',
        'startLine' => 129,
        'endLine' => 139,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Stripe',
        'declaringClassName' => 'Stripe\\Person',
        'implementingClassName' => 'Stripe\\Person',
        'currentClassName' => 'Stripe\\Person',
        'aliasName' => NULL,
      ),
    ),
    'traitsData' => 
    array (
      'aliases' => 
      array (
      ),
      'modifiers' => 
      array (
      ),
      'precedences' => 
      array (
      ),
      'hashes' => 
      array (
      ),
    ),
  ),
));