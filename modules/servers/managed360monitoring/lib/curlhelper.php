<?php
/**
 * WHMCS 360 Monitoring for Managed Services Provisioning Module
 * cURL Helper
 * (C) 2022 Plesk International GmbH
**/

namespace WHMCS\Module\Server\Managed360Monitoring;

class CURLHelper {
    
    static private function prepare($url, $username, $password, $apikey, $contentType) 
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $httpheaders = array();
        if ($username != "") {
            curl_setopt($ch, CURLOPT_USERPWD, $username . ":" . $password);
        }
        if ($contentType != "") {
            $httpheaders[] = 'Content-Type: ' . $contentType;
        }
        if ($apikey != "") {
            $httpheaders[] = 'Authorization: Bearer ' . $apikey;
        }

        curl_setopt($ch, CURLOPT_HTTPHEADER, $httpheaders);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 100);
        curl_setopt($ch, CURLOPT_FAILONERROR, true);

        return $ch;
    }
    
    static public function preparePOST($url, $username, $password, $apikey, $contentType, $postData) 
    {
        $ch = self::prepare($url, $username, $password, $apikey, $contentType);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        return $ch;
    }

    static public function preparePUT($url, $username, $password, $apikey, $contentType, $postData) 
    {
        $ch = self::prepare($url, $username, $password, $apikey, $contentType);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);

        return $ch;
    }

    static public function prepareGET($url, $username, $password, $apikey, $contentType) 
    {
        $ch = self::prepare($url, $username, $password, $apikey, $contentType);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
        curl_setopt($ch, CURLOPT_POST, 0);

        return $ch;
    }

    static public function prepareDELETE($url, $username, $password, $apikey) 
    {
        $ch = self::prepare($url, $username, $password, $apikey, '');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POST, 0);
        
        return $ch;
    }
}