<?php

namespace Porter\Service;

class Youtube extends ServiceAbstract {

	protected $_service = 'Youtube';

	/**
	* Parse Youtube URLs via RegEx to get video ID
	*
	* @link    http://stackoverflow.com/questions/3392993/php-regex-to-get-youtube-video-id
	* @link    http://rubular.com/r/M9PJYcQxRW
	* @return  string|bool   video ID or FALSE if no ID found
	*/
	public function getId()
	{
		if ( $this->_id !== NULL )
		{
			return $this->_id;
		}

		$matches = array();
		preg_match('/(?<=(?:v|i)=)[a-zA-Z0-9-]+(?=&)|(?<=(?:v|i)\/)[^&\n]+|(?<=embed\/)[^"&\n]+|(?<=(?:v|i)=)[^&\n]+|(?<=youtu.be\/)[^&\n]+/', $this->getUrl(), $matches);

		if ( isset($matches[0]) )
		{
			$this->_id = $matches[0];

			return $this->_id;
		}

		return FALSE;
	}

	/**
	* Parse Youtube video to retrieve meta data
	*
	* @param    object    Request object with which to retrieve data
	* @return   object    Object of meta data
	*/
	public function getMetadata(Porter\Request\RequestInterface $request)
	{
		if ( $this->_metaData !== NULL )
		{
			return $this->_metaData;
		}

		$this->_metaData = array('id' => $this->getId());

		// Magic Request Code
		$res = $request->get("http://gdata.youtube.com/feeds/api/videos/{$meta_data['embed_url_id']}?alt=json");

		$this->_metaData = array_merge($meta_data, array(
			'title' => isset($res->entry->title->{'$t'}) ?
					   $res->entry->title->{'$t'} : null,

			'description' => isset($res->entry->content->{'$t'}) ?
							 $res->entry->content->{'$t'} : null,

			'duration' => isset($res->entry->{'media$group'}->{'media$content'}[0]->duration) ?
						  $res->entry->{'media$group'}->{'media$content'}[0]->duration : null,

			'thumbnail_url' => isset($res->entry->{'media$group'}->{'media$thumbnail'}[0]->url) ?
							   $res->entry->{'media$group'}->{'media$thumbnail'}[0]->url : null
		));

		return $this->_metaData;
	}

}