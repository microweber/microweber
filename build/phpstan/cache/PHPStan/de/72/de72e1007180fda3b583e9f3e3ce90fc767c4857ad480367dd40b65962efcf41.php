<?php declare(strict_types = 1);

// osfsl-/home/headless/Documents/GitHub/microweber/vendor/composer/../fakerphp/faker/src/Faker/Generator.php-PHPStan\BetterReflection\Reflection\ReflectionClass-Faker\Generator
return \PHPStan\Cache\CacheItem::__set_state(array(
   'variableKey' => 'v2-7f59225a08e57dd6da5871038a39f1a71df66fd3d11b35b4c9792685f46db53a-8.4.18-6.65.0.9',
   'data' => 
  array (
    'locatedSource' => 
    array (
      'class' => 'PHPStan\\BetterReflection\\SourceLocator\\Located\\LocatedSource',
      'data' => 
      array (
        'name' => 'Faker\\Generator',
        'filename' => '/home/headless/Documents/GitHub/microweber/vendor/composer/../fakerphp/faker/src/Faker/Generator.php',
      ),
    ),
    'namespace' => 'Faker',
    'name' => 'Faker\\Generator',
    'shortName' => 'Generator',
    'isInterface' => false,
    'isTrait' => false,
    'isEnum' => false,
    'isBackedEnum' => false,
    'modifiers' => 0,
    'docComment' => '/**
 * @property string $citySuffix
 *
 * @method string citySuffix()
 *
 * @property string $streetSuffix
 *
 * @method string streetSuffix()
 *
 * @property string $buildingNumber
 *
 * @method string buildingNumber()
 *
 * @property string $city
 *
 * @method string city()
 *
 * @property string $streetName
 *
 * @method string streetName()
 *
 * @property string $streetAddress
 *
 * @method string streetAddress()
 *
 * @property string $postcode
 *
 * @method string postcode()
 *
 * @property string $address
 *
 * @method string address()
 *
 * @property string $country
 *
 * @method string country()
 *
 * @property float $latitude
 *
 * @method float latitude($min = -90, $max = 90)
 *
 * @property float $longitude
 *
 * @method float longitude($min = -180, $max = 180)
 *
 * @property float[] $localCoordinates
 *
 * @method float[] localCoordinates()
 *
 * @property int $randomDigitNotNull
 *
 * @method int randomDigitNotNull()
 *
 * @property mixed $passthrough
 *
 * @method mixed passthrough($value)
 *
 * @property string $randomLetter
 *
 * @method string randomLetter()
 *
 * @property string $randomAscii
 *
 * @method string randomAscii()
 *
 * @property array $randomElements
 *
 * @method array randomElements($array = [\'a\', \'b\', \'c\'], $count = 1, $allowDuplicates = false)
 *
 * @property mixed $randomElement
 *
 * @method mixed randomElement($array = [\'a\', \'b\', \'c\'])
 *
 * @property int|string|null $randomKey
 *
 * @method int|string|null randomKey($array = [])
 *
 * @property array|string $shuffle
 *
 * @method array|string shuffle($arg = \'\')
 *
 * @property array $shuffleArray
 *
 * @method array shuffleArray($array = [])
 *
 * @property string $shuffleString
 *
 * @method string shuffleString($string = \'\', $encoding = \'UTF-8\')
 *
 * @property string $numerify
 *
 * @method string numerify($string = \'###\')
 *
 * @property string $lexify
 *
 * @method string lexify($string = \'????\')
 *
 * @property string $bothify
 *
 * @method string bothify($string = \'## ??\')
 *
 * @property string $asciify
 *
 * @method string asciify($string = \'****\')
 *
 * @property string $regexify
 *
 * @method string regexify($regex = \'\')
 *
 * @property string $toLower
 *
 * @method string toLower($string = \'\')
 *
 * @property string $toUpper
 *
 * @method string toUpper($string = \'\')
 *
 * @property int $biasedNumberBetween
 *
 * @method int biasedNumberBetween($min = 0, $max = 100, $function = \'sqrt\')
 *
 * @property string $hexColor
 *
 * @method string hexColor()
 *
 * @property string $safeHexColor
 *
 * @method string safeHexColor()
 *
 * @property array $rgbColorAsArray
 *
 * @method array rgbColorAsArray()
 *
 * @property string $rgbColor
 *
 * @method string rgbColor()
 *
 * @property string $rgbCssColor
 *
 * @method string rgbCssColor()
 *
 * @property string $rgbaCssColor
 *
 * @method string rgbaCssColor()
 *
 * @property string $safeColorName
 *
 * @method string safeColorName()
 *
 * @property string $colorName
 *
 * @method string colorName()
 *
 * @property string $hslColor
 *
 * @method string hslColor()
 *
 * @property array $hslColorAsArray
 *
 * @method array hslColorAsArray()
 *
 * @property string $company
 *
 * @method string company()
 *
 * @property string $companySuffix
 *
 * @method string companySuffix()
 *
 * @property string $jobTitle
 *
 * @method string jobTitle()
 *
 * @property int $unixTime
 *
 * @method int unixTime($max = \'now\')
 *
 * @property \\DateTime $dateTime
 *
 * @method \\DateTime dateTime($max = \'now\', $timezone = null)
 *
 * @property \\DateTime $dateTimeAD
 *
 * @method \\DateTime dateTimeAD($max = \'now\', $timezone = null)
 *
 * @property string $iso8601
 *
 * @method string iso8601($max = \'now\')
 *
 * @property string $date
 *
 * @method string date($format = \'Y-m-d\', $max = \'now\')
 *
 * @property string $time
 *
 * @method string time($format = \'H:i:s\', $max = \'now\')
 *
 * @property \\DateTime $dateTimeBetween
 *
 * @method \\DateTime dateTimeBetween($startDate = \'-30 years\', $endDate = \'now\', $timezone = null)
 *
 * @property \\DateTime $dateTimeInInterval
 *
 * @method \\DateTime dateTimeInInterval($date = \'-30 years\', $interval = \'+5 days\', $timezone = null)
 *
 * @property \\DateTime $dateTimeThisCentury
 *
 * @method \\DateTime dateTimeThisCentury($max = \'now\', $timezone = null)
 *
 * @property \\DateTime $dateTimeThisDecade
 *
 * @method \\DateTime dateTimeThisDecade($max = \'now\', $timezone = null)
 *
 * @property \\DateTime $dateTimeThisYear
 *
 * @method \\DateTime dateTimeThisYear($max = \'now\', $timezone = null)
 *
 * @property \\DateTime $dateTimeThisMonth
 *
 * @method \\DateTime dateTimeThisMonth($max = \'now\', $timezone = null)
 *
 * @property string $amPm
 *
 * @method string amPm($max = \'now\')
 *
 * @property string $dayOfMonth
 *
 * @method string dayOfMonth($max = \'now\')
 *
 * @property string $dayOfWeek
 *
 * @method string dayOfWeek($max = \'now\')
 *
 * @property string $month
 *
 * @method string month($max = \'now\')
 *
 * @property string $monthName
 *
 * @method string monthName($max = \'now\')
 *
 * @property string $year
 *
 * @method string year($max = \'now\')
 *
 * @property string $century
 *
 * @method string century()
 *
 * @property string $timezone
 *
 * @method string timezone($countryCode = null)
 *
 * @property void $setDefaultTimezone
 *
 * @method void setDefaultTimezone($timezone = null)
 *
 * @property string $getDefaultTimezone
 *
 * @method string getDefaultTimezone()
 *
 * @property string $file
 *
 * @method string file($sourceDirectory = \'/tmp\', $targetDirectory = \'/tmp\', $fullPath = true)
 *
 * @property string $randomHtml
 *
 * @method string randomHtml($maxDepth = 4, $maxWidth = 4)
 *
 * @property string $imageUrl
 *
 * @method string imageUrl($width = 640, $height = 480, $category = null, $randomize = true, $word = null, $gray = false, string $format = \'png\')
 *
 * @property string $image
 *
 * @method string image($dir = null, $width = 640, $height = 480, $category = null, $fullPath = true, $randomize = true, $word = null, $gray = false, string $format = \'png\')
 *
 * @property string $email
 *
 * @method string email()
 *
 * @property string $safeEmail
 *
 * @method string safeEmail()
 *
 * @property string $freeEmail
 *
 * @method string freeEmail()
 *
 * @property string $companyEmail
 *
 * @method string companyEmail()
 *
 * @property string $freeEmailDomain
 *
 * @method string freeEmailDomain()
 *
 * @property string $safeEmailDomain
 *
 * @method string safeEmailDomain()
 *
 * @property string $userName
 *
 * @method string userName()
 *
 * @property string $password
 *
 * @method string password($minLength = 6, $maxLength = 20)
 *
 * @property string $domainName
 *
 * @method string domainName()
 *
 * @property string $domainWord
 *
 * @method string domainWord()
 *
 * @property string $tld
 *
 * @method string tld()
 *
 * @property string $url
 *
 * @method string url()
 *
 * @property string $slug
 *
 * @method string slug($nbWords = 6, $variableNbWords = true)
 *
 * @property string $ipv4
 *
 * @method string ipv4()
 *
 * @property string $ipv6
 *
 * @method string ipv6()
 *
 * @property string $localIpv4
 *
 * @method string localIpv4()
 *
 * @property string $macAddress
 *
 * @method string macAddress()
 *
 * @property string $word
 *
 * @method string word()
 *
 * @property array|string $words
 *
 * @method array|string words($nb = 3, $asText = false)
 *
 * @property string $sentence
 *
 * @method string sentence($nbWords = 6, $variableNbWords = true)
 *
 * @property array|string $sentences
 *
 * @method array|string sentences($nb = 3, $asText = false)
 *
 * @property string $paragraph
 *
 * @method string paragraph($nbSentences = 3, $variableNbSentences = true)
 *
 * @property array|string $paragraphs
 *
 * @method array|string paragraphs($nb = 3, $asText = false)
 *
 * @property string $text
 *
 * @method string text($maxNbChars = 200)
 *
 * @property bool $boolean
 *
 * @method bool boolean($chanceOfGettingTrue = 50)
 *
 * @property string $md5
 *
 * @method string md5()
 *
 * @property string $sha1
 *
 * @method string sha1()
 *
 * @property string $sha256
 *
 * @method string sha256()
 *
 * @property string $locale
 *
 * @method string locale()
 *
 * @property string $countryCode
 *
 * @method string countryCode()
 *
 * @property string $countryISOAlpha3
 *
 * @method string countryISOAlpha3()
 *
 * @property string $languageCode
 *
 * @method string languageCode()
 *
 * @property string $currencyCode
 *
 * @method string currencyCode()
 *
 * @property string $emoji
 *
 * @method string emoji()
 *
 * @property string $creditCardType
 *
 * @method string creditCardType()
 *
 * @property string $creditCardNumber
 *
 * @method string creditCardNumber($type = null, $formatted = false, $separator = \'-\')
 *
 * @property \\DateTime $creditCardExpirationDate
 *
 * @method \\DateTime creditCardExpirationDate($valid = true)
 *
 * @property string $creditCardExpirationDateString
 *
 * @method string creditCardExpirationDateString($valid = true, $expirationDateFormat = null)
 *
 * @property array $creditCardDetails
 *
 * @method array creditCardDetails($valid = true)
 *
 * @property string $iban
 *
 * @method string iban($countryCode = null, $prefix = \'\', $length = null)
 *
 * @property string $swiftBicNumber
 *
 * @method string swiftBicNumber()
 *
 * @property string $name
 *
 * @method string name($gender = null)
 *
 * @property string $firstName
 *
 * @method string firstName($gender = null)
 *
 * @property string $firstNameMale
 *
 * @method string firstNameMale()
 *
 * @property string $firstNameFemale
 *
 * @method string firstNameFemale()
 *
 * @property string $lastName
 *
 * @method string lastName($gender = null)
 *
 * @property string $title
 *
 * @method string title($gender = null)
 *
 * @property string $titleMale
 *
 * @method string titleMale()
 *
 * @property string $titleFemale
 *
 * @method string titleFemale()
 *
 * @property string $phoneNumber
 *
 * @method string phoneNumber()
 *
 * @property string $e164PhoneNumber
 *
 * @method string e164PhoneNumber()
 *
 * @property int $imei
 *
 * @method int imei()
 *
 * @property string $realText
 *
 * @method string realText($maxNbChars = 200, $indexSize = 2)
 *
 * @property string $realTextBetween
 *
 * @method string realTextBetween($minNbChars = 160, $maxNbChars = 200, $indexSize = 2)
 *
 * @property string $macProcessor
 *
 * @method string macProcessor()
 *
 * @property string $linuxProcessor
 *
 * @method string linuxProcessor()
 *
 * @property string $userAgent
 *
 * @method string userAgent()
 *
 * @property string $chrome
 *
 * @method string chrome()
 *
 * @property string $msedge
 *
 * @method string msedge()
 *
 * @property string $firefox
 *
 * @method string firefox()
 *
 * @property string $safari
 *
 * @method string safari()
 *
 * @property string $opera
 *
 * @method string opera()
 *
 * @property string $internetExplorer
 *
 * @method string internetExplorer()
 *
 * @property string $windowsPlatformToken
 *
 * @method string windowsPlatformToken()
 *
 * @property string $macPlatformToken
 *
 * @method string macPlatformToken()
 *
 * @property string $iosMobileToken
 *
 * @method string iosMobileToken()
 *
 * @property string $linuxPlatformToken
 *
 * @method string linuxPlatformToken()
 *
 * @property string $uuid
 *
 * @method string uuid()
 */',
    'attributes' => 
    array (
    ),
    'startLine' => 556,
    'endLine' => 985,
    'startColumn' => 1,
    'endColumn' => 1,
    'parentClassName' => NULL,
    'implementsClassNames' => 
    array (
    ),
    'traitClassNames' => 
    array (
    ),
    'immediateConstants' => 
    array (
    ),
    'immediateProperties' => 
    array (
      'providers' => 
      array (
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'name' => 'providers',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 558,
            'endLine' => 558,
            'startTokenPos' => 26,
            'startFilePos' => 11423,
            'endTokenPos' => 27,
            'endFilePos' => 11424,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 558,
        'endLine' => 558,
        'startColumn' => 5,
        'endColumn' => 30,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'formatters' => 
      array (
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'name' => 'formatters',
        'modifiers' => 2,
        'type' => NULL,
        'default' => 
        array (
          'code' => '[]',
          'attributes' => 
          array (
            'startLine' => 559,
            'endLine' => 559,
            'startTokenPos' => 36,
            'startFilePos' => 11455,
            'endTokenPos' => 37,
            'endFilePos' => 11456,
          ),
        ),
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 559,
        'endLine' => 559,
        'startColumn' => 5,
        'endColumn' => 31,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'container' => 
      array (
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'name' => 'container',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => NULL,
        'attributes' => 
        array (
        ),
        'startLine' => 561,
        'endLine' => 561,
        'startColumn' => 5,
        'endColumn' => 23,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
      'uniqueGenerator' => 
      array (
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'name' => 'uniqueGenerator',
        'modifiers' => 4,
        'type' => NULL,
        'default' => NULL,
        'docComment' => '/**
 * @var UniqueGenerator
 */',
        'attributes' => 
        array (
        ),
        'startLine' => 566,
        'endLine' => 566,
        'startColumn' => 5,
        'endColumn' => 29,
        'isPromoted' => false,
        'declaredAtCompileTime' => true,
        'immediateVirtual' => false,
        'immediateHooks' => 
        array (
        ),
      ),
    ),
    'immediateMethods' => 
    array (
      '__construct' => 
      array (
        'name' => '__construct',
        'parameters' => 
        array (
          'container' => 
          array (
            'name' => 'container',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 568,
                'endLine' => 568,
                'startTokenPos' => 65,
                'startFilePos' => 11625,
                'endTokenPos' => 65,
                'endFilePos' => 11628,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Faker\\Container\\ContainerInterface',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 568,
            'endLine' => 568,
            'startColumn' => 33,
            'endColumn' => 69,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 568,
        'endLine' => 571,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'ext' => 
      array (
        'name' => 'ext',
        'parameters' => 
        array (
          'id' => 
          array (
            'name' => 'id',
            'default' => NULL,
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'string',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 582,
            'endLine' => 582,
            'startColumn' => 25,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'Faker\\Extension\\Extension',
            'isIdentifier' => false,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @template T of Extension\\Extension
 *
 * @param class-string<T> $id
 *
 * @throws Extension\\ExtensionNotFound
 *
 * @return T
 */',
        'startLine' => 582,
        'endLine' => 598,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'addProvider' => 
      array (
        'name' => 'addProvider',
        'parameters' => 
        array (
          'provider' => 
          array (
            'name' => 'provider',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 600,
            'endLine' => 600,
            'startColumn' => 33,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 600,
        'endLine' => 605,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'getProviders' => 
      array (
        'name' => 'getProviders',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 607,
        'endLine' => 610,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'unique' => 
      array (
        'name' => 'unique',
        'parameters' => 
        array (
          'reset' => 
          array (
            'name' => 'reset',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 629,
                'endLine' => 629,
                'startTokenPos' => 261,
                'startFilePos' => 13356,
                'endTokenPos' => 261,
                'endFilePos' => 13360,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 629,
            'endLine' => 629,
            'startColumn' => 28,
            'endColumn' => 41,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'maxRetries' => 
          array (
            'name' => 'maxRetries',
            'default' => 
            array (
              'code' => '10000',
              'attributes' => 
              array (
                'startLine' => 629,
                'endLine' => 629,
                'startTokenPos' => 268,
                'startFilePos' => 13377,
                'endTokenPos' => 268,
                'endFilePos' => 13381,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 629,
            'endLine' => 629,
            'startColumn' => 44,
            'endColumn' => 62,
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
 * With the unique generator you are guaranteed to never get the same two
 * values.
 *
 * <code>
 * // will never return twice the same value
 * $faker->unique()->randomElement(array(1, 2, 3));
 * </code>
 *
 * @param bool $reset      If set to true, resets the list of existing values
 * @param int  $maxRetries Maximum number of retries to find a unique value,
 *                         After which an OverflowException is thrown.
 *
 * @throws \\OverflowException When no unique value can be found by iterating $maxRetries times
 *
 * @return self A proxy class returning only non-existing values
 */',
        'startLine' => 629,
        'endLine' => 636,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'optional' => 
      array (
        'name' => 'optional',
        'parameters' => 
        array (
          'weight' => 
          array (
            'name' => 'weight',
            'default' => 
            array (
              'code' => '0.5',
              'attributes' => 
              array (
                'startLine' => 645,
                'endLine' => 645,
                'startTokenPos' => 333,
                'startFilePos' => 13835,
                'endTokenPos' => 333,
                'endFilePos' => 13837,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'float',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 645,
            'endLine' => 645,
            'startColumn' => 30,
            'endColumn' => 48,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'default' => 
          array (
            'name' => 'default',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 645,
                'endLine' => 645,
                'startTokenPos' => 340,
                'startFilePos' => 13851,
                'endTokenPos' => 340,
                'endFilePos' => 13854,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 645,
            'endLine' => 645,
            'startColumn' => 51,
            'endColumn' => 65,
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
 * Get a value only some percentage of the time.
 *
 * @param float $weight A probability between 0 and 1, 0 means that we always get the default value.
 *
 * @return self
 */',
        'startLine' => 645,
        'endLine' => 653,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'valid' => 
      array (
        'name' => 'valid',
        'parameters' => 
        array (
          'validator' => 
          array (
            'name' => 'validator',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 680,
                'endLine' => 680,
                'startTokenPos' => 425,
                'startFilePos' => 15253,
                'endTokenPos' => 425,
                'endFilePos' => 15256,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionUnionType',
              'data' => 
              array (
                'types' => 
                array (
                  0 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'Closure',
                      'isIdentifier' => false,
                    ),
                  ),
                  1 => 
                  array (
                    'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
                    'data' => 
                    array (
                      'name' => 'null',
                      'isIdentifier' => true,
                    ),
                  ),
                ),
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 680,
            'endLine' => 680,
            'startColumn' => 27,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'maxRetries' => 
          array (
            'name' => 'maxRetries',
            'default' => 
            array (
              'code' => '10000',
              'attributes' => 
              array (
                'startLine' => 680,
                'endLine' => 680,
                'startTokenPos' => 434,
                'startFilePos' => 15277,
                'endTokenPos' => 434,
                'endFilePos' => 15281,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'int',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 680,
            'endLine' => 680,
            'startColumn' => 56,
            'endColumn' => 78,
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
 * To make sure the value meet some criteria, pass a callable that verifies the
 * output. If the validator fails, the generator will try again.
 *
 * The value validity is determined by a function passed as first argument.
 *
 * <code>
 * $values = array();
 * $evenValidator = function ($digit) {
 *   return $digit % 2 === 0;
 * };
 * for ($i=0; $i < 10; $i++) {
 *   $values []= $faker->valid($evenValidator)->randomDigit;
 * }
 * print_r($values); // [0, 4, 8, 4, 2, 6, 0, 8, 8, 6]
 * </code>
 *
 * @param ?\\Closure $validator  A function returning true for valid values
 * @param int       $maxRetries Maximum number of retries to find a valid value,
 *                              After which an OverflowException is thrown.
 *
 * @throws \\OverflowException When no valid value can be found by iterating $maxRetries times
 *
 * @return self A proxy class returning only valid values
 */',
        'startLine' => 680,
        'endLine' => 683,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'seed' => 
      array (
        'name' => 'seed',
        'parameters' => 
        array (
          'seed' => 
          array (
            'name' => 'seed',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 685,
                'endLine' => 685,
                'startTokenPos' => 467,
                'startFilePos' => 15397,
                'endTokenPos' => 467,
                'endFilePos' => 15400,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 685,
            'endLine' => 685,
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
        'docComment' => NULL,
        'startLine' => 685,
        'endLine' => 692,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'mode' => 
      array (
        'name' => 'mode',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @see https://www.php.net/manual/en/migration83.deprecated.php#migration83.deprecated.random
 */',
        'startLine' => 697,
        'endLine' => 704,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 20,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'format' => 
      array (
        'name' => 'format',
        'parameters' => 
        array (
          'format' => 
          array (
            'name' => 'format',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 706,
            'endLine' => 706,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'arguments' => 
          array (
            'name' => 'arguments',
            'default' => 
            array (
              'code' => '[]',
              'attributes' => 
              array (
                'startLine' => 706,
                'endLine' => 706,
                'startTokenPos' => 570,
                'startFilePos' => 15876,
                'endTokenPos' => 571,
                'endFilePos' => 15877,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 706,
            'endLine' => 706,
            'startColumn' => 37,
            'endColumn' => 51,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 706,
        'endLine' => 709,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'getFormatter' => 
      array (
        'name' => 'getFormatter',
        'parameters' => 
        array (
          'format' => 
          array (
            'name' => 'format',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 716,
            'endLine' => 716,
            'startColumn' => 34,
            'endColumn' => 40,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param string $format
 *
 * @return callable
 */',
        'startLine' => 716,
        'endLine' => 744,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'parse' => 
      array (
        'name' => 'parse',
        'parameters' => 
        array (
          'string' => 
          array (
            'name' => 'string',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 753,
            'endLine' => 753,
            'startColumn' => 27,
            'endColumn' => 33,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Replaces tokens (\'{{ tokenName }}\') with the result from the token method call
 *
 * @param string $string String that needs to bet parsed
 *
 * @return string
 */',
        'startLine' => 753,
        'endLine' => 760,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'mimeType' => 
      array (
        'name' => 'mimeType',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a random MIME type
 *
 * @example \'video/avi\'
 */',
        'startLine' => 767,
        'endLine' => 770,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'fileExtension' => 
      array (
        'name' => 'fileExtension',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a random file extension (without a dot)
 *
 * @example avi
 */',
        'startLine' => 777,
        'endLine' => 780,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'filePath' => 
      array (
        'name' => 'filePath',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a full path to a new real file on the system.
 */',
        'startLine' => 785,
        'endLine' => 788,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'bloodType' => 
      array (
        'name' => 'bloodType',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get an actual blood type
 *
 * @example \'AB\'
 */',
        'startLine' => 795,
        'endLine' => 798,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'bloodRh' => 
      array (
        'name' => 'bloodRh',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a random resis value
 *
 * @example \'+\'
 */',
        'startLine' => 805,
        'endLine' => 808,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'bloodGroup' => 
      array (
        'name' => 'bloodGroup',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a full blood group
 *
 * @example \'AB+\'
 */',
        'startLine' => 815,
        'endLine' => 818,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'ean13' => 
      array (
        'name' => 'ean13',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a random EAN13 barcode.
 *
 * @example \'4006381333931\'
 */',
        'startLine' => 825,
        'endLine' => 828,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'ean8' => 
      array (
        'name' => 'ean8',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a random EAN8 barcode.
 *
 * @example \'73513537\'
 */',
        'startLine' => 835,
        'endLine' => 838,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'isbn10' => 
      array (
        'name' => 'isbn10',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a random ISBN-10 code
 *
 * @see http://en.wikipedia.org/wiki/International_Standard_Book_Number
 *
 * @example \'4881416324\'
 */',
        'startLine' => 847,
        'endLine' => 850,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'isbn13' => 
      array (
        'name' => 'isbn13',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a random ISBN-13 code
 *
 * @see http://en.wikipedia.org/wiki/International_Standard_Book_Number
 *
 * @example \'9790404436093\'
 */',
        'startLine' => 859,
        'endLine' => 862,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'numberBetween' => 
      array (
        'name' => 'numberBetween',
        'parameters' => 
        array (
          'int1' => 
          array (
            'name' => 'int1',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 869,
                'endLine' => 869,
                'startTokenPos' => 1204,
                'startFilePos' => 19767,
                'endTokenPos' => 1204,
                'endFilePos' => 19767,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 869,
            'endLine' => 869,
            'startColumn' => 35,
            'endColumn' => 43,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'int2' => 
          array (
            'name' => 'int2',
            'default' => 
            array (
              'code' => '2147483647',
              'attributes' => 
              array (
                'startLine' => 869,
                'endLine' => 869,
                'startTokenPos' => 1211,
                'startFilePos' => 19778,
                'endTokenPos' => 1211,
                'endFilePos' => 19787,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 869,
            'endLine' => 869,
            'startColumn' => 46,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a random number between $int1 and $int2 (any order)
 *
 * @example 79907610
 */',
        'startLine' => 869,
        'endLine' => 872,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'randomDigit' => 
      array (
        'name' => 'randomDigit',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a random number between 0 and 9
 */',
        'startLine' => 877,
        'endLine' => 880,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'randomDigitNot' => 
      array (
        'name' => 'randomDigitNot',
        'parameters' => 
        array (
          'except' => 
          array (
            'name' => 'except',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 885,
            'endLine' => 885,
            'startColumn' => 36,
            'endColumn' => 42,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Generates a random digit, which cannot be $except
 */',
        'startLine' => 885,
        'endLine' => 888,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'randomDigitNotZero' => 
      array (
        'name' => 'randomDigitNotZero',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a random number between 1 and 9
 */',
        'startLine' => 893,
        'endLine' => 896,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'randomFloat' => 
      array (
        'name' => 'randomFloat',
        'parameters' => 
        array (
          'nbMaxDecimals' => 
          array (
            'name' => 'nbMaxDecimals',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 903,
                'endLine' => 903,
                'startTokenPos' => 1360,
                'startFilePos' => 20665,
                'endTokenPos' => 1360,
                'endFilePos' => 20668,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 903,
            'endLine' => 903,
            'startColumn' => 33,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'min' => 
          array (
            'name' => 'min',
            'default' => 
            array (
              'code' => '0',
              'attributes' => 
              array (
                'startLine' => 903,
                'endLine' => 903,
                'startTokenPos' => 1367,
                'startFilePos' => 20678,
                'endTokenPos' => 1367,
                'endFilePos' => 20678,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 903,
            'endLine' => 903,
            'startColumn' => 56,
            'endColumn' => 63,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
          'max' => 
          array (
            'name' => 'max',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 903,
                'endLine' => 903,
                'startTokenPos' => 1374,
                'startFilePos' => 20688,
                'endTokenPos' => 1374,
                'endFilePos' => 20691,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 903,
            'endLine' => 903,
            'startColumn' => 66,
            'endColumn' => 76,
            'parameterIndex' => 2,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'float',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Return a random float number
 *
 * @example 48.8932
 */',
        'startLine' => 903,
        'endLine' => 910,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'randomNumber' => 
      array (
        'name' => 'randomNumber',
        'parameters' => 
        array (
          'nbDigits' => 
          array (
            'name' => 'nbDigits',
            'default' => 
            array (
              'code' => 'null',
              'attributes' => 
              array (
                'startLine' => 922,
                'endLine' => 922,
                'startTokenPos' => 1452,
                'startFilePos' => 21329,
                'endTokenPos' => 1452,
                'endFilePos' => 21332,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 922,
            'endLine' => 922,
            'startColumn' => 34,
            'endColumn' => 49,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'strict' => 
          array (
            'name' => 'strict',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 922,
                'endLine' => 922,
                'startTokenPos' => 1459,
                'startFilePos' => 21345,
                'endTokenPos' => 1459,
                'endFilePos' => 21349,
              ),
            ),
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 922,
            'endLine' => 922,
            'startColumn' => 52,
            'endColumn' => 66,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'int',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Returns a random integer with 0 to $nbDigits digits.
 *
 * The maximum value returned is mt_getrandmax()
 *
 * @param int|null $nbDigits Defaults to a random number between 1 and 9
 * @param bool     $strict   Whether the returned number should have exactly $nbDigits
 *
 * @example 79907610
 */',
        'startLine' => 922,
        'endLine' => 928,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'semver' => 
      array (
        'name' => 'semver',
        'parameters' => 
        array (
          'preRelease' => 
          array (
            'name' => 'preRelease',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 940,
                'endLine' => 940,
                'startTokenPos' => 1522,
                'startFilePos' => 21952,
                'endTokenPos' => 1522,
                'endFilePos' => 21956,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 940,
            'endLine' => 940,
            'startColumn' => 28,
            'endColumn' => 51,
            'parameterIndex' => 0,
            'isOptional' => true,
          ),
          'build' => 
          array (
            'name' => 'build',
            'default' => 
            array (
              'code' => 'false',
              'attributes' => 
              array (
                'startLine' => 940,
                'endLine' => 940,
                'startTokenPos' => 1531,
                'startFilePos' => 21973,
                'endTokenPos' => 1531,
                'endFilePos' => 21977,
              ),
            ),
            'type' => 
            array (
              'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
              'data' => 
              array (
                'name' => 'bool',
                'isIdentifier' => true,
              ),
            ),
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 940,
            'endLine' => 940,
            'startColumn' => 54,
            'endColumn' => 72,
            'parameterIndex' => 1,
            'isOptional' => true,
          ),
        ),
        'returnsReference' => false,
        'returnType' => 
        array (
          'class' => 'PHPStan\\BetterReflection\\Reflection\\ReflectionNamedType',
          'data' => 
          array (
            'name' => 'string',
            'isIdentifier' => true,
          ),
        ),
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * Get a version number in semantic versioning syntax 2.0.0. (https://semver.org/spec/v2.0.0.html)
 *
 * @param bool $preRelease Pre release parts may be randomly included
 * @param bool $build      Build parts may be randomly included
 *
 * @example 1.0.0
 * @example 1.0.0-alpha.1
 * @example 1.0.0-alpha.1+b71f04d
 */',
        'startLine' => 940,
        'endLine' => 943,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      'callFormatWithMatches' => 
      array (
        'name' => 'callFormatWithMatches',
        'parameters' => 
        array (
          'matches' => 
          array (
            'name' => 'matches',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 948,
            'endLine' => 948,
            'startColumn' => 46,
            'endColumn' => 53,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @deprecated
 */',
        'startLine' => 948,
        'endLine' => 953,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 2,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      '__get' => 
      array (
        'name' => '__get',
        'parameters' => 
        array (
          'attribute' => 
          array (
            'name' => 'attribute',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 960,
            'endLine' => 960,
            'startColumn' => 27,
            'endColumn' => 36,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param string $attribute
 *
 * @deprecated Use a method instead.
 */',
        'startLine' => 960,
        'endLine' => 965,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      '__call' => 
      array (
        'name' => '__call',
        'parameters' => 
        array (
          'method' => 
          array (
            'name' => 'method',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 971,
            'endLine' => 971,
            'startColumn' => 28,
            'endColumn' => 34,
            'parameterIndex' => 0,
            'isOptional' => false,
          ),
          'attributes' => 
          array (
            'name' => 'attributes',
            'default' => NULL,
            'type' => NULL,
            'isVariadic' => false,
            'byRef' => false,
            'isPromoted' => false,
            'attributes' => 
            array (
            ),
            'startLine' => 971,
            'endLine' => 971,
            'startColumn' => 37,
            'endColumn' => 47,
            'parameterIndex' => 1,
            'isOptional' => false,
          ),
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => '/**
 * @param string $method
 * @param array  $attributes
 */',
        'startLine' => 971,
        'endLine' => 974,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      '__destruct' => 
      array (
        'name' => '__destruct',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 976,
        'endLine' => 979,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
        'aliasName' => NULL,
      ),
      '__wakeup' => 
      array (
        'name' => '__wakeup',
        'parameters' => 
        array (
        ),
        'returnsReference' => false,
        'returnType' => NULL,
        'attributes' => 
        array (
        ),
        'docComment' => NULL,
        'startLine' => 981,
        'endLine' => 984,
        'startColumn' => 5,
        'endColumn' => 5,
        'couldThrow' => false,
        'isClosure' => false,
        'isGenerator' => false,
        'isVariadic' => false,
        'modifiers' => 1,
        'namespace' => 'Faker',
        'declaringClassName' => 'Faker\\Generator',
        'implementingClassName' => 'Faker\\Generator',
        'currentClassName' => 'Faker\\Generator',
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