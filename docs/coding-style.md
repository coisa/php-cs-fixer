# Coding-style policy

Target: [PER Coding Style 3.1](https://www.php-fig.org/per/coding-style/), on PHP 8.3+.
The tested PHP CS Fixer 3.95 versions implement the `@PER-CS3x0` ruleset, not a
complete 3.1 checker. The versioned PER 3.0 set is applied after upstream opinionated
sets so its spacing/type/list decisions take precedence. PSR-12 is inherited;
its explicit duplicate selection and covered CoiSA formatting overrides are removed.

The remaining CoiSA-specific decisions are Doctrine annotation formatting,
PHP 8.3 migration aids, application-last rule overrides, optional dynamic headers,
author-annotation removal, explicit PHPDoc void/package retention, mb string
conversion and disabling automatic DateTime-to-immutable replacement. Risky rules
remain enabled by the established preset policy and require behavior review.
The old fixed PHPUnit 8.4 migration is removed; consumers choose framework-specific
migrations explicitly rather than inheriting a historical version from this package.

## Manual 3.0 to 3.1 review

For changed code, review the [3.1 migration guide](https://github.com/php-fig/per-coding-style/blob/master/migration-3.1.md)
and the normative rule when a construct appears:

- Use the required parenthesized clone expression form.
- Terminate nonempty switch cases, avoid an extra case brace block and parenthesize
  multiline case conditions; do not change intentional fallthrough without review.
- Review concise empty closures and attributes on anonymous classes.
- Keep a multiline array's opening bracket with its expression.
- Review private non-public enum constants and supported modifier ordering.
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
