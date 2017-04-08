<?php

namespace smtech\StMarksSearch\Canvas;

use Exception;
use smtech\CanvasPest\CanvasPest;
use smtech\StMarksSearch\AbstractSearchDomain;

/**
 * Parent class for all Canvas search domains
 *
 * @author Seth Battis <SethBattis@stmarksschool.org>
 */
abstract class AbstractCanvasSearchDomain extends AbstractSearchDomain
{
    /**
     * API access object
     * @var CanvasPest
     */
    protected $api;

    /**
     * Canvas ID or SIS ID of the Canvas object
     * @var string|integer
     */
    protected $id;

    /**
     * Construct a CanvasSearchDomain from `$params`, requires `id` param, will
     * extract `url` param from `$api` if necessary.
     *
     * @inheritdoc
     *
     * @param CanvasPest $api
     * @param mixed[] $params
     */
    public function __construct(CanvasPest $api, $params)
    {
        /* API access may be required to process $params */
        $this->setApi($api);

        if (empty($params['url'])) {
            $params['url'] = preg_replace('%^(.*)/api/v\d+$%', '$1', $this->getApi()->base_url);
        }

        parent::__construct($params);

        assert(isset($params['id']), new Exception('`id` parameter required'));
        $this->setId($params['id']);

        $this->localizeUrl();
    }

    /**
     * Update the `$api` field
     *
     * @used-by AbstractCanvasSearchDomain::__construct()
     * @param CanvasPest $api
     */
    protected function setApi(CanvasPest $api)
    {
        assert($api !== null, new Exception('Initialized CanvasPest object required'));
        $this->api = $api;
    }

    /**
     * Get the Canvas API field
     *
     * @return CanvasPest
     */
    protected function getApi()
    {
        return $this->api;
    }

    /**
     * Update the ID of the Canvas object
     *
     * @used-by AbstractCanvasSearchDomain::__construct()
     * @param string|integer $id Canvas ID or SIS ID formatted as `sis_*_id:*`
     */
    protected function setId($id)
    {
        assert(
            is_numeric($id) ||
                preg_match('/^sis_[a-z]+_id:\S+$/i', $id),
            new Exception('ID must be a Canvas ID or SIS ID, received:' . PHP_EOL . print_r($id, true))
        );
        $this->id = $id;
    }

    /**
     * Get the Canvas object ID
     *
     * @return string|integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Localize the object URL field within the Canvas instance

     * @return void
     */
    abstract protected function localizeUrl();
}