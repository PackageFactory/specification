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

namespace PackageFactory\Specification\Core;

/**
 * @template C
 */
interface SpecificationInterface
{
    /**
     * @param C $candidate
     * @return boolean
     */
    public function isSatisfiedBy($candidate): bool;

    /**
     * @param SpecificationInterface<C> $other
     * @return SpecificationInterface<C>
     */
    public function and(SpecificationInterface $other): SpecificationInterface;

    /**
     * @param SpecificationInterface<C> $other
     * @return SpecificationInterface<C>
     */
    public function andNot(SpecificationInterface $other): SpecificationInterface;

    /**
     * @param SpecificationInterface<C> $other
     * @return SpecificationInterface<C>
     */
    public function or(SpecificationInterface $other): SpecificationInterface;

    /**
     * @param SpecificationInterface<C> $other
     * @return SpecificationInterface<C>
     */
    public function orNot(SpecificationInterface $other): SpecificationInterface;

    /**
     * @return SpecificationInterface<C>
     */
    public function not(): SpecificationInterface;
}
