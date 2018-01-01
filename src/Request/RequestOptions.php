<?php

namespace Appstract\LushHttp\Request;

use Appstract\LushHttp\Lush;
use Appstract\LushHttp\Exception\LushException;

class RequestOptions
{
    /**
     * Configurable Curl options.
     *
     * @var array
     */
    public static $curlOptions = [
        'user_agent'        => CURLOPT_USERAGENT,        // custom user agent
        'ua'                => CURLOPT_USERAGENT,        // alias for custom user agent
        'timeout'           => CURLOPT_TIMEOUT,          // timeout
        'connect_timeout'   => CURLOPT_CONNECTTIMEOUT,   // timeout for connection
        'encoding'          => CURLOPT_ENCODING,         // custom encoding
        'follow_redirects'  => CURLOPT_FOLLOWLOCATION,   // follow redirects
        'fail_on_error'     => CURLOPT_FAILONERROR,      // throw exception if return code is not a success code
        'verify_ssl'        => CURLOPT_SSL_VERIFYPEER,   // verify ssl
        'verify_host'       => CURLOPT_SSL_VERIFYHOST,   // verify host domain

        'cookies'           => CURLOPT_COOKIE,           // (internal) set cookies
        'cookiejar'         => CURLOPT_COOKIEJAR,        // (internal) set cookiejar
        'cookie_file'       => CURLOPT_COOKIEFILE,        // (internal) set cookie file
    ];

    /**
     * Lush options.
     *
     * @var array
     */
    public static $lushOptions = [
        'auto_format',          // automatic format response
        'username',             // username for authentication
        'password',             // password for authentication

        'return_status',        // (internal) used for testing return status
        'return_content_type',  // (internal) used for testing content types
        'body_format',           // (internal) used for body formatting
    ];

    /**
     * Curl Defaults (internal).
     *
     * @var array
     */
    public static $defaultCurlOptions = [
        CURLOPT_RETURNTRANSFER  => true,            // return web page
        CURLOPT_HEADER          => false,           // return headers
        CURLOPT_FOLLOWLOCATION  => true,            // follow redirects
        CURLOPT_ENCODING        => '',              // handle compressed
        CURLOPT_CONNECTTIMEOUT  => 60,              // time-out on connect
        CURLOPT_TIMEOUT         => 300,             // time-out on response
        CURLOPT_AUTOREFERER     => true,
        CURLOPT_FAILONERROR     => true,
        CURLOPT_USERAGENT       => 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Lush Http Client',
    ];

    /**
     * @param string $option
     *
     * @return mixed
     */
    public static function resolve($option)
    {
        if (isset(self::$curlOptions[$option])) {
            return [
                'type' => 'curl_option',
                'option' => self::$curlOptions[$option],
            ];
        } elseif (in_array($option, self::$lushOptions)) {
            return [
                'type' => 'lush_option',
                'option' => $option,
            ];
        }

        throw new LushException(sprintf("Invalid option '%s'", $option));
    }
}
