<?php

namespace Framework\Remote;

abstract class Api
{
	private static $_curlMulti = null;
	private static $_queuedHandles = array();
	
	private static function _getCurlMulti()
	{
		if (self::$_curlMulti === null) {
			self::$_curlMulti = curl_multi_init();
		}
		
		return self::$_curlMulti;
	}
	
	private function _initCurl($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		
		return $curl;
	}
	
	protected function curl($url)
	{
		$curl = $this->_initCurl($url);
		$response = curl_exec($curl);
		
		if ($response === false) {
			
			throw new CurlException(curl_error($curl), curl_errno($curl));
		}
		
		curl_close($curl);
		
		return $response;
	}
	
	protected function queueCurl($url, $handleId)
	{
		$curl = $this->_initCurl($url);
		
		curl_multi_add_handle(
			self::_getCurlMulti(), 
			$curl
		);
		
		self::$_queuedHandles[$handleId] = $curl;
	}
	
	protected function getHandleContent($handleId)
	{
		return curl_multi_getcontent(self::$_queuedHandles[$handleId]);
	}
	
	public static function runQueue()
	{
		$active = null;
		$multiHandle = self::_getCurlMulti();
		
		do {
			
			$multiExecResult = curl_multi_exec($multiHandle, $active);
		
		} while ($multiExecResult === CURLM_CALL_MULTI_PERFORM);
		
		while ($active && $multiExecResult === CURLM_OK) {
			
			if (curl_multi_select($multiHandle) != -1) {
				
				do {
					
					$multiExecResult = curl_multi_exec($multiHandle, $active);
				
				} while ($multiExecResult === CURLM_CALL_MULTI_PERFORM);
			}
		}
		
		foreach (self::$_queuedHandles as $queuedHandle) {
			curl_multi_remove_handle($multiHandle, $queuedHandle);
		}
		
		curl_multi_close($multiHandle);
		self::$_curlMulti = null;
	}
}