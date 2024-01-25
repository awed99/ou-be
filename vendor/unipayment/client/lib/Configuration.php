<?php
/**
 * Configuration
 */

namespace UniPayment\Client;

/**
 * Configuration
 */
class Configuration
{
    private static $defaultConfiguration;

    /**
     * Client ID
     *
     * @var string
     */
    protected $clientId = '';

    /**
     * Client Secret
     *
     * @var string
     */
    protected $clientSecret = '';

    /**
     * Api Host
     *
     * @var string
     */
    protected $apiHost = 'https://api.unipayment.io';

    /**
     * Sandbox switch (default set to false)
     *
     * @var bool
     */
    protected $isSandbox = false;

    /**
     * Api Version
     *
     * @var string
     */
    protected $apiVersion = '1.0';

    /**
     * User agent of the HTTP request
     *
     * @var string
     */
    protected $userAgent = 'unipayment_sdk_php/1.1.2';

    /**
     * Debug switch (default set to false)
     *
     * @var bool
     */
    protected $debug = false;

    /**
     * Debug file location (log to STDOUT by default)
     *
     * @var string
     */
    protected $debugFile = 'php://output';

    /**
     * Debug file location (log to STDOUT by default)
     *
     * @var string
     */
    protected $tempFolderPath;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->tempFolderPath = sys_get_temp_dir();
    }

    /**
     * Set Client ID
     *
     * @param string $clientId Client ID
     *
     * @return $this
     */
    public function setClientId($clientId)
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * Gets the Client ID
     *
     * @return string Client ID
     */
    public function getClientId()
    {
        return $this->clientId;
    }

    /**
     * Set Client Secret
     *
     * @param string $clientSecret  Client Secret
     *
     * @return $this
     */
    public function setClientSecret($clientSecret)
    {
        $this->clientSecret = $clientSecret;
        return $this;
    }

    /**
     * Gets API key
     *
     * @return string API key
     */
    public function getClientSecret()
    {
        return $this->clientSecret;
    }

    /**
     * Sets the API host
     *
     * @param string $apiHost API Host
     *
     * @return $this
     */
    public function setApiHost($apiHost)
    {
        $this->apiHost = $apiHost;
        return $this;
    }

    /**
     * Gets the API Host
     *
     * @return string API Host
     */
    public function getHost()
    {
        return $this->apiHost;
    }

    /**
     * Sets isSandbox flag
     *
     * @param bool $isSandbox IsSandbox flag
     *
     * @return $this
     */
    public function setIsSandbox($isSandbox)
    {
        $this->isSandbox = $isSandbox;
        if($this->isSandbox)
        {
            $this->apiHost="https://sandbox-api.unipayment.io";
        }
        else
        {
            $this->apiHost="https://api.unipayment.io";
        }
        return $this;
    }

    /**
     * Gets the isSandbox flag
     *
     * @return bool
     */
    public function getIsSandbox()
    {
        return $this->isSandbox;
    }

    /**
     * Sets the API Version
     *
     * @param string $apiVersion API Version
     *
     * @return $this
     */
    public function setApiVersion($apiVersion)
    {
        $this->apiVersion = $apiVersion;
        return $this;
    }

    /**
     * Gets the API Version
     *
     * @return string API Version
     */
    public function getApiVersion()
    {
        return $this->apiVersion;
    }

    /**
     * Sets the user agent of the api client
     *
     * @param string $userAgent the user agent of the api client
     *
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function setUserAgent($userAgent)
    {
        if (!is_string($userAgent)) {
            throw new \InvalidArgumentException('User-agent must be a string.');
        }

        $this->userAgent = $userAgent;
        return $this;
    }

    /**
     * Gets the user agent of the api client
     *
     * @return string user agent
     */
    public function getUserAgent()
    {
        return $this->userAgent . ' (' . php_uname('s') . ' ' . php_uname('r') . ')';
    }

    /**
     * Sets debug flag
     *
     * @param bool $debug Debug flag
     *
     * @return $this
     */
    public function setDebug($debug)
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Gets the debug flag
     *
     * @return bool
     */
    public function getDebug()
    {
        return $this->debug;
    }

    /**
     * Sets the debug file
     *
     * @param string $debugFile Debug file
     *
     * @return $this
     */
    public function setDebugFile($debugFile)
    {
        $this->debugFile = $debugFile;
        return $this;
    }

    /**
     * Gets the debug file
     *
     * @return string
     */
    public function getDebugFile()
    {
        return $this->debugFile;
    }

    /**
     * Sets the temp folder path
     *
     * @param string $tempFolderPath Temp folder path
     *
     * @return $this
     */
    public function setTempFolderPath($tempFolderPath)
    {
        $this->tempFolderPath = $tempFolderPath;
        return $this;
    }

    /**
     * Gets the temp folder path
     *
     * @return string Temp folder path
     */
    public function getTempFolderPath()
    {
        return $this->tempFolderPath;
    }

    /**
     * Gets the default configuration instance
     *
     * @return Configuration
     */
    public static function getDefaultConfiguration()
    {
        if (self::$defaultConfiguration === null) {
            self::$defaultConfiguration = new Configuration();
        }

        return self::$defaultConfiguration;
    }

    /**
     * Sets the detault configuration instance
     *
     * @param Configuration $config An instance of the Configuration Object
     *
     * @return void
     */
    public static function setDefaultConfiguration(Configuration $config)
    {
        self::$defaultConfiguration = $config;
    }

    /**
     * Gets the essential information for debugging
     *
     * @return string The report for debugging
     */
    public static function toDebugReport()
    {
        $report = 'PHP SDK (UniPayment\Client) Debug Report:' . PHP_EOL;
        $report .= '    OS: ' . php_uname() . PHP_EOL;
        $report .= '    PHP Version: ' . PHP_VERSION . PHP_EOL;
        $report .= '    OpenAPI Spec Version: 1.0.0' . PHP_EOL;
        $report .= '    Temp Folder Path: ' . self::getDefaultConfiguration()->getTempFolderPath() . PHP_EOL;

        return $report;
    }

}
