# Changelog

This file records notable changes to the project.

The format follows [Keep a Changelog](https://keepachangelog.com/en/1.1.0/),
and releases use [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [3.0.0] - 2026-10-15

### Added

- Static `HeaderExtractor` methods to read the configuration's opening block comment without executing it.
- PHPUnit 12.5 unit and integration suites covering configuration isolation, headers, overrides, exclusions, CLI behavior and idempotency, with a PHP 8.3–8.5 dependency matrix.
- A read-only reusable workflow for changed PR PHP files that preserves the caller's Finder scope and disables Composer plugins and scripts.
- Weekly Dependabot updates for Composer dependencies and GitHub Actions.
- Historical changelog entries and matching GitHub release notes for versions 1.0.0–2.1.0.

### Changed

- Require PHP 8.3+ and PHP CS Fixer `^3.95`, without a forced PHP 7.4 Composer platform.
- Use the public rule-set definition interface and rename `@CoiSA` to `@CoiSA/default`.
- Rename `CoiSA\PhpCsFixer\RuleSet\Sets\CustomSet` to `CoiSA\PhpCsFixer\RuleSet\DefaultSet`.
- Register definitions per configuration through the public API, keeping headers and application-last rule overrides independent.
- Use `@auto` and `@autoPHPMigration:risky` alongside `@PhpCsFixer` and `@PhpCsFixer:risky`; derive PHP migration targets from the consumer's Composer minimum.
- Enable `date_time_immutable` and disable automatic multibyte substitutions, property-unset rewrites and trailing-whitespace removal inside strings.
- Apply available PER 3.1 rules for redundant case braces and private enum constants, and preserve PER trailing commas and clone/case parentheses across presets.
- Keep README and documentation focused on current usage, with release history and upgrade changes in the changelog and release notes.
- Reference GitHub Actions by major version.
- Mark test classes and their filesystem support class as internal.
- Limit package export exclusions to existing development and test files, retaining the README, changelog, license and coding-style documentation.

### Removed

- The unused global-registration adapter `CoiSA\PhpCsFixer\RuleSet\RuleSets`.
- The separate `.docheader` template, replaced by the configuration's own opening block comment.
- The Doctrine style set, redundant formatting overrides and fixed PHPUnit 8.4 migration.
- The Lando configuration and Makefile.

### Fixed

- Honor `!directory` exclusions before checking whether a path exists.
- Normalize raw, single-line and multiline PHPDoc headers, including the nonempty header `0`.
- Preserve asterisks, suffixes and whitespace in raw headers; strip comment syntax only from a single complete block.
- Refresh copyright end years after the formatter writes literal dates, preserving creation years and other dates.

## [2.1.0] - 2022-05-27

### Added

- Application-specific rules through the factory's third argument, applied after the preset.

### Changed

- Require PHP `^7.4 || ^8.0` and PHP CS Fixer `^3.8`, with a PHP 7.4 Composer platform.
- Separate configuration, file discovery and preset definitions into `Config`, `Finder`, `RuleSet\RuleSets` and `RuleSet\Sets\CustomSet`, keeping `PhpCsFixer::create()` as the factory.
- Use PSR-12, PHP CS Fixer and Doctrine presets, PHP migration sets through 8.1, and risky PHPUnit 8.4 migration rules.

## [2.0.0] - 2021-12-29

### Added

- Runtime-selected PHP 8.1 migration sets.

### Changed

- Require PHP `^7.2|^8.0` and PHP CS Fixer `^3.3`.
- Adapt configuration creation and rule options to PHP CS Fixer 3, including header comments, operator alignment and class-member separation.
- Rename the project configuration to `.php-cs-fixer.dist.php`.

## [1.0.4] - 2021-12-29

### Changed

- Constrain dependencies to PHP `^5.3|^7.0` and PHP CS Fixer `^2.2`.
- Update the Lando PHP service and Xdebug configuration.

### Fixed

- Correct the Lando application name.

## [1.0.3] - 2020-10-19

### Added

- A Lando development environment, Makefile helpers, EditorConfig and package export exclusions.

### Changed

- Select PHP migration rules through PHP 8.0 according to the runtime, and choose long or short array syntax according to PHP support.
- Expand Doctrine annotation, PHPDoc, return-spacing and random API rules.
- Filter configured rule names against the installed built-in fixers.

## [1.0.2] - 2020-10-18

### Fixed

- Remove the return-type declaration from configuration creation to restore PHP 5 syntax compatibility.

## [1.0.1] - 2020-10-13

### Added

- The MIT license file.

### Changed

- Adopt the PSR-2 preset and refine array indentation, closure spacing, trait imports and multiline array commas.

## [1.0.0] - 2020-08-18

### Added

- The CoiSA PHP CS Fixer configuration and `PhpCsFixer::create()` factory with Finder-based source selection.
- Opinionated formatting and risky rules, plus optional PHPDoc headers with `%year%` substitution.
- Composer dependencies on PHP `>=5.3` and PHP CS Fixer `>=2.2`.

[3.0.0]: https://github.com/coisa/php-cs-fixer/compare/2.1.0...3.0.0
[2.1.0]: https://github.com/coisa/php-cs-fixer/compare/2.0.0...2.1.0
[2.0.0]: https://github.com/coisa/php-cs-fixer/compare/1.0.4...2.0.0
[1.0.4]: https://github.com/coisa/php-cs-fixer/compare/1.0.3...1.0.4
[1.0.3]: https://github.com/coisa/php-cs-fixer/compare/1.0.2...1.0.3
[1.0.2]: https://github.com/coisa/php-cs-fixer/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/coisa/php-cs-fixer/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/coisa/php-cs-fixer/releases/tag/1.0.0
