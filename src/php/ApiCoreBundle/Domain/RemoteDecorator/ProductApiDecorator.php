<?php

namespace Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator;

use Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecoratorFactory;
use Frontastic\Catwalk\ApiCoreBundle\Domain\RemoteDecorator\Formatter;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\LifecycleEventDecorator\BaseImplementation;
use Frontastic\Common\HttpClient;

use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\Product;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\CategoryQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Query\ProductTypeQuery;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi\Result;
use Frontastic\Common\ProductApiBundle\Domain\ProductType;

class ProductApiDecorator extends BaseImplementation
{
    private $httpClient;

    private $remoteCalls = [];

    private $formatter = [];

    public function __construct(RemoteDecoratorFactory $factory, HttpClient $httpClient, array $formatter = [])
    {
        $this->remoteCalls = $factory->getProductApiCalls();
        $this->httpClient = $httpClient;

        $this->formatter = array_merge(
            [
                'json' => new Formatter\Json(),
                // 'flatbuf' => new Formatter\Flatbuf(),
            ],
            $formatter
        );
    }

    public function beforeGetCategories(ProductApi $productApi, CategoryQuery $query): void
    {
        $this->runRemoteDecorators(__FUNCTION__, array_slice(func_get_args(), 1));
    }

    public function afterGetCategories(ProductApi $productApi, array $categories): ?array
    {
        return $this->runRemoteDecorators(__FUNCTION__, $categories);
    }

    public function beforeGetProductTypes(ProductApi $productApi, ProductTypeQuery $query): void
    {
        $this->runRemoteDecorators(__FUNCTION__, array_slice(func_get_args(), 1));
    }

    public function afterGetProductTypes(ProductApi $productApi, array $productTypes): ?array
    {
        return $this->runRemoteDecorators(__FUNCTION__, $productTypes);
    }

    public function beforeGetProduct(
        ProductApi $productApi,
        ProductQuery $query,
        string $mode = ProductApi::QUERY_SYNC
    ): void {
        $this->runRemoteDecorators(__FUNCTION__, array_slice(func_get_args(), 1));
    }

    public function afterGetProduct(ProductApi $productApi, ?Product $product): ?Product
    {
        return $this->runRemoteDecorators(__FUNCTION__, $product);
    }

    public function beforeQuery(
        ProductApi $productApi,
        ProductQuery $query,
        string $mode = ProductApi::QUERY_SYNC
    ): void {
        $this->runRemoteDecorators(__FUNCTION__, array_slice(func_get_args(), 1));
    }

    public function afterQuery(ProductApi $productApi, ?Result $result): ?Result
    {
        return $this->runRemoteDecorators(__FUNCTION__, $result);
    }

    private function runRemoteDecorators(string $method, $arguments)
    {
        if (!isset($this->remoteCalls[$method]) || !count($this->remoteCalls[$method])) {
            return;
        }

        foreach ($this->remoteCalls[$method] as $endpoint) {
            try {
                if (!isset($this->formatter[$endpoint->format])) {
                    throw new \OutOfBoundsException(
                        'Unknown formatter ' . $endpoint->format
                    );
                }

                $response = $this->httpClient->request(
                    $endpoint->method,
                    $endpoint->url,
                    $this->formatter[$endpoint->format]->encode($arguments),
                    $endpoint->headers,
                    new HttpClient\Options(['timeout' => $endpoint->timeout])
                );

                if ($response->status >= 400) {
                    if ($endpoint->optional) {
                        continue;
                    } else {
                        throw new \RunTimeException(
                            'Endpoint ' . $endpoint->url . ' failed: ' . $response->status . ' ' . $response->body
                        );
                    }
                }

                if ($decoded = $this->formatter[$endpoint->format]->decode($response->body)) {
                    $arguments = $decoded;
                }
            } catch (\Throwable $e) {
                if ($endpoint->optional) {
                    debug('Stream Decorator Error: ' . $e->getMessage());
                    continue;
                }

                throw $e;
            }
        }

        return $arguments;
    }
}
