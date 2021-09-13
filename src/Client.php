<?php

namespace ChinLeung\Nuvei;

use ChinLeung\Nuvei\Http\Response;
use DOMDocument;
use DOMElement;
use Illuminate\Support\Arr;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;

class Client
{
    /**
     * The endpoint of Nuvei's api.
     *
     * @var string
     */
    protected $endpoint = 'https://payments.nuvei.com/merchant/xmlpayment';

    /**
     * The merchant id of the account.
     *
     * @var string
     */
    protected $id;

    /**
     * The terminal secret.
     *
     * @var string
     */
    protected $secret;

    /**
     * The terminal id.
     *
     * @var string
     */
    protected $terminal;

    /**
     * Create a new instance of the client.
     *
     * @param  string  $id
     * @param  string  $terminal
     * @param  string  $secret
     * @param  bool  $demo
     */
    public function __construct(string $id, string $terminal, string $secret, bool $demo = false)
    {
        $this->id = $id;
        $this->terminal = $terminal;
        $this->secret = $secret;

        if ($demo === true) {
            $this->demo();
        }
    }

    /**
     * Add a given data to the payload after a given index.
     *
     * @param  array  $payload
     * @param  array  $data
     * @param  string  $after
     * @return array
     */
    protected function addToPayload(array $payload, array $data, string $after): array
    {
        $keys = array_keys($payload);
        $index = array_search($after, $keys);

        if ($index === false) {
            return array_merge($payload, $data);
        }

        return array_slice($payload, 0, $index + 1, true)
            + $data
            + array_slice($payload, $index, count($payload), true);
    }

    /**
     * Update the client's endpoint to use the demo environment endpoint.
     *
     * @return void
     */
    protected function demo(): void
    {
        $this->endpoint = 'https://testpayments.nuvei.com/merchant/xmlpayment';
    }

    /**
     * Generate the hash of the request.
     *
     * @param  array  $options
     * @return string
     */
    public function generateHash(array $options): string
    {
        return hash('sha512', str_replace(
            [
                'TERMINALID',
                'SECRET',
            ],
            [
                $this->terminal,
                $this->secret,
            ],
            collect(explode(':', Arr::get($options, 'HASH')))
                ->map(static fn ($key) => Arr::get($options, $key, $key))
                ->join(':')
        ));
    }

    /**
     * Generate the XML payload based on the given options.
     *
     * @param  string  $action
     * @param  array  $options
     * @return string
     */
    protected function generatePayload(string $action, array $options = []): string
    {
        if (Arr::has($options, 'TERMINALID') && ! $options['TERMINALID']) {
            Arr::set($options, 'TERMINALID', $this->terminal);
        }

        if (Arr::has($options, 'DATETIME') && ! $options['DATETIME']) {
            Arr::set(
                $options,
                'DATETIME',
                Carbon::now()->format('d-m-Y:H:i:s:v')
            );
        }

        if (Arr::has($options, 'HASH')) {
            $options['HASH'] = $this->generateHash($options);
        }

        return $this->toXml($action, $options);
    }

    /**
     * Retrieve the endpoint of the client.
     *
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    /**
     * Send the request to Nuvei's api.
     *
     * @param  string  $action
     * @param  array  $options
     * @return \ChinLeung\Nuvei\Http\Response
     */
    public function send(string $action, array $options = []): Response
    {
        return new Response(
            Http::withBody($this->generatePayload($action, $options), 'xml')
                ->post($this->endpoint)
        );
    }

    /**
     * Convert an array of data into xml.
     *
     * @param  string  $root
     * @param  array  $data
     * @param  \DOMElement|null  $parent
     * @return string
     */
    protected function toXml(string $root, array $data, DOMElement $parent = null): string
    {
        if ($parent) {
            $dom = $parent->ownerDocument;
        } else {
            $dom = new DOMDocument('1.0');
            $dom->formatOutput = true;

            $parent = $dom->createElement($root);

            $dom->appendChild($parent);
        }

        foreach ($data as $key => $value) {
            $node = $dom->createElement($key);

            $parent->appendChild($node);

            if (is_array($value)) {
                $this->toXml($key, $value, $node);

                continue;
            }

            $node->appendChild($dom->createTextNode($value));
        }

        return $dom->saveXML();
    }
}
