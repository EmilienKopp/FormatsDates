# Laravel Dates Formatter Trait

[![Latest Version on Packagist](https://img.shields.io/packagist/v/emilienkopp/formats-dates-trait.svg?style=flat-square)](https://packagist.org/packages/emilienkopp/formats-dates-trait)
[![Total Downloads](https://img.shields.io/packagist/dt/emilienkopp/formats-dates-trait.svg?style=flat-square)](https://packagist.org/packages/emilienkopp/formats-dates-trait)
[![License](https://img.shields.io/packagist/l/emilienkopp/formats-dates-trait?style=flat-square)](LICENSE.md)

A Laravel trait that provides elegant date formatting capabilities to your models with automatic format detection and magic method support.

## Features

- 🔄 Automatic format detection based on property names and types
- ✨ Magic method support for cleaner syntax
- 🔍 Reflection-based property access
- 🛡️ Null-safe operations
- 🎨 Customizable date formats
- 🎯 Type-hint aware

## Installation

You can install the package via composer:

```bash
composer require emilienkopp/formats-dates-trait
```

## Usage

Add the trait to your model:

```php
use EmilienKopp\DatesFormatter\FormatsDates;

class User extends Model
{
    use FormatsDates;

    protected $dates = [
        'created_at',
        'updated_at',
        'birth_date',
        'login_time'
    ];
}
```

Now you can use 'getFormattedDate' method or magic methods to format your dates.

⚠️ Note: magic methods are `camelCase` versions of the property names.

### Automatic Format Detection

The trait automatically determines the appropriate format based on property names and types. It uses strict suffix matching to avoid false positives.

#### Format Detection Rules (in order of precedence)

1. **Type hints**
   - Properties typed as `DateTime` → Uses datetime format

2. **Property name suffixes**
   - Ends with `_datetime` or `_at` → Uses datetime format
   - Ends with `_time` → Uses time format
   - Ends with `_date` → Uses date format
   - All others → Uses default date format

Magic methods are generated based on the suffixes. 
Therefore, any property that does not follow the suffix rules will not have a magic method invoked
and will likely throw a `BadMethodCallException`.

#### Examples

```php
// DateTime format (Y-m-d H:i:s)
created_at          ✓
updated_at          ✓
deleted_datetime    ✓
attestation         ✗ (no underscored suffix)
updater_name        ✗ (not a supported suffix)

// Time format (H:i:s)
login_time          ✓
start_time          ✓
datetime_time       ✓
overtime            ✗ (no underscore prefix)

// Date format (Y-m-d)
birth_date          ✓
publish_date        ✓
mandated_by         ✗ (not a supported suffix)
validate            ✗ (no underscore prefix)
```

### Basic Usage

```php
// Using magic methods
$user->createdAt();        // Returns '2024-03-13'
$user->updatedAt();        // Returns '2024-03-13'
$user->loginTime();        // Returns '14:30:00'

// Using custom formats
$user->createdAt('d/m/Y'); // Returns '13/03/2024'
$user->loginTime('H:i');   // Returns '14:30'

// Using the direct method
$user->getFormattedDate('created_at');           // Uses auto-detected format
$user->getFormattedDate('created_at', 'd/m/Y');  // Uses custom format
```

### Customizing Default Formats

You can customize the default formats for your model:

```php
// Customize date format
User::setDefaultDateFormat('d/m/Y');

// Customize datetime format
User::setDefaultDatetimeFormat('d/m/Y H:i');

// Customize time format
User::setDefaultTimeFormat('H:i');

// Get current formats
$dateFormat = User::getDefaultDateFormat();
$datetimeFormat = User::getDefaultDatetimeFormat();
```

⚠️ Note: Format changes are applied per model class, not globally.

### Null Safety

The trait handles null values gracefully:

```php
$user->birth_date = null;
$user->birthDate(); // Returns null
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

### Security

If you discover any security related issues, please contact me instead of using the issue tracker.

## Support Laravel Versions

- Laravel 7.x through 11.x
- PHP 7.4 or higher