<?php

/**
 * (c) Jefferson Magboo <jeffersonmagboo21@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Muffeen\UrlStatus;

class UrlStatus
{
    /**
     * Request url.
     *
     * @var string
     */
    protected $url;

    /**
     * Request settings.
     *
     * @var Settings
     */
    protected $settings;

    /**
     * HTTP status code.
     *
     * @var string
     */
    protected $code;

    /**
     * Response headers.
     *
     * @var array
     */
    protected $headers;

    /**
     * Redirect url.
     *
     * @var array
     */
    protected $redirect_url;

    /**
     * UrlStatus constructor.
     *
     * @param $url
     * @param Settings|null $settings
     */
    public function __construct($url, Settings $settings = null)
    {
        $this->url = $url;
        $this->settings = !is_null($settings) ? $settings : new Settings();
        $this->headers = [];
    }

    /**
     * Get returned status code of url.
     *
     * @return string
     */
    public function getStatusCode()
    {
        return $this->code;
    }

    /**
     * Get response headers of url.
     *
     * @return array
     */
    public function getResponseHeaders()
    {
        return $this->headers;
    }

    /**
     * Get url status details for a given url.
     *
     * @param $url
     * @param Settings|null $settings
     * @return UrlStatus
     */
    public static function get($url, Settings $settings = null)
    {
        $status = new self($url, $settings);
        return $status->runRequest();
    }

    /**
     * Run url request.
     *
     * @param array $settings
     * @return $this
     */
    protected function runRequest(array $settings = [])
    {
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            [
                CURLOPT_URL => $this->url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADERFUNCTION => function ($curl, $header) {
                    $len = strlen($header);
                    $header = explode(':', $header, 2);
                    if (count($header) < 2) {
                        return $len;
                    }
                    $name = strtolower(trim($header[0]));
                    if (!array_key_exists($name, $this->headers)) {
                        $this->headers[$name] = [trim($header[1])];
                    } else {
                        $this->headers[$name][] = trim($header[1]);
                    }
                    return $len;
                }
            ]
            + $settings
            + $this->settings->getSettings()
            + [
                CURLOPT_HEADER => true,
                CURLOPT_NOBODY => true,
            ]
        );
        curl_exec($curl);
        $this->code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $this->redirect_url = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
        curl_close($curl);
        return $this;
    }

    /**
     * Follow redirections.
     *
     * @return UrlStatus
     */
    public function followRedirect()
    {
        return $this->redirect_url ? $this->runRequest([CURLOPT_FOLLOWLOCATION => true]): $this;
    }
}
