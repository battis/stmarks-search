<?php
/** LibGuidesSearchDomainFactory class */

namespace smtech\StMarksSearch\LibApps\LibGuides;

use smtech\StMarksSearch\AbstractSearchDomainFactory;

/**
 * A factory to generate an array of all relevant search domains for a
 * particular LibGuides entry
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
class LibGuidesSearchDomainFactory extends AbstractSearchDomainFactory
{
    /**
     * Construct an array of search domains relating to LibGuides
     * @param array $params
     * @return LibGuidesSearch[]
     */
    public static function constructSearchDomains($params)
    {
        return [new LibGuidesSearch($params)];
    }
}
