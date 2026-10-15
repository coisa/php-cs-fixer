# coisa/php-cs-fixer

CoiSA's opinionated [PHP CS Fixer](https://github.com/PHP-CS-Fixer/PHP-CS-Fixer)
configuration. This branch prepares an **unreleased major** requiring PHP 8.3+
and PHP CS Fixer `^3.95`. The published 2.1.0 preset uses removed fixer APIs
and cannot load with current PHP CS Fixer. Review this migration before upgrading.

## Installation and use

For a published compatible version, install the Composer development dependency:

```sh
composer require --dev coisa/php-cs-fixer
```

That command selects published releases; it does not install this unreleased PR.
To review this branch, check it out and run:

```sh
composer update --no-plugins --no-scripts --no-interaction --prefer-dist
composer --no-plugins check
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

```php
<?php

declare(strict_types=1);

use CoiSA\PhpCsFixer\PhpCsFixer;

$header = file_get_contents(__DIR__ . '/.docheader');
if ($header === false) {
    throw new RuntimeException('Cannot read the application header.');
}

return PhpCsFixer::create(
    [__DIR__ . '/src'],
    $header,
    ['mb_str_functions' => false]
);
```

Header text can be raw text or a complete block comment. Comment delimiters and
line stars are stripped only from a single complete block. Raw text retains its
asterisks, comment-like suffixes and whitespace. `%year%` is replaced with the
current year. Every configuration retains its own header and overrides;
constructing a configuration does not mutate PHP CS Fixer's global registry.
The third argument overrides preset rules last, including explicit `false`.

The CoiSA preset intentionally includes **risky rules**, preserving its existing
policy. These may change behavior, including string operations and migration
rules. Review the preview, use application overrides and run behavior tests:

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

## Migration from 2.1

- Require PHP 8.3+ and remove any application platform override forcing PHP 7.4.
- The custom preset is now named `@CoiSA/default`, satisfying the public custom
  rule-set naming contract. Consumers of `CustomSet::NAME` pick it up automatically;
  replace any manually written `@CoiSA` rule key.
- `CustomSet` implements `RuleSetDefinitionInterface`; it no longer extends the
  removed internal `AbstractRuleSetDescription`.
- `Config` uses the supported per-configuration `registerCustomRuleSets()` API.
  The unused `RuleSet\RuleSets` global-registration adapter is removed. Consumers
  should create configurations through `PhpCsFixer::create()` or `new Config()`;
  neither requires global registration.
- Migration rules always target PHP 8.3. Running the formatter on PHP 8.5 does
  not switch this target to newer PHP syntax. Current upstream preset/rule names
  replace deprecated aliases; additional CoiSA overrides remain in place.
- Directory exclusions documented with `!` now take effect.

## PER baseline

The style target is **PER Coding Style 3.1**. PHP CS Fixer 3.95 supplies the
versioned `@PER-CS3x0` preset, so it provides automated PER 3.0 support; the 3.1
delta still needs the [documented manual review](docs/coding-style.md).
The preset no longer repeats PSR-12, assignment/concatenation spacing, class
separation, parentheses or brace rules already owned by its upstream presets.
Only intentional CoiSA policies and header configuration remain explicit.
File headers precede `declare`, and multiline lists use the PER trailing commas.

This is a versioned style target with an acknowledged tooling gap, not a claim
that a green formatter certifies every PER 3.1 requirement.
No Composer lock is committed by this library; applications own their locks.

## Reusable PR workflow

Consumers can check only changed PHP files using their own Composer-installed
fixer and configuration. Replace the ref placeholder with a reviewed immutable
commit from this repository; the workflow is not yet part of a published release.

```yaml
name: PHP style
on: [pull_request]
permissions:
  contents: read
jobs:
  style:
    uses: coisa/php-cs-fixer/.github/workflows/check-php.yml@REVIEWED_COMMIT_SHA
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

## Verification

`composer --no-plugins check` runs PHPUnit 12.5 and checks this repository with its
own preset. Run `composer test` for all tests, or select a suite directly:

```sh
vendor/bin/phpunit --testsuite unit
vendor/bin/phpunit --testsuite integration
```

The PHPUnit suites cover the current public definition interface,
per-configuration header isolation, override precedence, preserved risky policy,
exclusion behavior, real CLI dry-run/apply, header
normalization, PHP lint, unchanged synthetic output and second-pass idempotency.
CLI tests use Symfony Process with a timeout and a fresh temporary directory per
test. Warnings, risky tests and deprecations fail the suite.

CI exercises PHP 8.3 with lowest/highest dependencies and PHP 8.4/8.5 with highest
dependencies. The local PHP 8.5.11 run uses PHP CS Fixer 3.95.27; actual CI results
apply to their exact commit. No release is performed by preparing this PR.
