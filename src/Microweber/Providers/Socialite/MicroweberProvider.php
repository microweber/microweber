<?php namespace Microweber\Providers\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MicroweberProvider extends AbstractProvider implements ProviderInterface
{
	/**
	 * The base API URL.
	 *
	 * @var string
	 */
	protected $serverUrl = 'http://login.dev';

	protected function apiUrl($path) {
		return $this->serverUrl . '/api/v1' . $path;
	}

	/**
	 * The scopes being requested.
	 *
	 * @var array
	 */
	protected $scopes = [];

	/**
	 * {@inheritdoc}
	 */
	protected function getAuthUrl($state)
	{
		return $this->buildAuthUrlFromBase($this->serverUrl.'/auth/oauth', $state);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getTokenUrl()
	{
		return $this->apiUrl('/oauth/access-token');
	}

	/**
	 * Get the access token for the given code.
	 *
	 * @param  string  $code
	 * @return string
	 */
	public function getAccessToken($code)
	{
		$query = $this->getTokenFields($code);
		$tokenUrl = $this->getTokenUrl() .'?grant_type=authorization_code';
		//dd($query);
		try{
			$response = $this->getHttpClient()->post($tokenUrl, ['body' => $query]);
		}
		catch(\GuzzleHttp\Exception\ServerException $e) { dd($e, $tokenUrl, $query); }
		return $this->parseAccessToken($response);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function parseAccessToken($response)
	{
		// access_token token_type(Bearer) expires expires_in
		$data = $response->json();
		return $data['access_token'];
	}

	/**
	 * {@inheritdoc}
	 */
	protected function getUserByToken($token)
	{
		$response = $this->getHttpClient()->get($this->apiUrl('/me?access_token='.$token), [
			'headers' => [
				'Accept' => 'application/json',
			],
		]);

		return json_decode($response->getBody(), true);
	}

	/**
	 * {@inheritdoc}
	 */
	protected function mapUserToObject(array $user)
	{
		return (new User)->setRaw($user)->map([
			'id' => $user['id'],
			'nickname' => null,
			'name' => $user['name'],
			'email' => isset($user['email']) ? $user['email'] : null
		]);
	}

	protected function formatScopes(array $scopes)
	{
		return implode(' ', $scopes);
	}

}
