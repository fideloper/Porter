<?php

namespace Porter\Service;

abstract class ServiceAbstract implements ServiceInterface {

	protected $_url;
	protected $_id;
	protected $_metaData;
	protected $_service;
	protected $_hosts = array();

	/**
	* Optionally set URL
	*
	* @param    string    Url of service
	*/
	public function __construct($url=NULL)
	{
		if ( $url !== NULL )
		{
			$this->setUrl($url);
		}
	}

	/**
	* Set the service URL
	*
	* @param    string    Url of service
	*/
	public function setUrl($url)
	{
		$this->_url = $url;
		return $this;
	}

	/**
	* Get the service URL
	*
	* @return    string    Url of service
	*/
	public function getUrl()
	{
		if ( $this->_url === NULL || $this->_url === '' || $this->_url === FALSE )
		{
			$this->_url = '';
		}
		return $this->_url;
	}

	/**
	* Get ID, usually available via the URL
	* Meant to be overridden
	*
	* @return    mixed    ID of the URL
	*/
	public function getId()
	{
		$this->_id = $this->getUrl();
		return $this->_id;
	}

	/**
	* Get meta data from URL or service ID
	* Meant to be overridden
	*
	* @return    mixed    Meta-data of service
	*/
	public function getMetadata(\Porter\Request\RequestInterface $request)
	{
		$this->_metaData = $this->getUrl();
		return $this->_metaData;
	}

	/**
	* Get human-readale name of the service
	*
	* @return    string    Name of service
	*/
	public function getServiceName()
	{
		return $this->_service;
	}

	/**
	* Test is supplied URL matches this service
	*
	* @param string    URL to test
	* @return boolean  TRUE if matches this service, else FALSE
	*/
	public function matchesHost($url)
	{
		$parts = parse_url($url);

		if ( isset($parts['host']) )
		{
			return in_array($parts['host'], $this->_hosts);
		}

		return FALSE;
	}

	/**
	* Return instance of itself
	* PHP demands this be in every class as well. Which is dumb.
	*
	* @return    object    return new instance of __CLASS__
	*/
	public function instance()
	{
		return new self;
	}

}