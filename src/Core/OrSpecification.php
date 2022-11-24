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
 * @extends AbstractSpecification<C>
 */
final class OrSpecification extends AbstractSpecification
{
    /**
     * @param SpecificationInterface<C> $left
     * @param SpecificationInterface<C> $right
     */
    public function __construct(
        private readonly SpecificationInterface $left,
        private readonly SpecificationInterface $right
    ) {
    }

    /**
     * @param C $candidate
     * @return boolean
     */
    public function isSatisfiedBy($candidate): bool
    {
        return $this->left->isSatisfiedBy($candidate) || $this->right->isSatisfiedBy($candidate);
    }
}
