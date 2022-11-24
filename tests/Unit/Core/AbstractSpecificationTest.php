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

use PackageFactory\Specification\Core\AbstractSpecification;
use PackageFactory\Specification\Core\SpecificationInterface;
use PHPUnit\Framework\TestCase;

final class AbstractSpecificationTest extends TestCase
{
    /**
     * @param boolean $result
     * @return SpecificationInterface<mixed>
     */
    private function getSpecificationReturning(bool $result): SpecificationInterface
    {
        return new class($result) extends AbstractSpecification
        {
            public function __construct(private readonly bool $result)
            {
            }

            public function isSatisfiedBy($candidate): bool
            {
                return $this->result;
            }
        };
    }

    /**
     * @void
     * @return void
     */
    public function testAnd(): void
    {
        $this->assertTrue(
            $this->getSpecificationReturning(true)
                ->and($this->getSpecificationReturning(true))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertFalse(
            $this->getSpecificationReturning(true)
                ->and($this->getSpecificationReturning(false))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertFalse(
            $this->getSpecificationReturning(false)
                ->and($this->getSpecificationReturning(false))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertFalse(
            $this->getSpecificationReturning(false)
                ->and($this->getSpecificationReturning(true))
                ->isSatisfiedBy('AnyValue')
        );
    }

    /**
     * @void
     * @return void
     */
    public function testAndNot(): void
    {
        $this->assertFalse(
            $this->getSpecificationReturning(true)
                ->andNot($this->getSpecificationReturning(true))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertTrue(
            $this->getSpecificationReturning(true)
                ->andNot($this->getSpecificationReturning(false))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertFalse(
            $this->getSpecificationReturning(false)
                ->andNot($this->getSpecificationReturning(false))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertFalse(
            $this->getSpecificationReturning(false)
                ->andNot($this->getSpecificationReturning(true))
                ->isSatisfiedBy('AnyValue')
        );
    }

    /**
     * @void
     * @return void
     */
    public function testOr(): void
    {
        $this->assertTrue(
            $this->getSpecificationReturning(true)
                ->or($this->getSpecificationReturning(true))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertTrue(
            $this->getSpecificationReturning(true)
                ->or($this->getSpecificationReturning(false))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertFalse(
            $this->getSpecificationReturning(false)
                ->or($this->getSpecificationReturning(false))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertTrue(
            $this->getSpecificationReturning(false)
                ->or($this->getSpecificationReturning(true))
                ->isSatisfiedBy('AnyValue')
        );
    }

    /**
     * @void
     * @return void
     */
    public function testOrNot(): void
    {
        $this->assertTrue(
            $this->getSpecificationReturning(true)
                ->orNot($this->getSpecificationReturning(true))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertTrue(
            $this->getSpecificationReturning(true)
                ->orNot($this->getSpecificationReturning(false))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertTrue(
            $this->getSpecificationReturning(false)
                ->orNot($this->getSpecificationReturning(false))
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertFalse(
            $this->getSpecificationReturning(false)
                ->orNot($this->getSpecificationReturning(true))
                ->isSatisfiedBy('AnyValue')
        );
    }

    /**
     * @void
     * @return void
     */
    public function testNot(): void
    {
        $this->assertFalse(
            $this->getSpecificationReturning(true)
                ->not()
                ->isSatisfiedBy('AnyValue')
        );
        $this->assertTrue(
            $this->getSpecificationReturning(false)
                ->not()
                ->isSatisfiedBy('AnyValue')
        );
    }
}
