<?php
/** PagesSearch class */

namespace smtech\StMarksSearch\WordPress\Pages;

use smtech\StMarksSearch\Relevance;
use smtech\StMarksSearch\SearchResult;
use smtech\StMarksSearch\SearchSource;
use smtech\StMarksSearch\WordPress\AbstractWordPressSearchDomain;

/**
 * Search a WordPress blog's pages
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
class PagesSearch extends AbstractWordPressSearchDomain
{
    /**
     * Search the blog's pages for `$query`
     *
     * Dependent on the WordPress page-listing search engine
     *
     * @param string $query
     * @return SearchResult[] Ordered by descending relevance
     */
    public function search($query)
    {
        $this->source = new SearchSource($this);
        $results = $this->processResponse(
            $this->getApi()->get('/pages', ['search' => $query]),
            $query
        );

        SearchResult::sort($results);
        return $results;
    }

    /**
     * Convert each page into a SearchResult
     *
     * @param mixed[] $page
     * @param string $query
     * @return SearchResult
     */
    public function processItem($page, $query)
    {
        return new SearchResult([
            'url' => $page['link'],
            'relevance' => $this->relevance($page, $query),
            'title' => $page['title']['rendered'],
            'description' => preg_replace('@<p class="continue-reading-button">.*</p>@', '', $page['excerpt']['rendered']),
            'source' => $this->source
        ]);
    }

    /**
     * Calculate the relevance of a page to the `$query`
     *
     * @param mixed[] $page
     * @param string $query
     * @return Relevance
     */
    protected function relevance($page, $query)
    {
        $relevance = new Relevance();

        $relevance->add(Relevance::stringProportion($page['title']['rendered'], $query), 'title match');

        if (($count = substr_count(strtolower($page['content']['rendered']), strtolower($query))) > 0) {
            $relevance->add($count * 0.01, "$count occurrences in body");
        }

        return $relevance;
    }
}
