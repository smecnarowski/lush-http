<?php

namespace Appstract\LushHttp\Test;

use Appstract\LushHttp\Lush;

class LushTest extends BaseTest
{
    /**
     * @var array
     */
    protected $headers = [
        'content_type',
        'header_size',
        'request_size',
        'ssl_verify_result',
        'redirect_count',
        'size_upload',
        'size_download',
        'speed_download',
        'speed_upload',
        'download_content_length',
        'upload_content_length',
        'starttransfer_time',
        'redirect_time',
        'redirect_url',
        'primary_ip',
        'certinfo',
        'primary_port',
        'local_ip',
        'local_port',
    ];

    /** @test */
    public function get_without_parameters()
    {
        $options = [
            'url'           => 'http://localhost',
        ];

        // the test
        $lush = new Lush($options['url']);
        $response = $lush->get();

        // check it
        $this->checkAll($response, $options);
    }

    /** @test */
    public function get_xml()
    {
        $options = [
            'url'           => 'http://localhost',
            'content_type'  => 'xml',
        ];

        // the test
        $lush = new Lush($options['url'], ['return_content_type' => $options['content_type']]);
        $response = $lush->get();

        // check it
        $this->checkAll($response, $options);
    }

    /** @test */
    public function get_json()
    {
        $options = [
            'url'           => 'http://localhost',
            'content_type'  => 'json',
        ];

        // the test
        $lush = new Lush($options['url'], ['return_content_type' => $options['content_type']]);
        $response = $lush->get();

        // check it
        $this->checkAll($response, $options);
    }
}