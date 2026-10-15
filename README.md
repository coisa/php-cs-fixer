# coisa/php-cs-fixer

CoiSA's opinionated [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)
configuration for PHP 8.3+ and PHP CS Fixer `^3.95`.

## Installation and use

Install the Composer development dependency:

```sh
composer require --dev coisa/php-cs-fixer
```

Create an application `.php-cs-fixer.dist.php`:

```php
<?php

declare(strict_types=1);

use CoiSA\PhpCsFixer\PhpCsFixer;

return PhpCsFixer::create([
    __DIR__ . '/src',
    __DIR__ . '/tests',
    '!generated',
]);
```

Exclusions name directories relative to the scanned roots, as in Symfony Finder.
The `!` prefix is processed before checking whether a path exists. Explicit files
can also be listed. The finder honors VCS ignores and includes dotfiles.
Scope the roots to owned source; do not scan installed dependencies.

## Headers and rule overrides

Use the configuration's own opening block comment as the header template:

```php
<?php

/**
 * Copyright (c) 2020-2026 Your Team
 */

declare(strict_types=1);

use CoiSA\PhpCsFixer\HeaderExtractor;
use CoiSA\PhpCsFixer\PhpCsFixer;

$header = HeaderExtractor::extractFromFile(__FILE__);

return PhpCsFixer::create(
    [__DIR__ . '/src'],
    $header,
    ['ordered_imports' => true]
);
```

`HeaderExtractor` reads PHP tokens without executing the file. It selects only
the opening block comment before declarations or executable code, and returns
an empty string when there is no such header. An unreadable file raises a
`RuntimeException`. No separate header file is needed.

Explicit header text accepts raw text or a complete block comment. Comment delimiters and
line stars are stripped only from a single complete block. Raw text retains its
asterisks, comment-like suffixes and whitespace. `%year%` is replaced with the
current year. Copyright lines retain their creation year and refresh the final
year. A copyright beginning in the current year stays a single year; future
creation years and dates outside copyright lines are preserved. Reading a header
previously rendered by the formatter keeps this annual update working.
Every configuration retains its own header and overrides;
constructing a configuration does not mutate PHP CS Fixer's global registry.
The third argument overrides preset rules last, including explicit `false`.

The CoiSA preset includes **risky rules**, which may change application behavior.
Review the preview, use application overrides and run behavior tests:

```sh
vendor/bin/php-cs-fixer fix --dry-run --diff
vendor/bin/php-cs-fixer fix
vendor/bin/php-cs-fixer fix --dry-run --diff
```

A dry-run exit 8 means formatting changes are needed. A successful second pass
should return 0 with no changes.
The preset enables `date_time_immutable`. It disables `mb_str_functions`,
`no_unset_on_property` and `no_trailing_whitespace_in_string` to preserve byte
operations, property unsetting and literal string contents.

## PER baseline

The `@CoiSA/default` preset is defined by `CoiSA\PhpCsFixer\RuleSet\DefaultSet`.
Its baseline is `@auto`: the latest supported `@PER-CS` style and PHP migrations
selected from the consumer's Composer minimum. `@autoPHPMigration:risky` enables
risky PHP migrations. Framework-specific migrations are application choices.
[`@PhpCsFixer`](https://cs.symfony.com/doc/ruleSets/PhpCsFixer.html) and
[`@PhpCsFixer:risky`](https://cs.symfony.com/doc/ruleSets/PhpCsFixerRisky.html)
add the team's opinionated style and behavior-changing rules. Explicit CoiSA
rules preserve PER trailing commas, clone/case parentheses and the policies above.
Run the fixer from the Composer project directory with an explicit PHP constraint.
The Composer minimum controls the migration target, even on a newer host runtime.

PHP CS Fixer 3.95 resolves its PER base to 3.0. Individual rules apply available
PER 3.1 updates for redundant case braces and private enum constants; see the
[coverage and remaining manual review](docs/coding-style.md).
Updating PHP CS Fixer can advance the PER baseline.
File headers precede `declare`, and multiline lists use the PER trailing commas.

Some PER 3.1 requirements need manual review, as documented in the coverage guide.
Applications own their Composer lock files.

## Reusable PR workflow

Consumers can check only changed PHP files using their own Composer-installed
fixer and configuration. Replace the ref placeholder with the desired release
tag from this repository.

```yaml
name: PHP style
on: [pull_request]
permissions:
  contents: read
jobs:
  style:
    uses: coisa/php-cs-fixer/.github/workflows/check-php.yml@RELEASE_TAG
    with:
      php-version: '8.3'
      working-directory: '.'
      config: '.php-cs-fixer.dist.php'
```

The workflow checks added/copied/modified/renamed PHP files against the PR base,
handles filenames with spaces and ignores deleted files. `--path-mode=intersection`
retains the application's Finder exclusions. No changed files is a successful no-op.
Calls outside `pull_request` must supply the `base-sha` input explicitly.
Composer plugins/scripts and persisted checkout credentials are disabled; no secrets
are required. Use a read-only PR event, not `pull_request_target` executing PR code.
Keep full checks as well when changing preset/configuration or dependency versions.

## Development

Install dependencies and run the checks:

```sh
composer update --no-plugins --no-scripts --no-interaction --prefer-dist
composer --no-plugins check
```

`composer --no-plugins check` runs PHPUnit 12.5 and checks this repository with its
own preset. Run `composer test` for all tests, or select a suite directly:

```sh
vendor/bin/phpunit --testsuite unit
vendor/bin/phpunit --testsuite integration
```

The PHPUnit suites cover the current public definition interface,
per-configuration header isolation, override precedence, explicit risky policy,
exclusion behavior, real CLI dry-run/apply, header extraction and normalization,
copyright-year updates, PHP lint, unchanged synthetic output and second-pass idempotency.
Formatting fixtures verify the available PER 3.1 additions. CLI cases verify that
the consumer's Composer PHP minimum controls syntax migrations across host versions.
CLI tests use Symfony Process with a timeout and a fresh temporary directory per
test. Warnings, risky tests and deprecations fail the suite.

CI exercises PHP 8.3 with lowest/highest dependencies and PHP 8.4/8.5 with highest
dependencies.
