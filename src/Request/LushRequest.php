<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Response\LushResponse;

class LushRequest extends CurlRequest
{
    /**
     * LushRequest constructor.
     *
     * @param $method
     * @param $payload
     */
    public function __construct($method, $payload)
    {
        parent::__construct();

        $this->method = $method;
        $this->payload = $payload;

        $this->prepareRequest();
    }

    /**
     * Prepare the request.
     */
    protected function prepareRequest()
    {
        $this->addHeaders();
        $this->addParameters();
        $this->setOptions();
    }

    /**
     * Add request headers.
     */
    protected function addHeaders()
    {
        $userHeaders = array_map(function ($key, $value) {
            // format header like this 'x-header: value'
            return sprintf('%s: %s', $key, $value);
        }, array_keys($this->payload['headers']), $this->payload['headers']);

        $headers = array_merge($this->defaultHeaders, $userHeaders);

        $this->addCurlOption(CURLOPT_HTTPHEADER, $headers);
    }

    /**
     *  Add request parameters.
     */
    protected function addParameters()
    {
        $parameters = http_build_query($this->payload['parameters']);

        if ($this->method == 'POST') {
            $this->addCurlOption(CURLOPT_POSTFIELDS, $parameters);
        } else {
            // append parameters in the url
            $this->payload['url'] = sprintf('%s?%s', $this->payload['url'], $parameters);
        }
    }

    /**
     * Add Lush option.
     *
     * @param $key
     * @param $value
     */
    protected function addOption($key, $value)
    {
        $this->options[$key] = $value;
    }

    /**
     * Add Curl option.
     *
     * @param $key
     * @param $value
     */
    protected function addCurlOption($key, $value)
    {
        $this->curlOptions[$key] = $value;
    }

    /**
     * Set request options.
     */
    protected function setOptions()
    {
        // Handle options from payload
        if (is_array($this->payload['options'])) {
            $options = $this->payload['options'];

            // Add authentication
            if (isset($options['username']) && isset($options['password'])) {
                $this->addCurlOption(CURLOPT_USERPWD, sprintf('%s:%s', $options['username'], $options['password']));
            }

            // Add user options
            foreach ($options as $option => $value) {
                $resolvedOption = OptionResolver::resolve($option);

                if ($resolvedOption['type'] == 'curl_option') {
                    $this->addCurlOption($resolvedOption['option'], $value);
                } else {
                    $this->addOption($option, $value);
                }
            }
        }

        // Set method
        if ($this->method == 'POST') {
            $this->addCurlOption(CURLOPT_POST, true);
        } elseif (in_array($this->method, ['DELETE', 'PATCH', 'PUT'])) {
            $this->addCurlOption(CURLOPT_CUSTOMREQUEST, $this->method);
        }

        // Set allowed protocols
        if (defined('CURLOPT_PROTOCOLS')) {
            $this->addCurlOption(CURLOPT_PROTOCOLS, CURLPROTO_HTTP | CURLPROTO_HTTPS);
        }

        $this->mergeCurlOptions();
    }

    /**
     * Send the Curl request.
     *
     * @return \Appstract\LushHttp\Response\LushResponse
     */
    public function send()
    {
        $response = $this->makeRequest();

        return new LushResponse($response, $this);
    }
}
