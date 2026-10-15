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

use CoiSA\PhpCsFixer\HeaderExtractor;
use CoiSA\PhpCsFixer\PhpCsFixer;

$paths = [
    __FILE__,
    __DIR__,
];

$header = (new HeaderExtractor())->extractFromFile(__FILE__);

return PhpCsFixer::create($paths, $header);
