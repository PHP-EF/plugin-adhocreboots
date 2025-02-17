<?php
// **
// USED TO DEFINE CRON JOBS
// **

// Set Schedules From Configuration
$adhocRebootConfig = $phpef->config->get('Plugins', 'AdhocReboot');
$adhocRebootSchedule = $adhocRebootConfig['Adhoc-Reboot-Check-Schedule'] ?? '30 * * * *';  // Run every hour at minute 30
$statusUpdateSchedule = $adhocRebootConfig['Status-Update-Schedule'] ?? '*/5 * * * *';  // Run every 5 minutes

// Scheduled execution of AdhocReboot jobs
$scheduler->call(function() {
    $AdhocReboot = new AdhocReboot();
    $awxPluginConfig = $AdhocReboot->config->get('Plugins', 'awx');
    
    // Check if AWX configuration is available
    if (isset($awxPluginConfig) && !empty($awxPluginConfig['Ansible-URL'])) {
        try {
            $result = $AdhocReboot->executeCronJob();
	    //$AdhocReboot->logging->writeLog('AdhocReboot', "Cron job result: ", 'debug', $result);
            if ($result['success']) {
                $AdhocReboot->updateCronStatus('AdhocReboot', 'Server Adhoc Reboot', 'success', $result['message'] ?? '');
            } else {
                $AdhocReboot->updateCronStatus('AdhocReboot', 'Server Adhoc Reboot', 'error', $result['error'] ?? 'Unknown error');
            }
        } catch (Exception $e) {
            $AdhocReboot->updateCronStatus('AdhocReboot', 'Server Adhoc Reboot', 'error', $e->getMessage());
        }
    }
})->at($adhocRebootSchedule);

// Scheduled update of AdhocReboot job statuses
$scheduler->call(function() {
    $AdhocReboot = new AdhocReboot();
    $awxPluginConfig = $AdhocReboot->config->get('Plugins', 'awx');
    
    // Check if AWX configuration is available
    if (isset($awxPluginConfig) && !empty($awxPluginConfig['Ansible-URL'])) {
        try {
            $result = $AdhocReboot->updateAdhocRebootJobStatuses();
            if ($result['success']) {
                $message = "Updated {$result['updated_count']} job(s)";
                $AdhocReboot->updateCronStatus('AdhocReboot', 'Status Updates', 'success', $message);
            } else {
                $AdhocReboot->updateCronStatus('AdhocReboot', 'Status Updates', 'error', $result['error'] ?? 'Unknown error');
            }
        } catch (Exception $e) {
            $AdhocReboot->updateCronStatus('AdhocReboot', 'Status Updates', 'error', $e->getMessage());
        }
    }
})->at($statusUpdateSchedule);
