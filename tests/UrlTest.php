<?php

class UrlTest extends PHPUnit_Framework_TestCase {

	public function testUrlsFound()
	{
		// Url in middle of content
		$urlInMiddle = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dolor lacus, gravida a auctor vel, scelerisque eget nulla. http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=share Vestibulum urna libero, dictum ut lobortis et, condimentum a nisl. Ut pharetra erat eget nulla lobortis porttitor. Praesent facilisis semper turpis eu aliquet.';

		// Url at end of content
		$urlAtEnd = 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dolor lacus, gravida a auctor vel, scelerisque eget nulla. Vestibulum urna libero, dictum ut lobortis et, condimentum a nisl. Ut pharetra erat eget nulla lobortis porttitor. Praesent facilisis semper turpis eu aliquet. http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=share';

		// Url at beginning of content
		$urlAtStart = 'http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=share Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed dolor lacus, gravida a auctor vel, scelerisque eget nulla. Vestibulum urna libero, dictum ut lobortis et, condimentum a nisl. Ut pharetra erat eget nulla lobortis porttitor. Praesent facilisis semper turpis eu aliquet.';

		// Url without protocol
		$urlNoProtocol = 'Lorem ipsum dolor sit amet, facbeook.com consectetur adipiscing elit. Sed dolor lacus, gravida a auctor vel, scelerisque eget nulla. Vestibulum urna libero, dictum ut lobortis et, condimentum a nisl. Ut pharetra erat eget nulla lobortis porttitor. Praesent facilisis semper turpis eu aliquet.';

		// Three URLs, similar format
		$urlThreeSame = 'Lorem ipsum dolor sit amet, https://www.facebook.com/ consectetur adipiscing elit. Sed dolor lacus, gravida a auctor vel, scelerisque eget nulla. Vestibulum urna libero, dictum ut lobortis et, condimentum a nisl. https://www.google.com/ Ut pharetra erat eget nulla lobortis porttitor. Praesent facilisis semper turpis eu aliquet. http://www.yahoo.com/';

		// Three URLs, similar format
		$urlThreeMixed = 'Lorem ipsum dolor sit amet, facebook.com consectetur adipiscing elit. Sed dolor lacus, gravida a auctor vel, scelerisque eget nulla. Vestibulum urna libero, dictum ut lobortis et, condimentum a nisl. https://www.google.com/ Ut pharetra erat eget nulla lobortis porttitor. Praesent facilisis semper turpis eu aliquet. www.yahoo.com/';

		$parser = $this->getParser();

		$result_urlInMiddle = $parser->matchUrls($urlInMiddle);
		$this->assertTrue( count($result_urlInMiddle) === 1 );
		$this->assertEquals( $result_urlInMiddle[0], 'http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=share' );

		$result_urlAtEnd = $parser->matchUrls($urlAtEnd);
		$this->assertTrue( count($result_urlAtEnd) === 1 );
		$this->assertEquals( $result_urlAtEnd[0], 'http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=share' );

		$result_urlAtStart = $parser->matchUrls($urlAtStart);
		$this->assertTrue( count($result_urlAtStart) === 1 );
		$this->assertEquals( $result_urlAtStart[0], 'http://www.youtube.com/watch?v=FL8aeeSTthQ&feature=share' );

		/* This doesn't parse protocol-less URLs!
		$result_urlNoProtocol = $parser->matchUrls($urlNoProtocol);
		$this->assertTrue( count($result_urlNoProtocol) === 1 );
		$this->assertTrue( $result_urlNoProtocol[0] ===  'facbeook.com' );
		*/

		$result_urlThreeSame = $parser->matchUrls($urlThreeSame);
		$this->assertTrue( count($result_urlThreeSame) === 3 );
		$this->assertEquals( $result_urlThreeSame[0], 'https://www.facebook.com/' );
		$this->assertEquals( $result_urlThreeSame[1], 'https://www.google.com/' );
		$this->assertEquals( $result_urlThreeSame[2], 'http://www.yahoo.com/' );

		/*
			- Nake URLs fail here too, but subdomain naked URLs work
			- Note that only "www" subdomains without protocol will be picked up. 'm.youtube.com/1234' would not be picked up
		*/
		$result_urlThreeMixed = $parser->matchUrls($urlThreeMixed);
		$this->assertTrue( count($result_urlThreeMixed) === 2 );
		//$this->assertTrue( $result_urlThreeMixed[0] ===  'facebook.com' ); # No "naked" urls :/
		$this->assertEquals( $result_urlThreeMixed[0], 'https://www.google.com/' );
		$this->assertEquals( $result_urlThreeMixed[1], 'www.yahoo.com/' );
	}

	protected function getParser()
	{
		return new \Porter\Parser();
	}

}
