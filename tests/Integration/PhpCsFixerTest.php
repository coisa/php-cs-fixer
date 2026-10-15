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

use CoiSA\PhpCsFixer\Config;
use CoiSA\PhpCsFixer\Finder;
use CoiSA\PhpCsFixer\PhpCsFixer;
use CoiSA\PhpCsFixer\Tests\FilesystemTestCase;
use PHPUnit\Framework\Attributes\CoversClass;

/**
 * @internal
 */
#[CoversClass(PhpCsFixer::class)]
final class PhpCsFixerTest extends FilesystemTestCase
{
    public function testCreatesAConfigurationWithTheRequestedFilesHeaderAndOverrides(): void
    {
        $file = $this->writeFile('sample.php', '<?php');
        $config = PhpCsFixer::create([$file], '/** Factory header */', ['concat_space' => false]);

        self::assertInstanceOf(Config::class, $config);
        self::assertInstanceOf(Finder::class, $config->getFinder());
        self::assertSame([$file], array_map(
            static fn(\SplFileInfo $found): string => $found->getPathname(),
            iterator_to_array($config->getFinder(), false),
        ));
        self::assertSame('Factory header', $config->getCustomRuleSets()[0]->getRules()['header_comment']['header']);
        self::assertFalse($config->getRules()['concat_space']);
    }
}
