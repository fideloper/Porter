<?php

use Mockery as m;

class YoutubeServiceTest extends PHPUnit_Framework_TestCase {

	public function tearDown()
	{
		m::close();
	}

	public function testYoutubeIdFound()
	{
		$youtubeUrls = array(
			'http://www.youtube.com/watch?v=FL8aeeSTthQ',
			'http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=share',
			'http://www.youtube.com/embed/FL8aeeSTthQ',
			'http://www.youtube.com/embed/FL8aeeSTthQ?hl=en_US',
			'http://www.youtu.be/FL8aeeSTthQ',
			'http://www.youtu.be/FL8aeeSTthQ?hl=en_US',
			'http://m.youtube.com/watch?v=FL8aeeSTthQ&feature=share',
			'www.youtube.com/watch?v=FL8aeeSTthQ',
			'youtube.com/embed/FL8aeeSTthQ',
		);

		$parser = $this->getParser();

		$instances = $parser->parseMany($youtubeUrls);

		// Do we have all our instances?
		$this->assertTrue( count($instances) === 9 );

		// Are URLs set as expected? (No parsing yet)
		$this->assertEquals( $instances[0]->getUrl(), 'http://www.youtube.com/watch?v=FL8aeeSTthQ' );
		$this->assertEquals( $instances[1]->getUrl(), 'http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=share' );
		$this->assertEquals( $instances[2]->getUrl(), 'http://www.youtube.com/embed/FL8aeeSTthQ' );
		$this->assertEquals( $instances[3]->getUrl(), 'http://www.youtube.com/embed/FL8aeeSTthQ?hl=en_US' );
		$this->assertEquals( $instances[4]->getUrl(), 'http://www.youtu.be/FL8aeeSTthQ' );
		$this->assertEquals( $instances[5]->getUrl(), 'http://www.youtu.be/FL8aeeSTthQ?hl=en_US' );
		$this->assertEquals( $instances[6]->getUrl(), 'http://m.youtube.com/watch?v=FL8aeeSTthQ&feature=share' );
		$this->assertEquals( $instances[7]->getUrl(), 'http://www.youtube.com/watch?v=FL8aeeSTthQ' );
		$this->assertEquals( $instances[8]->getUrl(), 'http://youtube.com/embed/FL8aeeSTthQ' );

		// Is ID parsed from URL correctly?
		$this->assertEquals( $instances[0]->getId(), 'FL8aeeSTthQ' );
		$this->assertEquals( $instances[1]->getId(), 'FL8aeeSTthQ' );
		$this->assertEquals( $instances[2]->getId(), 'FL8aeeSTthQ' );
		$this->assertEquals( $instances[3]->getId(), 'FL8aeeSTthQ' );
		$this->assertEquals( $instances[4]->getId(), 'FL8aeeSTthQ' );
		$this->assertEquals( $instances[5]->getId(), 'FL8aeeSTthQ' );
		$this->assertEquals( $instances[6]->getId(), 'FL8aeeSTthQ' );
		$this->assertEquals( $instances[7]->getId(), 'FL8aeeSTthQ' );
		$this->assertEquals( $instances[8]->getId(), 'FL8aeeSTthQ' );

		// Is meta-data retrievable (Not testing cURL requests here)
		$curlMock = $this->getCurlMock();
		$this->assertArrayHasKey( 'id', $instances[0]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[1]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[2]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[3]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[4]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[5]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[6]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[7]->getMetadata($curlMock) );
		$this->assertArrayHasKey( 'id', $instances[8]->getMetadata($curlMock) );
	}

	protected function getParser()
	{
		$parser = new \Porter\Parser();
		$parser->addService( new \Porter\Service\Youtube() );

		return $parser;
	}

	protected function getCurlMock()
	{
		$mock = m::mock('\Porter\Request\Curl');
		$mock->shouldReceive('get')->times(9)->andReturn(array(
			'id' => 'FL8aeeSTthQ' // Returned array has at least an ID
		));
		return $mock;
	}

}