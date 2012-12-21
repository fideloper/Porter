<?php

namespace Porter\Service;

class Vimeo extends ServiceAbstract {

	protected $_service = 'Vimeo';

	/**
	* Parse Vimeo URLs via RegEx to get video ID
	*
	* @link    http://stackoverflow.com/questions/5087681/youtube-vimeo-video-id-from-embed-code-or-from-url-with-php-regular-expression-r
	* @return  string|bool   video ID or FALSE if no ID found
	*/
	public function getId()
	{
		if ( $this->_id !== NULL )
		{
			return $this->_id;
		}

		$url = parse_url($this->getUrl());
    	$matches = array();

		if ( $url['host'] === 'vimeo.com' || $url['host'] === 'www.vimeo.com')
		{
			preg_match('/vimeo\.com\/([0-9]*)/', $this->getUrl(), $matches);
		}
		elseif ( $url['host'] === 'player.vimeo.com' )
		{
			preg_match('/player\.vimeo\.com\/video\/([0-9]*)/', $this->getUrl(), $matches);
		}

		if ( isset($matches[0]) )
		{

			$this->_id = $matches[0];

			return $this->_id;
		}

		return FALSE;
	}

	/**
	* Parse Vimeo video to retrieve meta data
	*
	* @param    object    Request object with which to retrieve data
	* @return   object    Object of meta data
	*/
	public function getMetadata(\Porter\Request\RequestInterface $request)
	{
		if ( $this->_metaData !== NULL )
		{
			return $this->_metaData;
		}

		$this->_metaData = array('id' => $this->getId());

		// Magic Request Code
		$res = $request->get("http://vimeo.com/api/v2/video/{$this->_metaData['id']}.json");

		$this->_metaData = array_merge($this->_metaData, array(
			'title' => isset($res[0]->title) ? $res[0]->title : null,
			'description' => isset($res[0]->description) ? $res[0]->description : null,
			'duration' => isset($res[0]->duration) ? $res[0]->duration : null,
			'thumbnail_url' => isset($res[0]->thumbnail_medium) ? $res[0]->thumbnail_medium : null
		));

		return $this->_metaData;
	}

	/**
	* Return instance of itself
	*
	* @return    object    return new instance of __CLASS__
	*/
	public function instance()
	{
		return new self;
	}

}