<?php
namespace vendor\isenselabs\instagramshopgallery;

/**
 * Instgaram Basic Display API library
 */
class BasicApi
{
    private $url = array(
        'api'            => 'https://graph.instagram.com/',
        'oauth'          => 'https://api.instagram.com/oauth/authorize',    // Access code and permission
        'oauth_token'    => 'https://api.instagram.com/oauth/access_token', // auth-access-code to short-lived (valid 1 hour) token
        'token_exchange' => 'https://graph.instagram.com/access_token',     // non-expired short-lived to long-lived (valid 60 days) token
        'token_refresh'  => 'https://graph.instagram.com/refresh_access_token'
    );
    private $appId;
    private $appSecret;
    private $accessToken;
    public  $redirectUri;

    private $accessScope = 'user_profile,user_media';
    private $userFields  = 'id,account_type,username,media_count';
    private $mediaFields = 'id,username,permalink,caption,media_type,media_url,thumbnail_url,timestamp,children{id,media_type,permalink,media_url,thumbnail_url,timestamp,username}';

    public function __construct($config)
    {
        $this->appId       = isset($config['appId']) ? $config['appId'] : '';
        $this->appSecret   = isset($config['appSecret']) ? $config['appSecret'] : '';
        $this->accessToken = isset($config['accessToken']) ? $config['accessToken'] : '';
        $this->redirectUri = isset($config['redirectUri']) ? $config['redirectUri'] : '';
    }

    public function setConfig($key, $value)
    {
        switch ($key) {
            case 'appId':
                $this->appId = $value;
                break;
            case 'appSecret':
                $this->appSecret = $value;
                break;
            case 'accessToken':
                $this->accessToken = $value;
                break;
            case 'redirectUri':
                $this->redirectUri = $value;
                break;
        }
    }

    //
    // Access and Permission
    //==========================================================================

    /**
     * URL for account authentication permission
     *
     * @param  string $state Returned back by Instagram callback
     * @return string
     */
    public function getUserAccessUrl($state = 'browser')
    {
        return $this->url['oauth'] . '?' .
            'client_id=' . $this->appId .
            '&scope=' . $this->accessScope .
            '&state=' . $state .
            '&response_type=' . 'code' .
            '&redirect_uri=' . urlencode($this->redirectUri);
    }

    /**
     * Get short-lived token (valid 1 hour)
     *
     * @param  string $auth_code
     * @return string|boolean
     */
    public function authCodeToken($code)
    {
        $params = array(
            'app_id'       => $this->appId,
            'app_secret'   => $this->appSecret,
            'grant_type'   => 'authorization_code',
            'code'         => $code,
            'redirect_uri' => $this->redirectUri
        );

        $result = $this->curlAuth($this->url['oauth_token'], $params);

        return $result;
    }

    /**
     * Exchange short-lived token with long-lived token (valid 60 days)
     *
     * @param  string  $stoken
     * @return string|boolean
     */
    public function exchangeToken($token)
    {
        $apiData = array(
            'client_secret' => $this->appSecret,
            'grant_type'    => 'ig_exchange_token',
            'access_token'  => $token
        );

        $result = $this->curlAuth($this->url['token_exchange'], $apiData, 'GET');

        if (isset($result['access_token'])) {
            return $result;
        }

        return false;
    }

    public function refreshToken($token)
    {
        $apiData = array(
            'grant_type' => 'ig_refresh_token',
            'access_token' => $token
        );

        $result = $this->curlAuth($this->url['token_refresh'], $apiData, 'GET');

        return $result;
    }

    protected function curlAuth($apiHost, $params = null, $method = 'POST')
    {
        $urlParam = null;
        if (isset($params) && is_array($params)) {
            $urlParam = '?' . http_build_query($params);
        }

        $apiCall = $apiHost . (('GET' === $method) ? $urlParam : null);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiCall);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, count($params));
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
        }

        $jsonData = curl_exec($ch);

        if (!$jsonData) {
            throw new Exception('InstagramShopGallery OAuth API error: ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($jsonData, true);
    }

    //
    // Account Media
    //==========================================================================


    public function getProfile()
    {
        return $this->curlApi('me', ['fields' => $this->userFields]);
    }

    public function getUserMedia($limit = 18, $next = '')
    {
        $params = [
            'fields' => $this->mediaFields
        ];

        if ($limit > 0) {
            $params['limit'] = $limit;
        }
        if ($next) {
            $params['after'] = $next;
        }

        return $this->curlApi('me/media', $params);
    }

    public function getMedia($id)
    {
        return $this->curlApi($id, ['fields' => $this->mediaFields]);
    }

    protected function curlApi($function, $params = null, $method = 'GET')
    {
        $authMethod = '?access_token=' . $this->accessToken;
        $urlParam   = null;

        if (isset($params) && is_array($params)) {
            $urlParam = '&' . http_build_query($params);
        }

        $apiCall    = $this->url['api'] . $function . $authMethod . (('GET' === $method) ? $urlParam : null);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiCall);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 150);

        $jsonData = curl_exec($ch);

        if (!$jsonData) {
            throw new Exception('InstagramShopGallery API error: ' . curl_error($ch));
        }

        list($headerContent, $jsonData) = explode("\r\n\r\n", $jsonData, 2);
        curl_close($ch);

        return json_decode($jsonData, true);
    }
}
