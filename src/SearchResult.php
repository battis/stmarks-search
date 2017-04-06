<?php

namespace smtech\StMarksSearch;

/**
 * An object representing a single search result
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
class SearchResult
{
    /**
     * URL of the search result
     * @var string
     */
    protected $url;

    /**
     * Description of result's relevance
     * @var Relevance
     */
    protected $relevance;

    /**
     * Human-readable title
     * @var string
     */
    protected $title;

    /**
     * Human-readable description
     *
     * Ideally 20-100 words, may be HTML-formatted (although links should be
     * stripped out).
     * @var string
     */
    protected $description;

    /**
     * Simplified description of search domain source of the result
     * @var SearchSource
     */
    protected $source;

    /**
     * Construct a SearchResult
     * @param string $url
     * @param Relevance $relevance
     * @param string $title
     * @param string $description
     * @param SearchSource $source
     */
    public function __construct($url, Relevance $relevance, $title, $description, SearchSource $source)
    {
        /*
         * TODO validate/clean parameters
         */
        $this->relevance = $relevance;
        $this->title = $title;
        $this->description = $description;
        $this->url = $url;
        $this->source = $source;
    }

    /**
     * Summary of relevance information of the result
     *
     * @return Relevance
     */
    public function getRelevance()
    {
        return $this->relevance;
    }

    /**
     * Title of the search result
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Description of the search result
     *
     * Potentially HTML-formatted, ideally 20-100 words.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * URL of the search result
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Information about the source of the result
     *
     * @return SearchSource
     */
    public function getSource()
    {
        return $this->source;
    }
}
