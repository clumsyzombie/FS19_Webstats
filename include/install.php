<?php
/**
 *
 * This file is part of the "FS19 Web Stats" package.
 * Copyright (C) 2017-2018 John Hawk <john.hawk@gmx.net>
 *
 * "FS19 Web Stats" is free software: you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * "FS19 Web Stats" is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */
if (! defined ( 'IN_FS19WS' ) && ! defined ( 'IN_INSTALL' )) {
	exit ();
}

$smarty->assign ( 'maps', getMaps () );
$smarty->assign ( 'languages', getLanguages () );
$smarty->assign ( 'styles', $styles );

$error = $success = false;
$mode = GetParam ( 'mode', 'G', 'start' );
$smarty->assign ( 'mode', $mode );
if ($_SERVER ['REQUEST_METHOD'] == 'POST') {
	$submit = GetParam ( 'submit' );
	if ($submit == 'language') {
		$_SESSION ['language'] = $options ['general'] ['language'] = GetParam ( 'language' );
		setcookie ( 'fs19webstats', json_encode ( $options ), time () + 31536000 );
	} elseif ($submit == 'style') {
		$style = $options ['general'] ['style'] = GetParam ( 'style', 'P', 'fs17' );
		setcookie ( 'fs19webstats', json_encode ( $options ), time () + 31536000 );
	} elseif ($submit == 'gameSettings') {
		$config = array (
				'adminPass' => GetParam ( 'adminpass1', 'P', '' ),
				'map' => GetParam ( 'map', 'P' ) 
		);
		$repeatedPassword = GetParam ( 'adminpass2', 'P', '' );
		switch ($mode) {
			case 'api' :
				$config += array (
						'configType' => 'api',
						'serverIp' => GetParam ( 'serverip', 'P' ),
						'serverPort' => intval ( GetParam ( 'serverport', 'P' ) ),
						'serverCode' => GetParam ( 'servercode', 'P', '' ) 
				);
				if (filter_var ( $config ['serverIp'], FILTER_VALIDATE_IP ) === false) {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_IP##</div>';
				}
				if ($config ['serverPort'] < 1 || $config ['serverPort'] > 65536) {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_PORT##</div>';
				}
				if (strlen ( $config ['serverCode'] ) < 1) {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_CODE##</div>';
				}
				if (! $error) {
					if (! checkConnectionAPI ( $config ['serverIp'], $config ['serverPort'], $config ['serverCode'] )) {
						$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_CODE##</div>';
					}
				}
				break;
			case 'ftp' :
				//http://php.net/manual/de/book.ftp.php
				break;
			case 'local' :
				$config += array (
						'configType' => 'local',
						'path' => GetParam ( 'savepath', 'P', '' ) . DIRECTORY_SEPARATOR 
				);
				if (! file_exists ( $config ['path'] . 'careerSavegame.xml' )) {
					$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_SAVEGAME##</div>';
				}
				break;
		}
		if (! file_exists ( "./config/" . $config ['map'] )) {
			$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##ERROR_MAP##</div>';
		}
		if ($config ['adminPass'] != $repeatedPassword) {
			$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##PASSWORD_MATCH##</div>';
		}
		if (strlen ( $config ['adminPass'] ) < 6) {
			$error .= '<div class="alert alert-danger"><strong>##ERROR##</strong> ##PASSWORD_SHORT##</div>';
		}
		if (! $error) {
			$config ['adminPass'] = password_hash ( $config ['adminPass'], PASSWORD_DEFAULT );
			$fp = fopen ( './config/server.conf', 'w' );
			fwrite ( $fp, serialize ( $config ) );
			fclose ( $fp );
			$success = true;
		}
	}
}
$smarty->setTemplateDir ( "./styles/$style/templates" );
$smarty->assign ( 'style', $style );
$smarty->assign ( 'fsockopen', function_exists ( 'fsockopen' ) );
$smarty->assign ( 'fgetcontent', function_exists ( 'file_get_contents' ) );
$smarty->assign ( 'postdata', isset ( $config ) ? $config : array () );
$smarty->assign ( 'error', $error );
$smarty->assign ( 'success', $success );
$tpl_source = $smarty->fetch ( 'install.tpl' );
echo preg_replace_callback ( '/##(.+?)##/', 'prefilter_i18n', $tpl_source );
function checkConnectionAPI($serverIp, $serverPort, $serverCode) {
	error_reporting ( E_NOTICE );
	$fp = fsockopen ( $serverIp, $serverPort, $errno, $errstr, 4 );
	error_reporting ( E_ALL );
	if ($fp) {
		$out = "GET /feed/dedicated-server-stats.xml?code=" . $serverCode . " HTTP/1.0\r\n";
		$out .= "Host: " . $serverIp . "\r\n";
		$out .= "Connection: Close\r\n\r\n";
		fwrite ( $fp, $out );
		$resp = "";
		while ( ! feof ( $fp ) ) {
			$resp .= fgets ( $fp, 256 );
		}
		fclose ( $fp );
		if (preg_match ( "/HTTP\/1\.\d\s(\d+)/", $resp, $matches ) && $matches [1] == 200) {
			return true;
		}
	}
	return false;
}