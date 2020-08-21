<?php

namespace JsonParser\Loader;

use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use JsonParser\Exceptions\UnavailableUrlException;
use Throwable;

class UrlLoader implements ILoader
{
	/**
	 * @var string
	 */
	private $url;

	/** @var ClientInterface */
	private $client;

	/**
	 * @param string $url
	 * @param ClientInterface|null $client
	 */
	public function __construct(string $url, ClientInterface $client = null)
	{
		$this->url = $url;
		$this->client = $client ?? new Client();
	}

	/**
	 * {@inheritDoc}
	 * @throws UnavailableUrlException
	 */
	public function load(): ?string
	{
		$options = [
			RequestOptions::CONNECT_TIMEOUT => 5,
			RequestOptions::TIMEOUT => 5,
			RequestOptions::HEADERS => [
				'Accept' => 'application/json',
			],
		];

		try {
			$response = $this->client->request('GET', $this->url, $options);
		} catch (Throwable $e) {
			throw new UnavailableUrlException(sprintf('Адрес %s недоступен', $this->url));
		}

		return $response->getBody()->getContents();
	}
}
