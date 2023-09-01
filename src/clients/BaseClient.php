<?php

namespace Platron\Connectum\clients;

use Platron\Connectum\services\BaseRequest;
use stdClass;
use Psr\Log\LoggerInterface;
use Platron\Connectum\data_objects\ConnectionSettingsData;
use Psr\Log\LogLevel;

abstract class BaseClient {

    const LOG_LEVEL = LogLevel::INFO;
    
    /** @var string */
    protected $errorDescription;
    /** @var int */
    protected $errorCode;
    /** @var ConnectionSettingsData */
    protected $connectionSettings;
    /** @var LoggerInterface */
    protected $logger;
	/** @var int */
    protected $httpCode;
    
    /**
     * Send request
     * @param BaseRequest $service
     * @return stdClass
     */
    abstract public function sendRequest(BaseRequest $service);
    
    /**
     * @param ConnectionSettingsData $connectionSettings
     */
    public function __construct(ConnectionSettingsData $connectionSettings) {
        $this->connectionSettings = $connectionSettings;
    }
    
    /**
     * Get headers
     * @return array
     */
    public function getHeaders(){
        return array(
            'Content-Type: application/json',
        );
    }

	/**
	 * Get http code
	 * @return int
	 */
    public function getHttpCode(){
    	return $this->httpCode;
	}

    /**
     * Set log
     * @param LoggerInterface $logger
     * @return self
     */
    public function setLogger(LoggerInterface $logger){
        $this->logger = $logger;
        return $this;
    }

    /**
     * Замаскировать данные
     * @param array $paramsToMask
     * @return array
     */
    protected function getMaskedParams($paramsToMask){
        array_walk_recursive($paramsToMask, function(&$value, $key) {
            if(in_array($key, array('pan', 'cvv', 'holder', 'expiration_month', 'expiration_year'))){
                $value = str_repeat('*', strlen($value));
            }
        });
        
        return $paramsToMask;
    }
}
