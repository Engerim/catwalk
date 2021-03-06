<?php

namespace Frontastic\Catwalk\FrontendBundle\RulerZ\Operator;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\ApiCoreBundle\Domain\ContextService;
use Frontastic\Common\ProductApiBundle\Domain\Category;
use Frontastic\Common\ProductApiBundle\Domain\ProductApi;

/**
 * @SuppressWarnings(PHPMD.CountInLoopExpression)
 */
class CategoriesContain
{
    const QUERY_CATEGORIES_LIMIT = 500;

    /**
     * @var ProductApi
     */
    private $productApi;

    /**
     * @var ContextService
     */
    private $contextService;

    public function __construct(ProductApi $productApi, ContextService $contextService)
    {
        $this->productApi = $productApi;
        $this->contextService = $contextService;
    }

    public function __invoke(array $categoryIds, string $ancestorId)
    {
        if (in_array($ancestorId, $categoryIds)) {
            return true;
        }

        $categoryIdsToCheck = $categoryIds;

        $offset = 0;
        do {
            $fetchedCategories = $this->fetchCategories($offset);
            $offset += self::QUERY_CATEGORIES_LIMIT;

            foreach ($fetchedCategories as $category) {
                if (!in_array($category->categoryId, $categoryIdsToCheck)) {
                    continue;
                }

                if ($this->categoryHasAncestor($category, $ancestorId)) {
                    return true;
                }

                $categoryIdsToCheck = array_diff($categoryIdsToCheck, [$category->categoryId]);
            }
        } while (!empty($categoryIdsToCheck) && count($fetchedCategories) === self::QUERY_CATEGORIES_LIMIT);

        return false;
    }

    private function fetchCategories(int $offset)
    {
        return $this->productApi->getCategories(new ProductApi\Query\CategoryQuery([
            'locale' => $this->contextService->createContextFromRequest()->locale,
            'limit' => self::QUERY_CATEGORIES_LIMIT,
            'offset' => $offset,
        ]));
    }

    private function categoryHasAncestor(Category $category, string $ancestorId): bool
    {
        return preg_match('{/' . preg_quote($ancestorId) . '($|/)}', $category->path) === 1;
    }
}
