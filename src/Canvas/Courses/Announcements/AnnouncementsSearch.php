<?php
/** AnnouncementsSearch class */

namespace smtech\StMarksSearch\Canvas\Courses\Announcements;

use smtech\CanvasPest\CanvasArray;
use smtech\CanvasPest\CanvasObject;
use smtech\StMarksSearch\Relevance;
use smtech\StMarksSearch\SearchResult;
use smtech\StMarksSearch\SearchSource;
use smtech\StMarksSearch\Canvas\Courses\AbstractCourseSearchDomain;

/**
 * Search a Canvas course's announcements
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
class AnnouncementsSearch extends AbstractCourseSearchDomain
{
    /**
     * Search for `$query` among the announcements
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
            'courses/' . $this->getId() . '/discussion_topics',
            [
                'search_term' => $query,
                'only_announcements' => true
            ]
        );

        if (is_a($response, CanvasArray::class)) {
            foreach ($response as $announcement) {
                $results[] = new SearchResult([
                    'url' => $announcement['html_url'],
                    'relevance' => $this->relevance($announcement, $query),
                    'title' => $announcement['title'],
                    'description' => (empty($announcement['message']) ? '' : substr(str_replace(PHP_EOL, ' ', strip_tags($announcement['message'])), 0, 255) . '&hellip;'),
                    'source' => $source
                ]);
            }
        }

        SearchResult::sort($results);
        return $results;
    }

    /**
     * Calculate the relevance of a particular announcement
     *
     * @param CanvasObject $announcement
     * @param string $query
     * @return Relevance
     */
    protected function relevance($announcement, $query)
    {
        $relevance = new Relevance();

        $relevance->add(Relevance::stringProportion($announcement['title'], $query), 'title match');

        if (($count = substr_count(strtolower($announcement['message']), strtolower($query))) > 0) {
            $relevance->add($count * 0.01, "$count occurrences in body");
        }

        return $relevance;
    }
}
