<?php

namespace ChinLeung\Nuvei\Http;

use Illuminate\Http\Client\Response as ClientResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use SimpleXMLElement;

class Response
{
    /**
     * The response of the http client.
     *
     * @var \Illuminate\Http\Client\Response
     */
    protected $response;

    /**
     * The xml response.
     *
     * @var \SimpleXMLElement
     */
    protected $xml;

    /**
     * Create a new instance of the response.
     *
     * @param  \Illuminate\Http\Client\Response  $response
     */
    public function __construct(ClientResponse $response)
    {
        $this->response = $response;

        if (config('nuvei.debug.log')) {
            Log::debug(sprintf('Nuvei Response :: [%s]', $response->body()));
        }

        $this->xml = simplexml_load_string($response->body());

        $this->throwException();
    }

    /**
     * Retrieve a value from the xml.
     *
     * @param  string  $key
     * @return string
     */
    public function get(string $key): ?string
    {
        return (string) data_get($this->xml(), $key) ?: null;
    }

    /**
     * Retrieve the class of the exception based on the error name.
     *
     * @return string
     */
    public function getExceptionClass(): string
    {
        $class = Str::of($this->xml->ERRORSTRING)
            ->studly()
            ->prepend(__NAMESPACE__.'\\')
            ->replaceFirst('Http\\', 'Exceptions\\')
            ->append('Exception');

        if (! class_exists((string) $class)) {
            return Str::of($class)->beforeLast('\\')->append('\\Exception');
        }

        return $class;
    }

    /**
     * Throw an exception if there has been an error in the request.
     *
     * @return void
     *
     * @throws  \ChinLeung\Nuvei\Exceptions\InvalidRequestFormatException
     */
    protected function throwException(): void
    {
        if ($this->xml->ERRORSTRING->count() === 0) {
            return;
        }

        tap($this->getExceptionClass(), function (string $exception) {
            throw new $exception(
                $this->xml->ERRORSTRING,
                (int) $this->xml->ERRORCODE
            );
        });
    }

    /**
     * Retrieve the xml response.
     *
     * @return \SimpleXMLElement
     */
    public function xml(): SimpleXMLElement
    {
        return $this->xml;
    }
}
