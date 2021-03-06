<?php
/** CourseSearchDomainFactory class */

namespace smtech\StMarksSearch\Canvas\Courses;

use smtech\StMarksSearch\AbstractSearchDomain;
use smtech\StMarksSearch\AbstractSearchDomainFactory;
use smtech\StMarksSearch\Canvas\Courses\AbstractCanvasSearchDomain;
use smtech\StMarksSearch\Canvas\Courses\Announcements\AnnouncementsSearch;
use smtech\StMarksSearch\Canvas\Courses\Pages\PagesSearch;

/**
 * Factory to generate search domains relevant to a particular Canvas Course
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
class CourseSearchDomainFactory extends AbstractSearchDomainFactory
{
    const ANNOUNCEMENTS = 'announcements';
    const PAGES = 'pages';

    /**
     * Construct course-related search domains
     *
     * @param array $params
     * @return AbstractCourseSearchDomain[]
     */
    public static function constructSearchDomains($params)
    {
        $domains = [];

        $consumedParams = [self::ANNOUNCEMENTS, self::PAGES];

        $params[self::PAGES] = static::forceBooleanParameter($params, self::PAGES);
        $params[self::ANNOUNCEMENTS] = static::forceBooleanParameter($params, self::ANNOUNCEMENTS);

        if ($params[self::PAGES]) {
            $domains[] = new PagesSearch(static::consumeParameters($params, $consumedParams));
        }

        if ($params[self::ANNOUNCEMENTS]) {
            $domains[] = new AnnouncementsSearch(static::consumeParameters($params, $consumedParams));
        }

        return $domains;
    }
}
