<?php namespace Microweber\Providers\Socialite;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class MicroweberProvider extends AbstractProvider implements ProviderInterface
{
	protected $serverUrl = 'http://login.dev';
	protected $scopes = [];

	protected function apiUrl($path) {
		return $this->serverUrl . '/api/v1' . $path;
	}

	protected function getAuthUrl($state)
	{
		return $this->buildAuthUrlFromBase($this->serverUrl.'/auth/oauth', $state);
	}

	protected function buildAuthUrlFromBase($url, $state)
	{
		$session = $this->request->getSession();

		return $url.'?'.http_build_query([
			'client_id' => $this->clientId,
			'client_secret' => $this->clientSecret,
			'redirect_uri' => $this->redirectUrl,
			'scope' => $this->formatScopes($this->scopes),
			'state' => $state,
			'response_type' => 'code',
		]);
	}

	protected function getTokenUrl()
	{
		return $this->apiUrl('/oauth/access-token');
	}

	public function getAccessToken($code)
	{
		$query = $this->getTokenFields($code);
		$tokenUrl = $this->getTokenUrl() .'?grant_type=authorization_code';
		$response = $this->getHttpClient()->post($tokenUrl, ['body' => $query]);
		return $this->parseAccessToken($response);
	}

	protected function parseAccessToken($response)
	{
		// access_token token_type(Bearer) expires expires_in
		$data = $response->json();
		return $data['access_token'];
	}

	protected function getUserByToken($token)
	{
		$response = $this->getHttpClient()->get($this->apiUrl('/me?access_token='.$token), [
			'headers' => ['Accept' => 'application/json'],
		]);
		return json_decode($response->getBody(), true);
	}

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
