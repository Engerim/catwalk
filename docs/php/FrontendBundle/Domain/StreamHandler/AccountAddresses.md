#  AccountAddresses

**Fully Qualified**: [`\Frontastic\Catwalk\FrontendBundle\Domain\StreamHandler\AccountAddresses`](../../../../../src/php/FrontendBundle/Domain/StreamHandler/AccountAddresses.php)

**Extends**: [`StreamHandler`](../StreamHandler.md)

## Methods

* [__construct()](#__construct)
* [getType()](#gettype)
* [handleAsync()](#handleasync)

### __construct()

```php
public function __construct(
    \Frontastic\Common\AccountApiBundle\Domain\AccountApi $accountApi
): mixed
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$accountApi`|`\Frontastic\Common\AccountApiBundle\Domain\AccountApi`||

Return Value: `mixed`

### getType()

```php
public function getType(): string
```

Return Value: `string`

### handleAsync()

```php
public function handleAsync(
    Stream $stream,
    Context $context,
    array $parameters = []
): \GuzzleHttp\Promise\PromiseInterface
```

Argument|Type|Default|Description
--------|----|-------|-----------
`$stream`|[`Stream`](../Stream.md)||
`$context`|[`Context`](../../../ApiCoreBundle/Domain/Context.md)||
`$parameters`|`array`|`[]`|

Return Value: `\GuzzleHttp\Promise\PromiseInterface`

Generated with [Frontastic API Docs](https://github.com/FrontasticGmbH/apidocs).
