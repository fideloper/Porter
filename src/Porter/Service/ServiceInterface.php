<?php

namespace Porter\Service;

interface ServiceInterface {

	// Set the service URL
	public function setUrl($url);

	// Get the service URL
	public function getUrl();

	// Get object ID
	public function getId();

	// Get meta data from that URL
	public function getMetadata();

	// Get service name
	public function getServiceName();

	// Return new instance of itself
	public function instance();

}