<?php

namespace Frontastic\Catwalk\FrontendBundle\Controller;

use Frontastic\Catwalk\ApiCoreBundle\Domain\Context;
use Frontastic\Catwalk\FrontendBundle\Domain\MasterService;
use Frontastic\Catwalk\FrontendBundle\Domain\NodeService;
use Frontastic\Catwalk\FrontendBundle\Domain\PageMatcher\PageMatcherContext;
use Frontastic\Catwalk\FrontendBundle\Domain\PageService;
use Frontastic\Catwalk\FrontendBundle\Domain\ViewDataProvider;
use Frontastic\Catwalk\TrackingBundle\Domain\TrackingService;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SearchController extends Controller
{
    public function searchAction(Request $request, Context $context, string $phrase): array
    {
        /** @var MasterService $pageMatcherService */
        $pageMatcherService = $this->get(MasterService::class);
        /** @var NodeService $nodeService */
        $nodeService = $this->get(NodeService::class);
        /** @var ViewDataProvider $dataProvider */
        $dataProvider = $this->get(ViewDataProvider::class);
        /** @var PageService $pageService */
        $pageService = $this->get(PageService::class);
        /** @var MasterService $masterService */
        $masterService = $this->get(MasterService::class);

        $node = $nodeService->get($pageMatcherService->matchNodeId(new PageMatcherContext(['search' => $phrase])));
        $node->nodeType = 'search';
        $node->streams = $pageMatcherService->completeDefaultQuery($node->streams, 'search', $phrase);

        $parameters = array_merge_recursive(
            $request->query->get('s', []),
            ['__master' => ['query' => $phrase]]
        );

        $page = $pageService->fetchForNode($node, $context);

        $masterService->completeTasticStreamConfigurationWithMasterDefault($page, 'search');

        $this->get(TrackingService::class)->trackPageView($context, $node->nodeType);

        return [
            'node' => $node,
            'page' => $page,
            'data' => $dataProvider->fetchDataFor($node, $context, $parameters, $page),
        ];
    }
}
