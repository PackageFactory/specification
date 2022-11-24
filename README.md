# PackageFactory.Specification

> Implementation of the Specification pattern for PHP

The specification pattern is a way to express business rules in a domain model using boolean logic. It is described in detail in the following document: https://www.martinfowler.com/apsupp/spec.pdf

## Installation

```
composer require --dev packagefactory/specification
```

## Usage

### Writing a Specification

Let's presume the following (very simplified) problem: You've got an application with a simple user registration workflow. Users can register freely, but have to verify their E-Mail address. If a user didn't verify their E-Mail address for a period of time, they shall be reminded (via E-Mail) that verification is still due.

How can this business rule be codified using the Specification pattern?

First, let's write a specification that checks if a given user has a verified E-Mail address:

```php
use PackageFactory\Specification\Core\AbstractSpecification;
use Vendor\Project\Domain\User;

/**
 * The `@extends` annotation makes sure that static analysis tools like 
 * phpstan understand that this specification handles `User`-objects
 * only:
 * 
 * @extends AbstractSpecification<User>
 */
final class HasVerifiedEmailAddressSpecification extends AbstractSpecification
{
    public function isSatisfiedBy($user): bool
    {
        // In lieu of generics in PHP it is recommended to add a 
        // zero-cost assertion to ensure the type of the given value:
        assert($user instanceof User);

        return $user->emailAddress->isVerified;
    }
}
```

Then, let's write a specification that checks if a given user has been registered before a specific reference date:

```php
use PackageFactory\Specification\Core\AbstractSpecification;
use Vendor\Project\Domain\User;

/**
 * @extends AbstractSpecification<User>
 */
final class HasBeenRegisteredBefore extends AbstractSpecification
{
    public function __construct(
        private readonly \DateTimeImmutable $referenceDate
    ) {
    }

    public function isSatisfiedBy($user): bool
    {
        assert($user instanceof User);

        return $user->registrationDate->getTimestamp() < $this->referenceDate->getTimestamp();
    }
}
```

We can now use the Specification API to combine both specifications and express our business rule:

```php
// $twoWeeksAgo is a calculated \DateTimeImmutable
$needsReminderSpecification = (new HasBeenRegisteredBefore($twoWeeksAgo))
    ->andNot(new HasVerifiedEmailAddressSpecification());

$usersThatNeedReminder = $userRepository->findBySpecification($needsReminderSpecification);

foreach ($usersThatNeedReminder as $userThatNeedsReminder) {
    $notificationService->sendReminderTo($userThatNeedsReminder);
}
```

### API

Each specification must implement `PackageFactory\Specification\Core\SpecificationInterface`. Usually, a custom specification should extend `PackageFactory\Specification\Core\AbstractSpecification`, which implements all methods of the `SpecificationInterface` except for `isSatisfiedBy`.

The `SpecificationInterface` covers the following methods:

> **Note on Generics:** PHP does not have built-in Generics. However, there's static analysis tools like [phpstan](https://phpstan.org/) that do understand them. The `SpecificationInterface` comes with an annotation that allows you to specify the type of `$candidate` your specification is supposed to cover.
> 
> Your custom specification implementation should therefore name a concrete `$candidate` type like this:
> ```php
> /**
>  * @extends AbstractSpecification<MyClass>
>  */
> final class MyCustomSpecification extends AbstractSpecification
> {
>     /**
>      * @param MyClass $candidate
>      * @return boolean
>      */
>     public function isSatisfiedBy($candidate): bool
>     {
>         // ...
>     }
> }
> ```

#### `isSatisfiedBy`

```php
/**
 * @param C $candidate
 * @return boolean
 */
public function isSatisfiedBy($candidate): bool;
```

This method checks the given `$candidate` and returns `true` if it satisfies the specification and `false` if it doesn't.

In lieu of generics in PHP it is recommended to add a zero-cost assertion at the top of the implementation body to ensure the type of `$candidate`:

```php
/**
 * @param MyClass $candidate
 * @return boolean
 */
public function isSatisfiedBy($candidate): bool;
{
    assert($candidate instanceof MyClass);

    // ...
}
```

For more on zero-cost assertions see: https://www.php.net/manual/en/function.assert.php

#### `and`

```php
/**
 * @param SpecificationInterface<C> $other
 * @return SpecificationInterface<C>
 */
public function and(SpecificationInterface $other): SpecificationInterface;
```

The result of this method is a new specification that will be satisfied by a `$candidate` that satisfies both the calling specification and `$other`.

#### `andNot`

```php
/**
 * @param SpecificationInterface<C> $other
 * @return SpecificationInterface<C>
 */
public function andNot(SpecificationInterface $other): SpecificationInterface;
```

The result of this method is a new specification that will be satisfied by a `$candidate` that satisfies the calling specification and does not satisfy `$other`.

#### `or`

```php
/**
 * @param SpecificationInterface<C> $other
 * @return SpecificationInterface<C>
 */
public function or(SpecificationInterface $other): SpecificationInterface;
```

The result of this method is a new specification that will be satisfied by a `$candidate` that satisfies either the calling specification or `$other` (or both).

#### `orNot`

```php
/**
 * @param SpecificationInterface<C> $other
 * @return SpecificationInterface<C>
 */
public function orNot(SpecificationInterface $other): SpecificationInterface;
```

The result of this method is a new specification that will be satisfied by a `$candidate` that either satisfies the calling specification or does not satisfy `$other` (or both).

#### `not`

```php
/**
 * @return SpecificationInterface<C>
 */
public function not(): SpecificationInterface;
```

This method negates the calling specification. That means: the result is a specification that will be satisfied by a `$candidate` that does not satisfy the calling specification.

## Contribution

We will gladly accept contributions. Please send us pull requests.

## License

see [LICENSE](./LICENSE)
