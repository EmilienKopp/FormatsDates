# Laravel Date Formatter

A Laravel trait for elegant date formatting with automatic format detection and magic method support.

## Installation

You can install the package via composer:

```bash
composer emilienkopp/formats-dates-trait
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

Now you can format dates using magic methods:

```php
$user->createdAt(); // Returns in Y-m-d H:i:s format
$user->birthDate(); // Returns in Y-m-d format
$user->loginTime(); // Returns in H:i:s format

// Custom format
$user->birthDate('d/m/Y'); // Returns in custom format
```

## Features

- Automatic format detection based on property names and types
- Support for date, datetime, and time formats
- Magic method support with suffix detection
- Protected property access support
- Null-safe operations
- Custom format override support

## Testing

```bash
composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE) for more information.