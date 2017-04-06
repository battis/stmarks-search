<?php

namespace smtech\StMarksSearch\Canvas\Courses;

use Exception;

use smtech\CanvasPest\CanvasPest;

use smtech\StMarksSearch\SearchEngine;
use smtech\StMarksSearch\Canvas\Courses\Pages\PagesSearch;

/**
 * Search a Canvas course
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
class CourseSearch extends SearchEngine
{
    /**
     * Construct a CourseSearch: may have a `pages` field to indicate that
     * course pages should be searched.
     *
     * @inheritdoc
     *
     * @param CanvasPest $api
     * @param mixed[] $params
     */
    public function __construct(CanvasPest $api, $params)
    {
        assert(!empty($params['id']), new Exception('Non-empty id parameter required'));
        $this->api = $api;
        $course = $this->api->get("/courses/{$params['id']}");
        $params['name'] = $course['name'];
        $params['url'] = str_replace('/api/v1', '', "{$this->api->base_url}/courses/{$course['id']}");
        $params['pages'] = !empty($params['pages']) && filter_var($params['pages'], FILTER_VALIDATE_BOOLEAN);

        parent::__construct($params);

        if ($params['pages']) {
            $this->addDomain(new PagesSearch($api, $params));
        }
    }
}
