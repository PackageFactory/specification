<?php

/**
 * PackageFactory.Specification - Implementation of the Specification pattern for PHP
 *   Copyright (C) 2022 Contributors of PackageFactory.Specification
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

declare(strict_types=1);

namespace PackageFactory\Specification\Tests\Unit\Core;

use PackageFactory\Specification\Core\NotSpecification;
use PackageFactory\Specification\Core\SpecificationInterface;
use PackageFactory\Specification\Specification\Boolean\FalseSpecification;
use PackageFactory\Specification\Specification\Boolean\TrueSpecification;
use PHPUnit\Framework\TestCase;

final class NotSpecificationTest extends TestCase
{
    /**
     * @return array<mixed>
     */
    public function specificationExamples(): array
    {
        return [
            '!true' => [
                new TrueSpecification(),
                false
            ],
            '!false' => [
                new FalseSpecification(),
                true
            ],
        ];
    }

    /**
     * @dataProvider specificationExamples
     * @test
     * @param SpecificationInterface<mixed> $wrapped
     * @param boolean $expectedResult
     * @return void
     */
    public function testAndSpecification(SpecificationInterface $wrapped, bool $expectedResult): void
    {
        $notSpecification = new NotSpecification($wrapped);

        $this->assertEquals($expectedResult, $notSpecification->isSatisfiedBy('AnyValue'));
    }
}
