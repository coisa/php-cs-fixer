# Coding-style policy

Baseline: [`@auto`](https://cs.symfony.com/doc/ruleSets/Auto.html), on PHP 8.3+.
It combines the latest supported `@PER-CS` with PHP migrations selected from the
consumer's Composer minimum. Explicit `@autoPHPMigration:risky` enables risky PHP
migrations without adding automatic PHPUnit migrations. Run from the Composer
project directory; missing or unresolvable PHP constraints are reported by upstream.
PHP CS Fixer 3.95 resolves the PER alias to `@PER-CS3x0`. Available individual
rules add [PER 3.1](https://www.php-fig.org/per/coding-style/) behavior; this is
not a complete 3.1 checker. Dependency updates can advance the style baseline.

The preset also enables
[`@PhpCsFixer`](https://cs.symfony.com/doc/ruleSets/PhpCsFixer.html) and
[`@PhpCsFixer:risky`](https://cs.symfony.com/doc/ruleSets/PhpCsFixerRisky.html).
Explicit rules retain PER trailing commas in all multiline lists and preserve
parenthesized clone and case expressions. These presets also include PHPUnit
style rules; version-specific PHPUnit migration sets remain an application choice.

CoiSA policies include automatic PHP migration aids,
application-last rule overrides, optional dynamic headers, author-annotation
removal, explicit PHPDoc void/package retention and simplified null returns.
The configuration's opening block comment supplies its own header through
`HeaderExtractor`. Copyright ranges refresh their final year even after the
formatter renders literal dates; `%year%` placeholders remain supported.
`date_time_immutable` is enabled. Automatic multibyte substitutions, property-unset
rewrites and trailing-whitespace removal inside strings are disabled. PHP
risky migrations require behavior review. Consumers choose framework-specific
migrations explicitly.

## PER 3.1 coverage

| PER 3.1 requirement | Enforcement in PHP CS Fixer 3.95 |
| --- | --- |
| Remove extra blocks around case bodies | Explicit `no_unneeded_braces = true`. |
| Use private visibility for non-public enum constants | Explicit `protected_to_private = true`. This rule also narrows eligible protected members of final classes. |
| Compact empty closures | Inherited `single_line_empty_body`. Converting to arrow functions remains a reviewed semantic choice. |
| Put multiline pipe operators first | Inherited `operator_linebreak` on PHP 8.5. Pipe spacing/indentation needs manual review; the binary-spacing rule does not cover `\|>`. |
| Parenthesize clone and multiline case expressions | Explicit `no_unneeded_control_parentheses` options preserve these parentheses. The rules do not insert all missing parentheses. |
| Keep multiline array brackets with their expression | Conforming layout is preserved; general repositioning needs manual review. |
| Format attributes on anonymous classes | Manual review. |
| Terminate nonempty cases | Manual review; inserting a break can change intentional fallthrough. |

## Remaining manual review

Review the [PER 3.1 specification](https://www.php-fig.org/per/coding-style/)
when these constructs appear:

- Prefer the parenthesized clone expression form recommended by PER 3.1.
- Terminate nonempty switch cases and parenthesize
  multiline case conditions; do not change intentional fallthrough without review.
- Review concise empty closures and attributes on anonymous classes.
- Keep a multiline array's opening bracket with its expression.
- Review modifier ordering and any enum visibility changes that the fixer cannot apply.
- Apply pipe-operator rules only in projects whose minimum PHP supports that syntax;
  this package must not introduce PHP 8.5 syntax into its PHP 8.3 source.

Run `composer --no-plugins check` after review. A style-only change must preserve
the observable fixture behavior and idempotence.
