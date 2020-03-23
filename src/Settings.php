<?php

/**
 * (c) Jefferson Magboo <jeffersonmagboo21@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Muffeen\UrlStatus;

class Settings
{
    /**
     * Request settings.
     *
     * @var array
     */
    protected $settings = [
        CURLOPT_CONNECTTIMEOUT => 5,
        CURLOPT_TIMEOUT => 30,
    ];

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
        $package_info = Settings::getPackageInfo();
        $version = isset($package_info['version']) ? $package_info['version'] : 'dev-master';

        return 'url-status/'.$version.' (+'.$package_info['homepage'].')';
    }

    /**
     * Settings constructor.
     *
     * @param array|null $settings
     */
    public function __construct(array $settings = null)
    {
        if (!isset($settings[CURLOPT_USERAGENT])) {
            $this->settings[CURLOPT_USERAGENT] = Settings::getDefaultUserAgent();
        }
        $this->settings = ($settings?:[])  + $this->settings;
    }

    /**
     * Get settings.
     *
     * @return array
     */
    public function getSettings()
    {
        return $this->settings;
    }
}
