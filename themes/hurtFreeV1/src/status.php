<?php
if (!defined('FLUX_ROOT')) exit;

$title = Flux::message('ServerStatusTitle');
$tbl = Flux::config('FluxTables.OnlinePeakTable'); 

	$serverStatus = array();
	foreach (Flux::$loginAthenaGroupRegistry as $groupName => $loginAthenaGroup) {
		if (!array_key_exists($groupName, $serverStatus)) {
			$serverStatus[$groupName] = array();
		}

		$loginServerUp = $loginAthenaGroup->loginServer->isUp();

		foreach ($loginAthenaGroup->athenaServers as $athenaServer) {
			$serverName = $athenaServer->serverName;

			$sql = "SELECT COUNT(char_id) AS players_online FROM {$athenaServer->charMapDatabase}.char WHERE `online` > '0'";
			$sth = $loginAthenaGroup->connection->getStatement($sql);
			$sth->execute();
			$res = $sth->fetch();

			if(Flux::config('EnablePeakDisplay')){
				$sth = $server->connection->getStatement("SELECT `users` FROM {$server->charMapDatabase}.$tbl");
				$sth->execute();
				$peak = $sth->fetch();
			}
			
			$sql = "SELECT `status` FROM {$athenaServer->charMapDatabase}.woe_status";
			$sth = $loginAthenaGroup->connection->getStatement($sql);
			$sth->execute();
			$woe = $sth->fetch();
			
			$serverStatus[$groupName][$serverName] = array(
				'loginServerUp' => $loginServerUp,
				 'charServerUp' => $athenaServer->charServer->isUp(),
				  'mapServerUp' => $athenaServer->mapServer->isUp(),
				'playersOnline' => intval($res ? $res->players_online : 0),
                  'playersPeak' => intval($peak ? $peak->users : 0),
				  'woeStatus' => intval($woe ? $woe->status : 0)
			);
		}
	}

// Account Count 
$infoStats  = array(
		'accountsStats'   => 0
);

$sql = "SELECT COUNT(account_id) AS total FROM {$server->loginDatabase}.login WHERE sex != 'S' ";
if (Flux::config('HideTempBannedStats')) {
	$sql .= "AND unban_time <= UNIX_TIMESTAMP() ";
}
if (Flux::config('HidePermBannedStats')) {
	if (Flux::config('HideTempBannedStats')) {
		$sql .= "AND state != 5 ";
	} else {
		$sql .= "AND state != 5 ";
	}
}
$sth = $server->connection->getStatement($sql);
$sth->execute();
$infoStats['accountsStats'] += $sth->fetch()->total;
?>
