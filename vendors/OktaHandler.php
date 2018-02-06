<?php
class OktaHandler {
    private $authHeaderSecret;
    private $appUrl;
    private $clientId;
    private $clientSecret;
    private $orgUrl;
    private $state;

    public function __construct()
    {
        $config = require_once '../config.php';

        $this->appUrl = $config['app_url'];
        $this->clientId = $config['client_id'];
        $this->clientSecret = $config['client_secret'];
        $this->orgUrl = $config['org_url'];
        $this->state = $config['state'];

        $this->authHeaderSecret = base64_encode("{$this->clientId}:{$this->clientSecret}");
    }

    /**
     * Redirects to okta login page
     */
    public function login()
    {
        $query = http_build_query([
            'client_id' => $this->clientId,
            'response_type' => 'code',
            'response_mode' => 'query',
            'scope' => 'openid profile',
            'redirect_uri' => "{$this->appUrl}/pages/login-callback.php",
            'state' => $this->state,
            'nonce' => uniqid()
        ]);

        header("Location: {$this->orgUrl}/oauth2/default/v1/authorize?$query");
    }

    /**
     * @return null
     * @throws Exception
     */
    private function getAccessToken()
    {
        if(array_key_exists('error', $_REQUEST)) {
            throw new \Exception($_REQUEST['error']);
        }

        if(array_key_exists('state', $_REQUEST) && $_REQUEST['state'] !== $this->state) {
            throw new \Exception('State does not match.');
        }

        if(array_key_exists('code', $_REQUEST)) {
            $exchange = $this->exchangeCode($_REQUEST['code']);
            if (isset($exchange->access_token)) {
                return $exchange->access_token;
            }
        }
        
        return null;
    }

    /**
     * make request ot get okta oauth2 access token
     * @param $code
     * @return mixed
     */
    private function exchangeCode($code) {
        $query = http_build_query([
            'grant_type' => 'authorization_code',
            'code' => $code,
            'redirect_uri' => "{$this->appUrl}/pages/login-callback.php"
        ]);
        $url = "{$this->orgUrl}/oauth2/default/v1/token?$query";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getRequestHeaders());
        curl_setopt($ch, CURLOPT_POST, 1);
        $output = curl_exec($ch);
        $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if(curl_error($ch)) {
            $httpcode = 500;
        }
        curl_close($ch);
        return json_decode($output);
    }

    /**
     * Return user name using okta oauth2 access token
     * @return null
     * @throws Exception
     */
    public function getUserName()
    {
        if ($accessToken = $this->getAccessToken()) {
            $query = http_build_query([
                'grant_type' => 'authorization_code',
                'token' => $accessToken,
                'token_type_hint' => 'access_token'
            ]);

            $url = "{$this->orgUrl}/oauth2/default/v1/introspect?$query";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getRequestHeaders());
            curl_setopt($ch, CURLOPT_POST, 1);
            $output = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (curl_error($ch)) {
                $httpcode = 500;
            }
            curl_close($ch);
            $result = json_decode($output);
            if (isset($result->username)) {
                return $result->username;
            }
        }

        throw new \Exception('User not found.');
    }

    /**
     * generate request headers for okta oauth2 requests
     * @return array
     */
    private function getRequestHeaders() {
        return [
            'Authorization: Basic ' . $this->authHeaderSecret,
            'Accept: application/json',
            'Content-Type: application/x-www-form-urlencoded',
            'Connection: close',
            'Content-Length: 0'
        ];
    }
}