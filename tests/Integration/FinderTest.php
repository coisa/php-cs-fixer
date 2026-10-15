<?php

/**
 * This file is part of coisa/php-cs-fixer.
 *
 * This source file is subject to the license that is bundled
 * with this source code in the file LICENSE.
 *
 * @link      https://github.com/coisa/php-cs-fixer
 * @copyright Copyright (c) 2020-2026 Felipe Sayão Lobato Abreu <github@felipeabreu.com.br>
 * @license   https://opensource.org/licenses/MIT MIT License
 */

declare(strict_types=1);

namespace CoiSA\PhpCsFixer\Tests\Integration;

use CoiSA\PhpCsFixer\Finder;
use CoiSA\PhpCsFixer\Tests\FilesystemTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(Finder::class)]
final class FinderTest extends FilesystemTestCase
{
    public function testFindsPhpFilesIncludingDotFiles(): void
    {
        $first = $this->writeFile('sample.php', '<?php');
        $hidden = $this->writeFile('.hidden.php', '<?php');
        $this->writeFile('notes.txt', 'Not PHP');

        self::assertEqualsCanonicalizing([$first, $hidden], $this->paths(new Finder([$this->directory])));
    }

    public function testExcludesDirectoriesWithTheDocumentedPrefix(): void
    {
        $included = $this->writeFile('input/sample.php', '<?php');
        $this->writeFile('input/excluded/untouched.php', '<?php');

        self::assertSame([$included], $this->paths(new Finder([$this->directory . '/input', '!excluded'])));
    }

    public function testAcceptsExplicitFilesAndSkipsMissingPaths(): void
    {
        $file = $this->writeFile('file with spaces.php', '<?php');

        self::assertSame([$file], $this->paths(new Finder([$file, $this->directory . '/missing'])));
    }

    public function testHonorsGitignore(): void
    {
        $this->writeFile('.git/HEAD', 'ref: refs/heads/main');
        $this->writeFile('.gitignore', "ignored.php\n");
        $included = $this->writeFile('included.php', '<?php');
        $this->writeFile('ignored.php', '<?php');

        self::assertSame([$included], $this->paths(new Finder([$this->directory])));
    }

    private function paths(Finder $finder): array
    {
        $paths = [];
        foreach ($finder as $file) {
            $paths[] = $file->getPathname();
        }

        return $paths;
    }
}
