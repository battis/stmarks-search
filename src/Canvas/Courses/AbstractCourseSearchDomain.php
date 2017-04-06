<?php

namespace smtech\StMarksSearch\Canvas\Courses;

use smtech\CanvasPest\CanvasPest;

use smtech\StMarksSearch\AbstractSearchDomain;

/**
 * Parent object for Canvas course searches
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
abstract class AbstractCourseSearchDomain extends AbstractSearchDomain
{
    /**
     * API access object
     * @var CanvasPest
     */
    protected $api;

    /**
     * Canvas Course ID
     * @var integer
     */
    protected $id;

    /**
     * Construct a CourseSearch: `$params` must contain an `id` field, which is
     * either a numeric Canvas course ID or an SIS ID formatted as
     * `'sis_course_id:foo-bar'`
     *
     * @inheritdoc
     *
     * @param CanvasPest $api
     * @param mixed[] $params
     */
    public function __construct(CanvasPest $api, $params)
    {
        parent::__construct($params);

        $this->api = $api;
        $this->id = $params['id'];
    }
}
