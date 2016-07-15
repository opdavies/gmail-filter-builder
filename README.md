# gmail-filter-builder

## Description

Inspired by the [gmail-britta](https://github.com/antifuchs/gmail-britta) Ruby library, the Gmail Filter Builder generates XML that can be imported into Gmail’s filter settings.

## Usage

* Run `composer require opdavies/gmail-filter-builder` to download the library.
* Create a new PHP file and require `autoload.php`.
* Create an array of `GmailFilter` objects, each with it’s required methods.
* Pass the filters into an instance of `GmailFilterBuilder`.

```php
require __DIR__ . '/vendor/autoload.php';

$filters = [];

// Add filters.
$filters[] = GmailFilter::create();

...

// Display the output.
print GmailFilterBuilder($filters);
```

To generate the output, run PHP on the file - e.g. `php generate.php`.

By default, the output is displayed on screen. To generate a file, use the greater than symbol followed by a file name - e.g. `php generate.php > filters.xml`.

## Example

For a working example, see the [opdavies/gmail-filters](https://github.com/opdavies/gmail-filters/blob/master/generate.php) repository.

## License

MIT
