<?php
/** PagesSearch class */

namespace smtech\StMarksSearch\Canvas\Courses\Pages;

use smtech\CanvasPest\CanvasArray;
use smtech\CanvasPest\CanvasObject;
use smtech\StMarksSearch\Relevance;
use smtech\StMarksSearch\SearchResult;
use smtech\StMarksSearch\SearchSource;
use smtech\StMarksSearch\Canvas\Courses\AbstractCourseSearchDomain;

/**
 * Search a Canvas course's wiki pages
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
class PagesSearch extends AbstractCourseSearchDomain
{
    /**
     * Search for `$query` among the wiki pages
     *
     * @param string $query
     * @return SearchResult[] Ordered by descending relevance
     */
    public function search($query)
    {
        /* Canvas doesn't accept search queries shorter than 3 characters */
        if (strlen(trim($query)) < 3) {
            return [];
        }

        $source = new SearchSource($this);
        $results = [];

        $response = $this->getApi()->get(
            'courses/' . $this->getId() . '/pages',
            [
                'search_term' => $query,
                'sort' => 'updated_at',
                'published' => true
            ]
        );
        if (is_a($response, CanvasArray::class)) {
            foreach ($response as $page) {
                $results[] = new SearchResult([
                    'url' => $this->getUrl() . "/pages/{$page['url']}",
                    'relevance' => $this->relevance($page, $query),
                    'title' => $page['title'],
                    'source' => $source
                ]);
            }
        }

        SearchResult::sort($results);
        return $results;
    }

    /**
     * Calculate the relevance of a particular page
     *
     * @param CanvasObject $page
     * @param string $query
     * @return Relevance
     */
    protected function relevance(CanvasObject $page, $query)
    {
        $relevance = new Relevance();

        $relevance->add(Relevance::stringProportion($page['title'], $query), 'title match');

        return $relevance;
    }
}
