<?php

namespace ChinLeung\Nuvei\Builders;

use ChinLeung\Nuvei\Concerns\Makeable;
use Illuminate\Support\Str;

abstract class Builder
{
    use Makeable;

    /**
     * The options of the builder.
     *
     * @var array
     */
    protected $options = [];

    /**
     * Generate a unique id.
     *
     * @param  int  $length
     * @return string
     */
    protected function generateId(int $length): string
    {
        $id = time().uniqid();

        return strtoupper(Str::random($length - strlen($id)).$id);
    }

    /**
     * Update the builder's options.
     *
     * @param  array  $options
     * @return static
     */
    public function withOptions(array $options): self
    {
        $this->options = array_merge($this->options, $options);

        return $this;
    }

    /**
     * Retrieve the payload for the request.
     *
     * @return array
     */
    abstract protected function getRequestPayload(): array;
}
