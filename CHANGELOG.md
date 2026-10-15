# Changelog

## Unreleased — next major

- Require PHP 8.3+ and PHP CS Fixer ^3.95, removing the forced PHP 7.4 platform.
- Implement the current public rule-set definition interface and use a valid
  namespaced preset identifier, `@CoiSA/default`.
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
- Refresh migration, header, override, risk and package-distribution guidance.
- Inherit the latest supported `@PER-CS` through `@auto`, and add available PER
  3.1 rules for redundant case braces and private enum constants, with formatting
  regressions and documentation of the remaining tooling gaps.
- Remove the opinionated PHP CS Fixer/Doctrine style sets, inherited formatting
  overrides and the fixed PHPUnit 8.4 migration.
- Provide a read-only reusable workflow for changed PR PHP files, preserving caller
  Finder scope and disabling Composer plugins/scripts.

Published 2.1.0 predates this major. This branch has not been released.
