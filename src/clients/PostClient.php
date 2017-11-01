<?php

namespace Platron\Connectum\clients;

use Platron\Connectum\clients\BaseClient;
use Platron\Connectum\SdkException;
use Platron\Connectum\services\BaseRequest;

class PostClient implements BaseClient {
    
    /**
     * {@inheritdoc}
     */
    public function sendRequest(BaseRequest $service, $logger, $connectionTimeout) {
        $requestParameters = $service->getParameters();
        $requestUrl = $service->getRequestUrl();
        
        $curl = curl_init($service->getRequestUrl());
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, $connectionTimeout);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $this->getHeaders());
        
        if(!empty($requestParameters)){
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($requestParameters));
        }
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($curl);
        
        if($logger){
            $this->logger->log(self::LOG_LEVEL, 'Requested url '.$requestUrl.' params '. json_encode($requestParameters));
            $this->logger->log(self::LOG_LEVEL, 'Response '.$response);
        }
		
		if(curl_errno($curl)){
			throw new SdkException(curl_error($curl), curl_errno($curl));
		}

        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        if ($httpCode !== self::OK_HTTP_CODE && $httpCode !== self::REDIRECT_HTTP_CODE) {
            throw new SdkException('Service error. Wrong http code '.$httpCode);
        }
        
        $decodedResponse = json_decode($response);
        if(empty($decodedResponse)){
            throw new SdkException('Service error. Empty response or not json response');
        }
		
		return $decodedResponse;
    }
}