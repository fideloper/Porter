<?php

use Mockery as m;

class VimeoServiceTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testVimeoIdFound()
	{
		$vimeoUrls = array(
			'http://vimeo.com/43426940',
			'http://player.vimeo.com/video/43426940',
			'http://player.vimeo.com/video/43426940?title=0&amp;byline=0&amp;portrait=0',
			'http://vimeo.com/m/43426940',
			'http://vimeo.com/m/43426940?this=that',
		);

		$parser = $this->getParser();

		$instances = $parser->parse($vimeoUrls);

		// Do we have all our instances?
		$this->assertTrue( count($instances) === 5 );

		// Are URLs set as expected? (No parsing yet)
		$this->assertEquals( $instances[0]->getUrl(), 'http://vimeo.com/43426940' );
		$this->assertEquals( $instances[1]->getUrl(), 'http://player.vimeo.com/video/43426940' );
		$this->assertEquals( $instances[2]->getUrl(), 'http://player.vimeo.com/video/43426940?title=0&amp;byline=0&amp;portrait=0' );
		$this->assertEquals( $instances[3]->getUrl(), 'http://vimeo.com/m/43426940' );
		$this->assertEquals( $instances[4]->getUrl(), 'http://vimeo.com/m/43426940?this=that' );

		// Is ID parsed from URL correctly?
		$this->assertEquals( $instances[0]->getId(), '43426940' );
		$this->assertEquals( $instances[1]->getId(), '43426940' );
		$this->assertEquals( $instances[2]->getId(), '43426940' );
		$this->assertEquals( $instances[3]->getId(), '43426940' );
		$this->assertEquals( $instances[4]->getId(), '43426940' );

		// Is meta-data retrievable (Not testing cURL requests here)
		$curlMock = $this->getCurlMock();
		$this->assertArrayHasKey( 'id', $instances[0]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[1]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[2]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[3]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[4]->getMetadata($curlMock) );
	}

	protected function getParser()
	{
		$parser = new \Porter\Parser();
		$parser->addService( new \Porter\Service\Vimeo() );

		return $parser;
	}

	protected function getCurlMock()
	{
		$mock = m::mock('\Porter\Request\Curl');
		$mock->shouldReceive('get')->times(9)->andReturn(array(
			'id' => '43426940' // Returned array has at least an ID
		));
		return $mock;
	}

}