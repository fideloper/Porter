<?php

namespace Porter\Request;

class Curl implements RequestInterface {

	private $_curl;
	private $_response;

	public function __construct(\Shuber\Curl\Curl $curl)
	{
		$this->_curl = $curl;
	}

	/**
	* Retrieve Youtube meta data
	*
	* @param    string         Url to use to get meta data
	* @return   object|bool    Service meta data or FALSE
	*/
	public function get($url)
	{
		try
		{
			$this->_response = $this->_curl->get($url);

			return json_encode($this->_response->body);

		} catch ( \Shuber\Curl\CurlException )
		{
			return FALSE;
		}

	}

	public function getResponse()
	{
		return $this->_response;
	}

}