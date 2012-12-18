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
	public function addService( Porter\Service\ServiceInterface $service )
	{
		$_services[] = $service;

		return $this;
	}

	public function parse(array $urls)
	{
		foreach ( $urls as $url )
		{
			foreach ( $this->_services as $service )
			{
				// No parsing done at this point still
				$service->instance()->setUrl($url);
			}
		}
	}

	/**
	* Match URLs within given input
	* TO DO: Abstract this out (To a helper? Separate library? Filter?)
	*
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

}