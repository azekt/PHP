<?php
/** Author: azekt
 * azariaszt@gmail.com
 */
class CurlException extends Exception { }
class CurlRequest
{
	private $url;
	private $method;
	private $returnHeader;
	private $headers;
	private $post;
	private $outputFile;
			
	public function __construct($settings)
	{
		if (empty($settings['url'])) {
			throw new CurlException('URL address missing!');
		}
		$this->url = $settings['url'];
		$this->method = $settings['method'] ?? null;
		$this->headers = $settings['headers'] ?? null;
		$this->returnHeader = $settings['returnHeader'] ?? false;
		$this->post = $settings['post'] ?? null;
		$this->outputFile = $settings['outputFile'] ?? null;
	}

	public function get()
	{
		$ch = curl_init();
		if ($ch === false) {
			throw new CurlException('failed to initialize');
		}
		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:75.0) Gecko/20100101 Firefox/75.0');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, $this->returnHeader);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 200);
		curl_setopt($ch, CURLOPT_TIMEOUT, 200);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
		if (!empty($this->method)) {
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->method);
		}
		if (!empty($this->headers)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $this->headers);
		}
		if (!empty($this->post)) {
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->post);
		}
		if (!empty($this->outputFile)) {
			curl_setopt($ch, CURLOPT_FILE, $this->outputFile);
		}
		$content = curl_exec($ch);
		curl_close($ch);
		if ($content === false) {
			throw new CurlException(curl_error($ch), curl_errno($ch));
		}
		unset($ch);
		return $content;
	}
}
