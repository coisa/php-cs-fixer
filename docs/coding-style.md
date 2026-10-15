# Coding-style policy

Baseline: [`@auto`](https://cs.symfony.com/doc/ruleSets/Auto.html), on PHP 8.3+.
It combines the latest supported `@PER-CS` with PHP migrations selected from the
consumer's Composer minimum. Explicit `@autoPHPMigration:risky` retains PHP
migrations without adding automatic PHPUnit migrations. Run from the Composer
project directory; missing or unresolvable PHP constraints are reported by upstream.
PHP CS Fixer 3.95.27 resolves the PER alias to `@PER-CS3x0`. Available individual
rules add [PER 3.1](https://www.php-fig.org/per/coding-style/) behavior; this is
not a complete 3.1 checker. Dependency updates can advance the style baseline.
The preset does not also enable `@PhpCsFixer`,
`@PhpCsFixer:risky`, `@DoctrineAnnotation` or a separate PER risky style set.
PSR-12 is inherited; duplicate formatting selections are unnecessary.

The remaining CoiSA-specific decisions are automatic PHP migration aids,
application-last rule overrides, optional dynamic headers, author-annotation
removal, explicit PHPDoc void/package retention and simplified null returns.
The configuration's opening block comment supplies its own header through
`HeaderExtractor`. Copyright ranges refresh their final year even after the
formatter renders literal dates; `%year%` placeholders remain supported.
`date_time_immutable` is enabled. Automatic multibyte substitutions, property-unset
rewrites and trailing-whitespace removal inside strings are disabled. PHP
risky migrations remain enabled and require behavior review.
The old fixed PHPUnit 8.4 migration is removed; consumers choose framework-specific
migrations explicitly rather than inheriting a historical version from this package.

## PER 3.0 to 3.1 coverage

| PER 3.1 requirement | Enforcement in PHP CS Fixer 3.95.27 |
| --- | --- |
| Remove extra blocks around case bodies | Explicit `no_unneeded_braces = true`, with a formatting regression. |
| Use private visibility for non-public enum constants | Explicit `protected_to_private = true`, with a formatting regression. This rule also narrows eligible protected members of final classes. |
| Compact empty closures | Inherited `single_line_empty_body`, with a formatting regression. Converting to arrow functions remains a reviewed semantic choice. |
| Put multiline pipe operators first | Inherited `operator_linebreak`, tested on PHP 8.5. Pipe spacing/indentation still needs review; the installed binary-spacing rule does not cover `\|>`. |
| Parenthesize clone and multiline case expressions | Conforming parentheses are preserved and tested. The installed rules do not insert all missing parentheses. |
| Keep multiline array brackets with their expression | Conforming layout is preserved and tested; general repositioning still needs manual review. |
| Format attributes on anonymous classes | Manual review; no complete matching formatter rule was found. |
| Terminate nonempty cases | Manual review; inserting a break can change intentional fallthrough. |

## Remaining manual review

For changed code, review the [3.1 migration guide](https://github.com/php-fig/per-coding-style/blob/master/migration-3.1.md)
and the normative rule when a construct appears:

- Prefer the parenthesized clone expression form recommended by PER 3.1.
- Terminate nonempty switch cases and parenthesize
  multiline case conditions; do not change intentional fallthrough without review.
- Review concise empty closures and attributes on anonymous classes.
- Keep a multiline array's opening bracket with its expression.
- Review modifier ordering and any enum visibility changes that the fixer cannot apply.
- Apply pipe-operator rules only in projects whose minimum PHP supports that syntax;
  this package must not introduce PHP 8.5 syntax into its PHP 8.3 source.

The package source has no clone, switch, enum, pipe or attributed anonymous class
construct requiring a 3.1 transformation. Its headers, declarations, multiline
lists and array openings were reviewed after formatting. This observation is
scoped to this patch, not a certification of future consumer code.

Run `composer --no-plugins check` after review. A style-only change must preserve
the observable fixture behavior and idempotence. Public API identifiers such as
`PhpCsFixer` are preserved; an acronym recommendation alone does not authorize
renaming an exported class or changing its autoload path.
