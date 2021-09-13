<?php

namespace ChinLeung\Nuvei\Concerns;

use ChinLeung\Nuvei\Http\Response;

trait RequiresResponse
{
    /**
     * The response from Nuvei's api.
     *
     * @var \ChinLeung\Nuvei\Http\Response
     */
    protected $response;

    /**
     * Create a new charge instance.
     *
     * @param  \ChinLeung\Nuvei\Http\Response  $response
     */
    public function __construct(Response $response)
    {
        $this->response = $response;
    }
}
