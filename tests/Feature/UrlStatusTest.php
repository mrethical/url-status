<?php

namespace Tests;

use Muffeen\UrlStatus\UrlStatus;
use PHPUnit\Framework\TestCase;

class UrlStatusTest extends TestCase
{
    protected $testSite = 'http://example.com';
    protected $redirectionTestSite = 'http://google.com';

    /** @test */
    public function can_get_status_code()
    {
        $response = UrlStatus::get($this->testSite);
        $this->assertEquals(200, $response->getStatusCode());
    }

    /** @test */
    public function can_get_headers()
    {
        $response = UrlStatus::get($this->testSite);
        $this->assertNotEmpty($response->getResponseHeaders());
    }

    /** @test */
    public function can_follow_redirects()
    {
        $response = UrlStatus::get($this->redirectionTestSite)->followRedirect();
        $this->assertEquals(200, $response->getStatusCode());
    }
}
