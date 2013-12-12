<?php

namespace Framework\Remote;

abstract class Api
{
	private static $_curlMulti = null;
	private static $_queuedHandles = array();
	private static $_sharedCurl = null;
	private $_lastResponseCode = null;
	
	private static function _getCurlMulti()
	{
		if (self::$_curlMulti === null) {
			self::$_curlMulti = curl_multi_init();
		}
		
		return self::$_curlMulti;
	}
	
	private function _initCurl()
	{
		if (self::$_sharedCurl === null) {
			
			self::$_sharedCurl = curl_init();
			curl_setopt(self::$_sharedCurl, CURLOPT_RETURNTRANSFER, true);
			curl_setopt(self::$_sharedCurl, CURLOPT_HEADER, false);
			curl_setopt(self::$_sharedCurl, CURLOPT_SSL_VERIFYPEER, true);
		}
		
		return self::$_sharedCurl;
	}
	
	protected function curl($url, array $options = array())
	{
		// serial requests can reuse the handle
		$curl = $this->_initCurl();
		curl_setopt($curl, CURLOPT_URL, $url);
		
		foreach ($options as $option => $value) {
			curl_setopt($curl, $option, $value);
		}
		
		$response = curl_exec($curl);
		
		if ($response === false) {
			
			throw new CurlException(curl_error($curl), curl_errno($curl));
		}
		
		$responseCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$this->_lastResponseCode = $responseCode;
		
		// not calling curl_close so we can reuse the handle
		
		return $response;
	}
	
	protected function queueCurl($url, $handleId)
	{
		// each request in the queue gets its own resource
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
		
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
	
	public function getLastResponseCode()
	{
		return $this->_lastResponseCode;
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
	
	public function post($url, array $parameters)
	{
		return $this->curl(
			$url, 
			array(
				CURLOPT_POST => count($parameters), 
				CURLOPT_POSTFIELDS => http_build_query($parameters)
			)
		);
	}
}