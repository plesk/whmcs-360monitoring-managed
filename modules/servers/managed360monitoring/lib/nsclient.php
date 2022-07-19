<?php
/**
 * WHMCS 360 Monitoring for Managed Services Provisioning Module
 * 360 Monitoring Client
 * (C) 2022 Plesk International GmbH
**/

namespace WHMCS\Module\Server\Managed360Monitoring;

require_once __DIR__ . DIRECTORY_SEPARATOR . 'curlhelper.php';

use WHMCS\Module\Server\Managed360Monitoring\CURLHelper as CURLHelper;

class NSApiClient {

    const API_URL_BASE = 'https://api.monitoring360.io/v1';
    const JSON_CONTENT_TYPE = 'application/json';

    protected $results = array();
    private $apikey;

    public function __construct($apikey)
    {
        $this->apikey = $apikey;
    }

    public function create_monitor($name, $protocol, $url, $interval)
    {
        $postData = json_encode(array(
            'name' => $name,
            'type' => $protocol,
            'url' => $url,
            'interval' => $interval,
            'contacts' => array()
        ));

        $ch =  CURLHelper::preparePOST(self::API_URL_BASE.'/monitors', '', '', $this->apikey, self::JSON_CONTENT_TYPE, $postData);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Connection Error: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }
        curl_close($ch);

        $this->results = $this->processResponse($response);
       
        if (defined("WHMCS")) {
            logModuleCall(
                'managed360monitoring',
                'create',
                $postData,
                $response,
                $this->results,
                array(
                )
            );
        }

        if ($this->results === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Bad response received from API');
        }

        return $this->results;
    }

    public function retrieve_monitor($monitorId)
    {
        $ch =  CURLHelper::prepareGET(self::API_URL_BASE.'/monitor/'.$monitorId, '', '', $this->apikey, self::JSON_CONTENT_TYPE);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Connection Error: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }
        curl_close($ch);

        $this->results = $this->processResponse($response);
        
        if (defined("WHMCS")) {
            logModuleCall(
                'managed360monitoring',
                'retrieve',
                $monitorId,
                $response,
                $this->results,
                array(
                )
            );
        }

        if ($this->results === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Bad response received from API');
        }

        return $this->results;
    }

    public function retrieve_monitor_metrics($monitorId)
    {
        $ch =  CURLHelper::prepareGET(self::API_URL_BASE.'/monitor/'.$monitorId.'/metrics', '', '', $this->apikey, self::JSON_CONTENT_TYPE);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Connection Error: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }
        curl_close($ch);

        $this->results = $this->processResponse($response);
        
        if (defined("WHMCS")) {
            logModuleCall(
                'managed360monitoring',
                'retrieve_metrics',
                $monitorId,
                $response,
                $this->results,
                array(
                )
            );
        }

        if ($this->results === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Bad response received from API');
        }

        return $this->results;
    }

    public function update_monitor($monitorId, $name, $protocol, $url, $interval)
    {
        $postData = json_encode(array(
            'name' => $name,
            'type' => $protocol,
            'url' => $url,
            'interval' => $interval,
            'contacts' => array()
        ));

        $ch =  CURLHelper::preparePUT(self::API_URL_BASE.'/monitor/'.$monitorId, '', '', $this->apikey, self::JSON_CONTENT_TYPE, $postData);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Connection Error: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }
        curl_close($ch);

        $this->results = $this->processResponse($response);
       
        if (defined("WHMCS")) {
            logModuleCall(
                'managed360monitoring',
                'update',
                $postData,
                $response,
                $this->results,
                array(
                )
            );
        }

        if ($this->results === null && json_last_error() !== JSON_ERROR_NONE) {
            throw new \Exception('Bad response received from API');
        }

        return $this->results;
    }

    public function delete_monitor($monitorId)
    {
        $ch =  CURLHelper::prepareDELETE(self::API_URL_BASE.'/monitor/'.$monitorId, '', '', $this->apikey, self::JSON_CONTENT_TYPE);
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new \Exception('Connection Error: ' . curl_errno($ch) . ' - ' . curl_error($ch));
        }
        curl_close($ch);

        $this->results = $this->processResponse($response);

        if (defined("WHMCS")) {
            logModuleCall(
                'managed360monitoring',
                'delete',
                $monitorId,
                $response,
                $this->results,
                array(
                )
            );
        }

        if ($this->results !== null) {
            throw new \Exception('Bad response received from API');
        }

        return true;
    }

    public function processResponse($response)
    {
        return json_decode($response, true);
    }

}