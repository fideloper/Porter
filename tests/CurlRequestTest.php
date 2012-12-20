<?php

use Mockery as m;

class CurlRequestTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testSuccessCurlRequest()
	{
		$mock = $this->getCurlMock();

		$curl = new Porter\Request\Curl($mock);

		$data = $curl->get('http://gdata.youtube.com/feeds/api/videos/FL8aeeSTthQ?alt=json');

		$this->assertInstanceOf('stdClass', $data);
	}

	public function testFailureCurlRequest()
	{
		$mock = $this->getCurlMock();

		$curl = new Porter\Request\Curl($mock);

		$data = $curl->get('this is not a url');

		$this->assertFalse( $data );
	}

	public function test404CurlRequest()
	{
		$mock = $this->getCurlMock();

		$curl = new Porter\Request\Curl($mock);

		$data = $curl->get('http://thisdoesnotexist.co.uk');

		$this->assertFalse( $data );
	}

	protected function getCurlMock()
	{
		// Not actually mocking anymore, because this uses reflection in its magic methods for some reason
		return new Shuber\Curl\Curl();

		$mock = m::mock('Shuber\Curl\Curl');
		$mock->shouldReceive('get')->times(1)->andReturn('{"version":"1.0","encoding":"UTF-8","entry":{"xmlns":"http://www.w3.org/2005/Atom","xmlns$media":"http://search.yahoo.com/mrss/","xmlns$gd":"http://schemas.google.com/g/2005","xmlns$yt":"http://gdata.youtube.com/schemas/2007","id":{"$t":"http://gdata.youtube.com/feeds/api/videos/FL8aeeSTthQ"},"published":{"$t":"2006-03-23T07:41:56.000Z"},"updated":{"$t":"2012-12-20T15:53:25.000Z"},"category":[{"scheme":"http://schemas.google.com/g/2005#kind","term":"http://gdata.youtube.com/schemas/2007#video"},{"scheme":"http://gdata.youtube.com/schemas/2007/categories.cat","term":"Entertainment","label":"Entertainment"}],"title":{"$t":"Eric Johnson - Manhattan G3","type":"text"},"content":{"$t":"G3 1996. Eric Johnson playing Manhattan.","type":"text"},"link":[{"rel":"alternate","type":"text/html","href":"http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=youtube_gdata"},{"rel":"http://gdata.youtube.com/schemas/2007#video.responses","type":"application/atom+xml","href":"http://gdata.youtube.com/feeds/api/videos/FL8aeeSTthQ/responses"},{"rel":"http://gdata.youtube.com/schemas/2007#video.related","type":"application/atom+xml","href":"http://gdata.youtube.com/feeds/api/videos/FL8aeeSTthQ/related"},{"rel":"http://gdata.youtube.com/schemas/2007#mobile","type":"text/html","href":"http://m.youtube.com/details?v=FL8aeeSTthQ"},{"rel":"self","type":"application/atom+xml","href":"http://gdata.youtube.com/feeds/api/videos/FL8aeeSTthQ"}],"author":[{"name":{"$t":"jonyq"},"uri":{"$t":"http://gdata.youtube.com/feeds/api/users/jonyq"}}],"gd$comments":{"gd$feedLink":{"rel":"http://gdata.youtube.com/schemas/2007#comments","href":"http://gdata.youtube.com/feeds/api/videos/FL8aeeSTthQ/comments","countHint":3645}},"media$group":{"media$category":[{"$t":"Entertainment","label":"Entertainment","scheme":"http://gdata.youtube.com/schemas/2007/categories.cat"}],"media$content":[{"url":"http://www.youtube.com/v/FL8aeeSTthQ?version=3&f=videos&app=youtube_gdata","type":"application/x-shockwave-flash","medium":"video","isDefault":"true","expression":"full","duration":312,"yt$format":5},{"url":"rtsp://v6.cache2.c.youtube.com/CiILENy73wIaGQkUtpPkeRq_FBMYDSANFEgGUgZ2aWRlb3MM/0/0/0/video.3gp","type":"video/3gpp","medium":"video","expression":"full","duration":312,"yt$format":1},{"url":"rtsp://v6.cache2.c.youtube.com/CiILENy73wIaGQkUtpPkeRq_FBMYESARFEgGUgZ2aWRlb3MM/0/0/0/video.3gp","type":"video/3gpp","medium":"video","expression":"full","duration":312,"yt$format":6}],"media$description":{"$t":"G3 1996. Eric Johnson playing Manhattan.","type":"plain"},"media$keywords":{},"media$player":[{"url":"http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=youtube_gdata_player"}],"media$restriction":{"$t":"DE","type":"country","relationship":"deny"},"media$thumbnail":[{"url":"http://i.ytimg.com/vi/FL8aeeSTthQ/0.jpg","height":360,"width":480,"time":"00:02:36"},{"url":"http://i.ytimg.com/vi/FL8aeeSTthQ/1.jpg","height":90,"width":120,"time":"00:01:18"},{"url":"http://i.ytimg.com/vi/FL8aeeSTthQ/2.jpg","height":90,"width":120,"time":"00:02:36"},{"url":"http://i.ytimg.com/vi/FL8aeeSTthQ/3.jpg","height":90,"width":120,"time":"00:03:54"}],"media$title":{"$t":"Eric Johnson - Manhattan G3","type":"plain"},"yt$duration":{"seconds":"312"}},"gd$rating":{"average":4.927538,"max":5,"min":1,"numRaters":7673,"rel":"http://schemas.google.com/g/2005#overall"},"yt$statistics":{"favoriteCount":"0","viewCount":"2844344"}}}');
		return $mock;
	}

	protected function getParser()
	{
		$parser = new \Porter\Parser();
		$parser->addService( new \Porter\Service\Youtube() );

		return $parser;
	}

}