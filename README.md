# Gmail Filter Builder

[![Build Status](https://travis-ci.org/opdavies/gmail-filter-builder.svg?branch=master)](https://travis-ci.org/opdavies/gmail-filter-builder)

This library allows you to define Gmail filters in PHP, and then generate XML that you can import into Gmail's filter settings

Inspired by https://github.com/antifuchs/gmail-britta.

## Installation and Basic Usage

### Step 1: Require the Library

```bash
composer require opdavies/gmail-filter-builder
```

### Step 2: Create Your Filters File

Create a `filters.php` file that returns an array of `Filter` objects.

For example:

```php
<?php

require_once __DIR__.'/vendor/autoload.php';

return [
    // Add your filters.
];
```

### Step 3: Generate the XML

Run `./vendor/bin/generate-filters` to generate the XML for the filters and export it into a file.

#### Options

- `--input-file` - specify the name of the file containing the filters (defaults to `filters.php`).
- `--output-file` - specify the name of the output file (defaults to `filters.xml`).

### Step 4: Import the Filters

Log in to your Gmail account and import your filters using the generated XML file.

## Available Methods

### Conditions

_Conditions that a message must satisfy for the filter to be applied:_

- `has` - can be used to check for various properties, such as attachments, stars or labels. Can also be used as an alternative to some of the following methods previously - e.g. `from:john@example.com` - and can be useful for more advanced queries.
- `hasNot` - the opposite of the above.
- `from` - if the message is from a certain name or email address.
- `to` - if the message is to a certain name or email address.
- `subject` - if the message has a certain subject.
- `hasAttachment` - if the message has an attachment.
- `fromList` - if the message is from a mailing list.
- `excludeChats` - exclude chats from the results (false by default).

### Actions

_Actions you can apply to messages that match the conditions:_

- `label` - add a label to the message.
- `archive` - archive the message (skip the inbox).
- `labelAndArchive` - both add a label and archive the message.
- `spam` - mark the message as spam.
- `neverSpam` - never mark the message as spam.
- `trash` - delete the message.
- `read` - mark the message as read.
- `star` - star the message.
- `forward` - forward the message to another email address.
- `important` - mark the message as important.
- `notImportant` - mark the message as not important.
- `categorise` - apply a smart label to the message.

## Filter Examples

```php
// If an email is from a certain address, add a label.
Filter::create()
    ->from('john@example.com')
    ->label('Something');
```

## References

- [Search operators you can use with Gmail](https://support.google.com/mail/answer/7190?hl=en)
