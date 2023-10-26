<?php
namespace vendor\isenselabs\instagramshopgallery;

/**
 * Instgaram Graph API library
 *
 * - https://developers.facebook.com/docs/facebook-login/access-tokens/refreshing
 * - https://developers.facebook.com/docs/instagram-api/guides/hashtag-search
 */
class GraphApi
{
    private $url = 'https://graph.facebook.com/v7.0/';
    private $appId;
    private $appSecret;
    private $accessToken;

    public function __construct($config)
    {
        $this->appId       = isset($config['appId']) ? $config['appId'] : '';
        $this->appSecret   = isset($config['appSecret']) ? $config['appSecret'] : '';
        $this->accessToken = isset($config['accessToken']) ? $config['accessToken'] : '';
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
        }
    }

    public function getProfile()
    {
        $me = $this->curlApi('me', ['fields' => 'id,name']);
        $page = $this->curlApi('me/accounts', ['fields' => 'id']);
        $business = $this->curlApi($page['data'][0]['id'], ['fields' => 'instagram_business_account']);

        $me['fb_page_id'] = $page['data'][0]['id']; // Facebook Page ID
        $me['ig_user_id'] = $business['instagram_business_account']['id']; // Connected IG User ID

        return $me;
    }

    public function getHashtagId($hashtag, $user_id)
    {
        $nodeId = 0;

        $hashtag = $this->curlApi('ig_hashtag_search', [
            'user_id' => $user_id,
            'q' => $hashtag
        ]);

        if (isset($hashtag['data'][0]['id'])) {
            $nodeId = $hashtag['data'][0]['id'];
        }

        return $nodeId;
    }

    public function hashtagSearch($hashtagNodeId, $user_id, $limit = 18, $next = '')
    {
        $params = [
            'user_id' => $user_id,
            'fields'  => 'id,permalink,caption,media_type,media_url,timestamp'
        ];
        if ($limit > 0) {
            $params['limit'] = $limit;
        }
        if ($next) {
            $params['after'] = $next;
        }

        return $this->curlApi($hashtagNodeId . '/recent_media', $params);
    }

    protected function curlApi($function, $params = null, $method = 'GET')
    {
        $authMethod = '?access_token=' . $this->accessToken;
        $urlParam = null;

        if (isset($params) && is_array($params)) {
            $urlParam = '&' . http_build_query($params);
        }

        $apiCall    = $this->url . $function . $authMethod . (('GET' === $method) ? $urlParam : null);

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
            throw new Exception('InstagramShopGallery API error: ' . curl_error($ch));
        }

        curl_close($ch);

        return json_decode($jsonData, true);
    }
}
