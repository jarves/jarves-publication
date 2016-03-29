<?php

namespace Jarves\Publication\Controller\Plugin;

use Propel\Runtime\Map\TableMap;
use Jarves\Publication\Model\NewsCategoryQuery;
use Jarves\Publication\Model\NewsQuery;
use Jarves\PluginController;

class News extends PluginController
{
    /**
     * News listing.
     *
     * @param  array $options
     * @param  int $page
     * @param  string $slug
     * @return string
     */
    public function listing(array $options, $page = 1, $slug = null)
    {
        $this->setOptions(
            $options,
            [
                'itemsPerPage' => 10,
                'maxPages' => 10,
                'detailPage' => false,
                'detailView' => true,
                'enableRss' => false,
                'template' => null,
                'detailOptions' => []
            ]
        );

        if (null === $options['template']) {
            return null;
        }

        if ($slug) {
            if ($options['detailView']) {
                return $this->detail((array)$options['detailOptions'], $slug);
            }
        }

        $sum = md5(serialize($options));
        $page = (int)$page ? : 1;
        $cacheKey = 'Publication/News/list/' . $page . '.' . $sum;
        $view = 'JarvesPublicationBundle:News:List/' . $options['template'];

        return $this->renderFullCached(
            $cacheKey,
            $view,
            function () use ($page, $options) {

                $query = NewsQuery::create()
                    ->joinCategory()
                    ->orderByNewsDate();

                /** @var $paginate \Propel\Runtime\Util\PropelModelPager */
                $paginate = $query->paginate($page, $options['itemsPerPage']);

                if ($page > $paginate->getLastPage()) {
                    return null;
                    //when we return here NULL the `renderFullCached` function returns NULL as well,
                    //and this means that we say to the HTTPKernel 'we (this route) are not responsible for the current request'.
                    //the HTTPKernel removes this current route then from the RouteCollection
                    //and starts a new SUB_REQUEST. Maybe another route is here that handles the current request.
                }

                $items = $paginate
                    ->getResults();

                return array(
                    'items' => $items,
                    'maxPages' => $paginate->getLastPage(),
                    'page' => $paginate->getPage(),
                    'options' => $options
                );
            }
        );
    }

    /**
     * News detail.
     *
     * @param array $options
     * @param string $slug
     *
     * @return string
     */
    public function detail(array $options, $slug)
    {
        $this->setOptions(
            $options,
            [
                'template' => false,
                'detailPage' => false
            ]
        );

        $cacheKey = 'Publication/Models/News/detail/' . $slug;
        $view = 'JarvesPublicationBundle:News:Detail/' . ($options['template'] ? : 'default.html.twig');

        return $this->renderFullCached(
            $cacheKey,
            $view,
            function () use ($slug, $options) {
                $item = NewsQuery::create()
                    ->joinCategory()
                    ->leftJoinContent()
                    ->findOneBySlug($slug);

                if (!$item) {
                    return null;
                }

                return array(
                    'item' => $item,
                    'options' => $options
                );

            }
        );
    }

    /**
     * @param array $options
     * @return string
     */
    public function categoryList(array $options)
    {
        $cacheKey = 'Publication/Models/News/categoryList';
        $view = 'JarvesPublicationBundle:News:CategoryList/' . ($options['template'] ? : 'default.html.twig');

        $this->setOptions(
            $options,
            [
                'listPage' => false
            ]
        );

        return $this->renderFullCached(
            $cacheKey,
            $view,
            function () use ($options) {

                $categories = NewsCategoryQuery::create()
                    ->joinNews()
                    ->withColumn('COUNT(News.Id)', 'newsCount')
                    ->groupById()
                    ->find()
                    ->toArray(null, null, TableMap::TYPE_CAMELNAME);

                return array(
                    'categories' => $categories,
                    'options' => $options
                );

            }
        );
    }
}
