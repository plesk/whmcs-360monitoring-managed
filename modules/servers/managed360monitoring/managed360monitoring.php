<?php
/**
 * WHMCS 360 Monitoring for Managed Services Provisioning Module
 * Version 1.0
 * (C) 2022 Plesk International GmbH
**/

if (!defined("WHMCS")) {
    die("This file cannot be accessed directly");
}

require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'nsclient.php';
require_once __DIR__ . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'langhelper.php';

use WHMCS\Module\Server\Managed360Monitoring\NSApiClient as NSApiClient;
use WHMCS\Module\Server\Managed360Monitoring\LangHelper;

const PARAM_APIKEY = 'configoption1';
const PARAM_MONITORINGTYPE = 'configoption2';

const DEFAULT_PROTOCOL = 'https';
const DEFAULT_INTERVAL = 60;

function managed360monitoring_MetaData()
{
    return array(
        'DisplayName' => '360 Monitoring for Managed Services',
        'APIVersion' => '1.1',
        'RequiresServer' => false,
        'ServiceSingleSignOnLabel' => false,
    );
}

function managed360monitoring_ConfigOptions()
{
    return array(
        // configoption1
        'API Key' => array(
            'Type' => 'text',
            'Size' => '64',
            'Default' => '',
            'Description' => '',
        ),
        // configoption2
        'Monitoring Type' => array(
            'Type' => 'dropdown',
            'Options' => array(
                'website' => 'Website Monitor',
                'server' => 'Server Monitor'),
            'Description' => ''
        ),
    );
}

function managed360monitoring_ClientArea(array $params)
{
    $monitorId = $params['customfields']['monitorId'];

    try 
    {
        $langHelper = new LangHelper($_SESSION['Language']);
        $api = new NSApiClient($params[PARAM_APIKEY]);

        if ($params[PARAM_MONITORINGTYPE] === 'website') {
            $result = $api->retrieve_monitor($monitorId);

            $returnHtml = '<div class="tab-content">';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3"><strong>' . $langHelper->getLangValue('label_monitoring_results', 'Monitoring Results') . '</strong></div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_site', 'Site') . '</div><div class="col-sm-3 text-left">'.$result['url'].'</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_protocol', 'Protocol') . '</div><div class="col-sm-3 text-left">'.$result['type'].'</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_status', 'Status') . '</div><div class="col-sm-3 text-left">'.$result['status'].'</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_statuscode', 'Status Code') . '</div><div class="col-sm-3 text-left">'.$result['code'].'</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_timetotal', 'Time Total') . '</div><div class="col-sm-3 text-left">'.$result['last_check']['time_total'].' s</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_timefb', 'Time To First Byte') . '</div><div class="col-sm-3 text-left">'.$result['last_check']['time_to_first_byte'].' s</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_timedns', 'Time DNS') . '</div><div class="col-sm-3 text-left">'.$result['last_check']['time_dns'].' s</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_timeconnect', 'Time Connect') . '</div><div class="col-sm-3 text-left">'.$result['last_check']['time_connect'].' s</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_uptime24h', 'Uptime (24h)') . '</div><div class="col-sm-3 text-left">'.$result['uptimes'][0]['uptime_percentage'].'%</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_uptime7d', 'Uptime (7d)') . '</div><div class="col-sm-3 text-left">'.$result['uptimes'][1]['uptime_percentage'].'%</div></div>';
            $returnHtml = $returnHtml.'<div class="row"><div class="col-sm-3">' . $langHelper->getLangValue('label_uptime30d', 'Uptime (30d)') . '</div><div class="col-sm-3 text-left">'.$result['uptimes'][2]['uptime_percentage'].'%</div></div>';
        } else {
            // TBD
        }

        return $returnHtml;

    } catch (Exception $e) {
        logModuleCall(
            'managed360monitoring',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );
    }
    
    return $returnHtml;
}

function managed360monitoring_CreateAccount(array $params)
{
    try {
        $api = new NSApiClient($params[PARAM_APIKEY]);
        $name = $params['domain'].' [WHMCS Account #'.$params['accountid'].']';
        $url = $params['domain'];
        $protocol = DEFAULT_PROTOCOL;
        $interval = DEFAULT_INTERVAL;
        $result = $api->create_monitor($name, $protocol, $url, $interval);

        managed360monitoring_UpdateModel($params, $result);
    } catch (Exception $e) {
        logModuleCall(
            'managed360monitoring',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

function managed360monitoring_TerminateAccount(array $params)
{
    try {
        $monitorId = $params['customfields']['monitorId'];

        $api = new NSApiClient($params[PARAM_APIKEY]);
        $result = $api->delete_monitor($monitorId);

        managed360monitoring_UpdateModel($params, $result);
    } catch (Exception $e) {
        logModuleCall(
            'managed360monitoring',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

function managed360monitoring_ChangePackage(array $params)
{
    try {
        $monitorId = $params['customfields']['monitorId'];
        $name = $params['domain'].' [WHMCS Account #'.$params['accountid'].']';
        $url = $params['domain'];
        $protocol = DEFAULT_PROTOCOL;
        $interval = DEFAULT_INTERVAL;
        
        $api = new NSApiClient($params[PARAM_APIKEY]);
        $result = $api->update_monitor($monitorId, $name, $protocol, $url, $interval);

        managed360monitoring_UpdateModel($params, $result);
    } catch (Exception $e) {
        logModuleCall(
            'managed360monitoring',
            __FUNCTION__,
            $params,
            $e->getMessage(),
            $e->getTraceAsString()
        );

        return $e->getMessage();
    }

    return 'success';
}

function managed360monitoring_UpdateModel($params, $result) {
    $params['model']->serviceProperties->save(['monitorId' => $result['id']
                                            ]);
}

