<?php

namespace Porter;

class Parser {

	private $_services = array();

	const MATCH_URL = '(?xi)(?:\b|https?://)((?:www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`\!()\[\]{};:\'".,<>?«»“”‘’]))';


	/**
	* Add service to test input against
	*
	* @param    object    implementation of Porter\Service\ServiceInterface
	* @return   object    for chainability, current instance of Porter\Parser
	*/
	public function addService( \Porter\Service\ServiceInterface $service )
	{
		$this->_services[] = $service;

		return $this;
	}

	/**
	* Parse a collection of URLs by each registered service
	*
	* @param    array    array of URLs to parse
	* @return   array    collection of Service objects with parsed URLs
	*/
	public function parseMany(array $urls)
	{
		$urlCollection = array();

		foreach ( $urls as $url )
		{
			$urlCollection[] = $this->parseOne($url);
		}

		return $urlCollection;
	}

	/**
	* Parse a URL by each registered service
	*
	* @param    string    URL to parse
	* @return   object    Service object with parsed id
	*/
	public function parseOne($url)
	{

		$instances = array();

		$service = $this->matchService($url);

		if ( $service instanceof \Porter\Service\ServiceInterface )
		{
			// No parsing done at this point yet
			return $service->instance()->setUrl($url);
		}

		return FALSE;
	}

	/**
	* Match a service to given URL
	*
	* @param string    Url to match against
	* @return object|bool   Matched service, or FALSE if no match
	*/
	public function matchService($url)
	{
		foreach ( $this->_services as $service )
		{
			if ( $service->matchesHost( $this->normalizeUrl($url) ) )
			{
				return $service;
			}
		}
		return FALSE;
	}


	/**
	* Match URLs within given input
	*
	* @todo     Abstract this out (To a helper? Separate library? Filter?)
	* @param    string    text input
	* @return   array     url matches, if any
	*/
	public function matchUrls($input)
	{
		$matches = array();
		$final = array();

		preg_match_all("!".self::MATCH_URL."!i", $input, $matches);

		foreach ( $matches[0] as $url )
		{
			$final[] = $url;
		}

		return $final;
    }

    /**
    * Normalize multiple URLs
    *
    * @param array    Array of URLs to normalize
    * @return array   Array of normalized URLs
    */
    public function normalizeMany(array $urls)
    {
    	array_walk($urls, array($this, 'normalizeUrl'));

    	return $urls;
    }

    /**
    * Ensure 'http://' or 'https://' exists in URLs
    *
    * @param string    URL to normalize
    * @return string   Normalized URL
    */
    public function normalizeUrl($url)
    {
    	// Incase someone uses //example.com. Assumes no ssl.
		if ( strpos($url, '//') === 0 )
		{
			$url = 'http:' . $url;
		}

		// Add protocol if not present. Assumes no ssl.
		if( strpos($url, 'http://') === FALSE && strpos($url, 'https://') === FALSE )
		{
			$url = 'http://' . $url;
		}

		return $url;

    }
}