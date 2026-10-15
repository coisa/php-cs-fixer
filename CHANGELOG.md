# Changelog

## Unreleased — next major

- Require PHP 8.3+ and PHP CS Fixer ^3.95, removing the forced PHP 7.4 platform.
- Implement the current public rule-set definition interface and use a valid
  namespaced preset identifier, `@CoiSA/default`, replacing `@CoiSA`.
- Register definitions on each configuration through the supported API, preserving
  independent header/rule choices without reflection or constructor global mutation.
- Remove the unused global-registration adapter `RuleSet\RuleSets`.
- Rename `RuleSet\Sets\CustomSet` to `RuleSet\DefaultSet`, matching the default
  preset and removing the redundant namespace level.
- Use `@auto` with explicit risky PHP migrations, following the consumer's
  Composer PHP minimum rather than the host runtime, and retain application-last overrides.
- Enable `date_time_immutable` in the CoiSA preset.
- Disable automatic multibyte substitutions, property-unset rewrites and removal
  of trailing whitespace inside strings to preserve their behavior and contents.
- Fix !directory exclusions being skipped by the existence check.
- Normalize raw, single-line and multiline PHPDoc headers, including the nonempty header `0`.
- Preserve raw header asterisks, suffixes and whitespace; strip comment syntax only
  from a single complete block.
- Extract the header from the configuration's opening block comment without
  executing it; remove the separate `.docheader` file.
- Expose header extraction through static methods with documented contracts,
  and use early returns and spacing between logical blocks in the header classes.
- Keep copyright years current after the formatter writes literal dates back to
  the configuration, preserving creation years and other dates.
- Add PHPUnit 12.5 unit and integration suites, including header regressions,
  configuration isolation and bounded CLI/behavior/idempotency checks, with
  a PHP 8.3–8.5 dependency matrix.
- Document current usage, headers, overrides, risks and package distribution;
  keep release history and upgrade changes in the changelog and release notes.
- Backfill changelog entries and GitHub release notes for versions 1.0.0–2.1.0
  from the tagged source history.
- Inherit the latest supported `@PER-CS` through `@auto`, and add available PER
  3.1 rules for redundant case braces and private enum constants, with formatting
  regressions and documentation of the remaining tooling gaps.
- Combine `@PhpCsFixer` and `@PhpCsFixer:risky` with `@auto`, preserving PER
  trailing commas, clone/case parentheses and explicit CoiSA rule overrides.
- Remove the Doctrine style set, redundant formatting overrides and the fixed
  PHPUnit 8.4 migration.
- Provide a read-only reusable workflow for changed PR PHP files, preserving caller
  Finder scope and disabling Composer plugins/scripts.
- Reference actions by major version and add weekly Dependabot updates for
  Composer dependencies and GitHub Actions.
- Mark test classes and their filesystem support class as internal.
- Limit package export exclusions to existing development and test files,
  retaining the README, changelog, license and coding-style documentation.
- Remove the Lando configuration and Makefile.

## [2.1.0](https://github.com/coisa/php-cs-fixer/releases/tag/2.1.0) — 2022-05-27

- Require PHP `^7.4 || ^8.0` and PHP CS Fixer `^3.8`, with a PHP 7.4 Composer platform.
- Separate configuration, file discovery and preset definitions into `Config`, `Finder`, `RuleSet\RuleSets` and `RuleSet\Sets\CustomSet`, keeping `PhpCsFixer::create()` as the factory.
- Allow application-specific rules through the factory's third argument, applied after the preset.
- Use PSR-12, PHP CS Fixer and Doctrine presets, PHP migration sets through 8.1, and risky PHPUnit 8.4 migration rules.

[Full changelog](https://github.com/coisa/php-cs-fixer/compare/2.0.0...2.1.0)

## [2.0.0](https://github.com/coisa/php-cs-fixer/releases/tag/2.0.0) — 2021-12-29

- Require PHP `^7.2|^8.0` and PHP CS Fixer `^3.3`.
- Adapt configuration creation and rule options to PHP CS Fixer 3, including header comments, operator alignment and class-member separation.
- Rename the project configuration to `.php-cs-fixer.dist.php`.
- Add runtime-selected PHP 8.1 migration sets.

[Full changelog](https://github.com/coisa/php-cs-fixer/compare/1.0.4...2.0.0)

## [1.0.4](https://github.com/coisa/php-cs-fixer/releases/tag/1.0.4) — 2021-12-29

- Constrain dependencies to PHP `^5.3|^7.0` and PHP CS Fixer `^2.2`.
- Correct the Lando application name and update its PHP service and Xdebug configuration.

[Full changelog](https://github.com/coisa/php-cs-fixer/compare/1.0.3...1.0.4)

## [1.0.3](https://github.com/coisa/php-cs-fixer/releases/tag/1.0.3) — 2020-10-19

- Select PHP migration rules through PHP 8.0 according to the runtime, and choose long or short array syntax according to PHP support.
- Expand Doctrine annotation, PHPDoc, return-spacing and random API rules.
- Filter configured rule names against the installed built-in fixers.
- Add a Lando development environment, Makefile helpers, EditorConfig and package export exclusions.

[Full changelog](https://github.com/coisa/php-cs-fixer/compare/1.0.2...1.0.3)

## [1.0.2](https://github.com/coisa/php-cs-fixer/releases/tag/1.0.2) — 2020-10-18

- Remove the return-type declaration from configuration creation to restore PHP 5 syntax compatibility.

[Full changelog](https://github.com/coisa/php-cs-fixer/compare/1.0.1...1.0.2)

## [1.0.1](https://github.com/coisa/php-cs-fixer/releases/tag/1.0.1) — 2020-10-13

- Adopt the PSR-2 preset and refine array indentation, closure spacing, trait imports and multiline array commas.
- Add the MIT license file.

[Full changelog](https://github.com/coisa/php-cs-fixer/compare/1.0.0...1.0.1)

## [1.0.0](https://github.com/coisa/php-cs-fixer/releases/tag/1.0.0) — 2020-08-18

- Introduce the CoiSA PHP CS Fixer configuration and `PhpCsFixer::create()` factory with Finder-based source selection.
- Provide opinionated formatting and risky rules, plus optional PHPDoc headers with `%year%` substitution.
- Declare PHP `>=5.3` and PHP CS Fixer `>=2.2` dependencies.

[Source at 1.0.0](https://github.com/coisa/php-cs-fixer/tree/1.0.0)
