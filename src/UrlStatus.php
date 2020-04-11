<?php

/**
 * (c) Jefferson Magboo <jeffersonmagboo21@gmail.com>.
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
     * Request options.
     *
     * @var array
     */
    protected $options = array(
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 30,
    );

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
    protected $headers = array();

    /**
     * Redirect url.
     *
     * @var array
     */
    protected $redirect_url;

    /**
     * UrlStatus constructor.
     *
     * @param  string $url
     */
    public function __construct($url)
    {
        $this->url = $url;

        $this->options[CURLOPT_USERAGENT] = self::getDefaultUserAgent();
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
     * Set curl options.
     *
     * @param  array $options
     * @return UrlStatus
     */
    public function setCurlOptions($options)
    {
        $this->options = $options + $this->options;

        return $this;
    }

    /**
     * Run url request.
     *
     * @param  array $options
     * @return $this
     */
    protected function runRequest(array $options = array())
    {
        $self = $this;
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            (
                array(
                    CURLOPT_URL => $this->url,
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_HEADERFUNCTION => function ($curl, $header) use ($self) {
                        $len = strlen($header);
                        $header = explode(':', $header, 2);
                        if (count($header) < 2) {
                            return $len;
                        }
                        $name = strtolower(trim($header[0]));
                        if (! array_key_exists($name, $self->headers)) {
                            $self->headers[$name] = array(trim($header[1]));
                        } else {
                            $self->headers[$name][] = trim($header[1]);
                        }

                        return $len;
                    },
                ) +
                $options +
                $this->options +
                array(
                    CURLOPT_HEADER => true,
                    CURLOPT_NOBODY => true,
                )
            )
        );
        curl_exec($curl);
        $this->code = curl_getinfo($curl, CURLINFO_RESPONSE_CODE);
        $this->redirect_url = curl_getinfo($curl, CURLINFO_REDIRECT_URL);
        curl_close($curl);

        return $this;
    }

    /**
     * Get url status details for a given url.
     *
     * @param  string $url
     * @param  array $options
     * @return UrlStatus
     */
    public static function get($url, $options = array())
    {
        $status = new self($url);
        $status->options = $options + $status->options;

        return $status->runRequest();
    }

    /**
     * Follow redirections.
     *
     * @return UrlStatus
     */
    public function followRedirect()
    {
        return $this->redirect_url ? $this->runRequest(array(CURLOPT_FOLLOWLOCATION => true)) : $this;
    }

    /**
     * Get package information of this library.
     *
     * @return mixed
     */
    private static function getPackageInfo()
    {
        return json_decode(file_get_contents(__DIR__.'/../composer.json'), true);
    }

    /**
     * Generate a default user agent for request.
     *
     * @return string
     */
    private static function getDefaultUserAgent()
    {
        $package_info = self::getPackageInfo();
        $version = isset($package_info['version']) ? $package_info['version'] : 'dev-master';

        return 'url-status/'.$version.' (+'.$package_info['homepage'].')';
    }
}
