<?php
// **
// USED TO DEFINE API ENDPOINTS
// **

// Get Plugin Settings
$app->get('/plugin/AdhocReboot/settings', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $AdhocReboot->api->setAPIResponseData($AdhocReboot->_pluginGetSettings());
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});

// Search for servers with their reboot configuration
$app->get('/plugin/AdhocReboot/servers', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $AdhocReboot->api->setAPIResponseData($AdhocReboot->searchServers($request));
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});

// Get servers that are due for reboot based on current time
$app->get('/plugin/AdhocReboot/servers/due', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $AdhocReboot->api->setAPIResponseData($AdhocReboot->getServersDueForReboot());
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});

// Get servers that are due for reboot based on current time, categorised by OS
$app->get('/plugin/AdhocReboot/servers/due/categorised', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $AdhocReboot->api->setAPIResponseData($AdhocReboot->categorizeServersDueForReboot());
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});

// Get reboot history
$app->get('/plugin/AdhocReboot/history', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $AdhocReboot->api->setAPIResponseData($AdhocReboot->getRebootHistory());
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});

// Check if server exists in AWX inventory
$app->get('/plugin/AdhocReboot/server/check/{serverName}', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $serverName = $args['serverName'];
        $result = $AdhocReboot->checkServerInAWX($serverName);
        $AdhocReboot->api->setAPIResponseData($result);
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});

// Trigger cron job execution
$app->get('/plugin/AdhocReboot/cron/execute', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $AdhocReboot->logging->writeLog('AdhocReboot', 'Manually triggering cron job execution', 'debug');
        $AdhocReboot->api->setAPIResponseData($AdhocReboot->executeCronJob());
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});

// Verify database structure
$app->get('/plugin/AdhocReboot/database/verify', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $AdhocReboot->logging->writeLog('AdhocReboot', 'Verifying database structure', 'debug');
        $AdhocReboot->api->setAPIResponseData($AdhocReboot->verifyDatabaseStructure());
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});

// Recreate database structure
$app->post('/plugin/AdhocReboot/database/recreate', function ($request, $response, $args) {
    $AdhocReboot = new AdhocReboot();
    if ($AdhocReboot->auth->checkAccess('ADMIN-CONFIG')) {
        $AdhocReboot->logging->writeLog('AdhocReboot', 'Recreating database structure', 'debug');
        $AdhocReboot->api->setAPIResponseData($AdhocReboot->recreateDatabaseStructure());
    }
    $response->getBody()->write(jsonE($GLOBALS['api']));
    return $response
        ->withHeader('Content-Type', 'application/json;charset=UTF-8')
        ->withStatus($GLOBALS['responseCode']);
});