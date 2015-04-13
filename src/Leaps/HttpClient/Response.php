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
namespace Leaps\HttpClient;

class Response
{
	/**
	 * 原始的相应头
	 *
	 * @var string
	 */
	protected $rawHeaders;

	/**
	 * 解析后的Header集合
	 *
	 * @var array
	 */
	protected $headers = [ ];

	/**
	 * 响应状态码
	 *
	 * @var int
	 */
	protected $statusCode = 0;

	/**
	 * 响应内容
	 *
	 * @var string
	 */
	protected $content;

	/**
	 * 响应的内容类型
	 *
	 * @var string
	 */
	protected $contentType;

	/**
	 * Cookie集合
	 *
	 * @var array
	 */
	protected $cookies = [ ];

	/**
	 * 使用时间单位秒
	 *
	 * @var float
	 */
	protected $time = 0;

	/**
	 * 构造方法
	 *
	 * @param array $data
	 */
	public function __construct(array $response)
	{
		if (isset ( $response ['code'] )) {
			$this->statusCode = $response ['code'];
		}
		if (isset ( $response ['time'] )) {
			$this->time = $response ['time'];
		}
		if (isset ( $response ['data'] )) {
			$this->content = $response ['data'];
		}
		if (isset ( $response ['rawHeader'] )) {
			$this->rawHeaders = $response ['rawHeader'];
		}

		if (isset ( $response ['header'] ) && is_array ( $response ['header'] )) {
			foreach ( $response ['header'] as $item ) {
				if (empty ( $item ))
					continue;
				if (strpos ( $item, ':' ) !== false) {
					list ( $key, $value ) = explode ( ': ', $item, 2 );
					if ($key == 'Set-Cookie') { // Cookie 特殊处理
						$this->headers [$key] [] = $value;
						$cookie = $this->resolveCookie ( $value );
						$this->cookies [$cookie ['name']] = $cookie ['value'];
					} else {
						$this->headers [$key] = $value;
						if ($key == 'Content-Type') {
							if (($pos = strpos ( $value, ';' )) !== false) {
								$this->contentType = substr ( $value, 0, $pos );
							} else {
								$this->contentType = $value;
							}
						}
					}
				} else {
					$this->headers [] = $item;
				}
			}
		}
	}

	/**
	 * 获取响应的文档类型
	 *
	 * @return string
	 */
	public function getContentType()
	{
		return $this->contentType;
	}

	/**
	 * 获取内容后缀
	 */
	public function getContentSuffix()
	{
		return  MimeType::getSuffix($this->contentType);
	}

	/**
	 * 是否是有效的响应码
	 *
	 * @return boolean
	 */
	public function getIsInvalid()
	{
		return $this->getStatusCode () < 100 || $this->getStatusCode () >= 600;
	}

	/**
	 * 是否是成功的响应
	 *
	 * @return boolean
	 */
	public function getIsSuccessful()
	{
		return $this->getStatusCode () >= 200 && $this->getStatusCode () < 300;
	}

	/**
	 * 是否是重定向响应
	 *
	 * @return boolean
	 */
	public function getIsRedirection()
	{
		return $this->getStatusCode () >= 300 && $this->getStatusCode () < 400;
	}

	/**
	 * 是否请求客户端错误
	 *
	 * @return boolean
	 */
	public function getIsClientError()
	{
		return $this->getStatusCode () >= 400 && $this->getStatusCode () < 500;
	}

	/**
	 * 服务端是否发生错误
	 *
	 * @return boolean
	 */
	public function getIsServerError()
	{
		return $this->getStatusCode () >= 500 && $this->getStatusCode () < 600;
	}

	/**
	 * 是否响应成功
	 *
	 * @return boolean
	 */
	public function getIsOk()
	{
		return $this->getStatusCode () == 200;
	}

	/**
	 * 是否是403
	 *
	 * @return boolean
	 */
	public function getIsForbidden()
	{
		return $this->getStatusCode () == 403;
	}

	/**
	 * 是否是404
	 *
	 * @return boolean
	 */
	public function getIsNotFound()
	{
		return $this->getStatusCode () == 404;
	}

	/**
	 * 是否是空响应
	 *
	 * @return boolean
	 */
	public function getIsEmpty()
	{
		return in_array ( $this->getStatusCode (), [
				201,
				204,
				304
		] );
	}

	/**
	 * 获取响应状态码
	 *
	 * @return int
	 */
	public function getStatusCode()
	{
		return $this->statusCode;
	}

	/**
	 * 获取原始的响应头
	 *
	 * @return string
	 */
	public function getRawHeader()
	{
		return $this->rawHeaders;
	}

	/**
	 * 获取Header集合
	 *
	 * @param string $key
	 * @return array
	 */
	public function getHeaders()
	{
		return $this->headers;
	}

	/**
	 * 获取Header
	 *
	 * @param string $key
	 * @return array
	 */
	public function getHeader($name)
	{
		if (isset ( $this->headers [$name] )) {
			return $this->headers [$name];
		}
		return false;
	}

	/**
	 * 获取Cookie集合
	 *
	 * @param string $key
	 * @return multitype:
	 */
	public function getCookies($key = null)
	{
		return $this->cookies;
	}

	/**
	 * 获取Cookie内容
	 *
	 * @param string $key
	 * @return multitype:
	 */
	public function getCookie($key)
	{
		if (isset ( $this->cookies [$key] )) {
			return $this->cookies [$key];
		}
		return false;
	}

	/**
	 * 获取请求消耗时间
	 *
	 * @return number
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * 获取响应内容
	 *
	 * @return string
	 */
	public function getContent($format = true)
	{
		return $this->content;
	}

	/**
	 * 解析Cookie字符串
	 *
	 * @param string $cookie
	 */
	private function resolveCookie($cookie)
	{
		if (($pos = strpos ( $cookie, ';' )) !== false) {
			$item = [ ];
			list ( $item ['name'], $item ['value'] ) = explode ( '=', substr ( $cookie, 0, $pos ), 2 );
			return $item;
		}
		return false;
	}

	/**
	 * 魔术方法，输出内容
	 *
	 * @return string
	 */
	public function __toString()
	{
		return ( string ) $this->getContent ();
	}
}