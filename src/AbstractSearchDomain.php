<?php
/** AbstractSearchDomain class */

namespace smtech\StMarksSearch;

use Exception;

/**
 * Abstract class defining a search domain
 *
 * Includes a basic constructor and a reusable sorting method.
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 *
 * @method string getName()
 * @method setName(string $name)
 * @method string getUrl()
 * @method seturl(string $url)
 * @method string getIcon()
 * @method setIcon(string $iconUrl)
 */
abstract class AbstractSearchDomain extends ParameterArrayConstructor
{
    const NAME = 'name';
    const URL = 'url';
    const ICON = 'icon';

    /**
     * Human-readable name of the search domain
     *
     * Ideally, this is loaded dynamically from the domain via API -- and any
     * dynamic result will override any preset configuration.
     * @var string
     */
    protected $name;

    /**
     * URL of the search domain's home page
     *
     * Ideally, this is loaded or constructed dynamically via API -- and any
     * dynamic result will override any preset configuration.
     * @var string
     */
    protected $url;

    /**
     * URL of the search domain's icon
     *
     * This will be rendered 16x16 pixels when displayed.
     * @var string
     */
    protected $icon;

    /**
     * Construct a SearchDomain
     *
     * Parameters are passed to this constuctor as an associative array of values, including, at a minimum:
     *
     * ```
     * [
     *   'name' => 'A human-readable name for the domain',
     *   'url' => 'URL of the home page of the domain'
     * ]
     * ```
     *
     * Subclasses may (and will) add additional required parameters to that
     * list.
     *
     * @param mixed[] $params
     *
     * @throws Exception If `$params['url']` is not a valid URL or
     *         `$params['name']` is empty.
     */
    public function __construct($params)
    {
        $this->requireParameter($params, self::URL);
        $this->requireParameter($params, self::NAME);

        parent::__construct($params);
    }

    /**
     * Update the URL of the search domain
     *
     * @used-by AbstractSearchDomain::__construct()
     * @param string $url
     * @throws Exception If `$url` is empty or is not a valid URL
     */
    protected function setUrl($url)
    {
        if (empty($url) ||
            filter_var($url, FILTER_VALIDATE_URL) === false
        ) {
            throw new Exception("Valid url parameter required, received:" . PHP_EOL . print_r($url, true));
        }
        $this->url = $url;
    }

    /**
     * Set the name of the search domain
     *
     * @used-by AbstractSearchDomain::__construct()
     * @param string $name
     * @throws Exception If `$name` is empty
     */
    protected function setName($name)
    {
        if (empty($name)) {
            throw new Exception('Non-empty parameter required, received:' . PHP_EOL . print_r($name, true));
        }
        $this->name = $name;
    }

    /**
     * Set the icon URL for the search domain
     *
     *  Does nothing if `$url` is not a valid URL
     *
     * @used-by AbstractSearchDomain::__construct()
     * @param string $icon
     */
    protected function setIcon($icon)
    {
        if (filter_var($icon, FILTER_VALIDATE_URL)) {
            $this->icon = $icon;
        }
    }

    /**
     * Search within this domain
     *
     * @param string $query
     * @return SearchResult[] Ordered by descending relevance
     */
    abstract public function search($query);
}
