<?php
// +----------------------------------------------------------------------
// | Leaps Framework [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2011-2014 Leaps Team (http://www.tintsoft.com)
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author XuTongle <xutongle@gmail.com>
// +----------------------------------------------------------------------
namespace LeapsTest;

class HttpClientTest extends \PHPUnit_Framework_TestCase
{
	public $testUrl = 'http://127.0.0.1/httpclient/tests/test.php';
	public $testUrls = [
			'http://127.0.0.1/httpclient/tests/test.php',
			'http://127.0.0.1/httpclient/tests/test2.php'
	];
	public $postVar = [
			'aaa' => 'bbb'
	];
	public $postVars = [
			[
					'vv' => 'cc'
			],
			[
					'cc' => 'ss'
			]
	];
	public function testCurlGet()
	{
		$http = new \Leaps\HttpClient\Adapter\Curl ();
		$http->get ( $this->testUrl );
	}
	public function testCurlGets()
	{
		$http = new \Leaps\HttpClient\Adapter\Curl ();
		$http->get ( $this->testUrls );
	}
	public function testFsockGet()
	{
		$http = new \Leaps\HttpClient\Adapter\Fsock ();
		$http->get ( $this->testUrl );
	}
	public function testFsockGets()
	{
		$http = new \Leaps\HttpClient\Adapter\Fsock ();
		$http->get ( $this->testUrls );
	}
	public function testCurlPost()
	{
		$http = new \Leaps\HttpClient\Adapter\Curl ();
		$http->post ( $this->testUrl, $this->postVar );
	}
	public function testCurlPosts()
	{
		$http = new \Leaps\HttpClient\Adapter\Curl ();
		$http->post ( $this->testUrls, $this->postVars );
	}
	public function testFsockPost()
	{
		$http = new \Leaps\HttpClient\Adapter\Fsock ();
		$http->get ( $this->testUrl, $this->postVar );
	}
	public function testFsockPosts()
	{
		$http = new \Leaps\HttpClient\Adapter\Fsock ();
		$http->get ( $this->testUrls, $this->postVars );
	}
}