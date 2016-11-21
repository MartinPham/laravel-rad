<?php

namespace Martinpham\LaravelRAD\Providers\SocialiteProviders\Facebook;

use GuzzleHttp\ClientInterface;
use Illuminate\Support\Arr;
use Laravel\Socialite\Two\ProviderInterface;
use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider implements ProviderInterface
{
    /**
     * Unique Provider Identifier.
     */
    const IDENTIFIER = 'FACEBOOK';

    /**
     * The base Facebook Graph URL.
     *
     * @var string
     */
    protected $graphUrl = 'https://graph.facebook.com';
    /**
     * The Graph API version for the request.
     *
     * @var string
     */
    protected $version = 'v2.8';
    /** @noinspection PropertyCanBeStaticInspection */
    /** @noinspection PropertyCanBeStaticInspection */
    /** @noinspection PropertyCanBeStaticInspection */
    /** @noinspection PropertyCanBeStaticInspection */
    /**
     * The user fields being requested.
     *
     * @var array
     */
    //me?fields=id,about,age_range,birthday,context,cover,currency,devices,education,email,favorite_athletes,favorite_teams,first_name,gender,hometown,inspirational_people,install_type,installed,interested_in,is_shared_login,is_verified,languages,last_name,link,locale,location,meeting_for,middle_name,name,name_format,payment_pricepoints,political,public_key,quotes,relationship_status,religion,security_settings,shared_login_upgrade_required_by,significant_other,sports,test_group,third_party_id,timezone,updated_time,verified,video_upload_limits,viewer_can_send_gift,website,work
    //me?fields=id,age_range,context,cover,currency,email,first_name,gender,install_type,installed,is_shared_login,is_verified,last_name,link,locale,name,name_format,payment_pricepoints,security_settings,test_group,third_party_id,timezone,updated_time,verified,video_upload_limits,viewer_can_send_gift
    protected $fields = ['id', 'age_range', 'cover', 'currency', 'email', 'first_name', 'gender', 'install_type', 'installed', 'is_shared_login', 'is_verified', 'last_name', 'link', 'locale', 'name', 'name_format', 'payment_pricepoints', 'security_settings', 'test_group', 'third_party_id', 'timezone', 'updated_time', 'verified', 'video_upload_limits', 'viewer_can_send_gift'];
    /**
     * The scopes being requested.
     *
     * @var array
     */
    protected $scopes = ['email'];
    /**
     * Display the dialog in a popup view.
     *
     * @var bool
     */
    protected $popup = false;
    /**
     * Re-request a declined permission.
     *
     * @var bool
     */
    protected $reRequest = false;
    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://www.facebook.com/'.$this->version.'/dialog/oauth', $state);
    }
    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return $this->graphUrl.'/oauth/access_token';
    }
    /**
     * {@inheritdoc}
     */
    public function getAccessTokenResponse($code)
    {
        $postKey = (version_compare(ClientInterface::VERSION, '6') === 1) ? 'form_params' : 'body';
        $response = $this->getHttpClient()->post($this->getTokenUrl(), [
            $postKey => $this->getTokenFields($code),
        ]);
        $data = [];
        parse_str($response->getBody(), $data);
        return Arr::add($data, 'expires_in', Arr::pull($data, 'expires'));
    }

    public function userDataByToken($token)
    {
        $response = $this->exchangeToken($token);

        $user = $this->mapUserToObject($this->getUserByToken(
            $token = $this->parseAccessToken($response)
        ));

        $this->credentialsResponseBody = $response;

        if ($user instanceof User) {
            $user->setAccessTokenResponseBody($this->credentialsResponseBody);
        }

        return $user->setToken($token)
            ->setRefreshToken($this->parseRefreshToken($response))
            ->setExpiresIn($this->parseExpiresIn($response));
    }

    public function exchangeToken($token)
    {
        $exchangeUrl = $this->graphUrl.'/'.$this->version.'/oauth/access_token?access_token='.$token.'&grant_type=fb_exchange_token';
        $exchangeUrl .= '&client_id=' . $this->clientId;
        $exchangeUrl .= '&client_secret=' . $this->clientSecret;
        $exchangeUrl .= '&fb_exchange_token=' . $token;
        $response = $this->getHttpClient()->get($exchangeUrl, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);
        return json_decode($response->getBody(), true);
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $meUrl = $this->graphUrl.'/'.$this->version.'/me?access_token='.$token.'&fields='.implode(',', $this->fields);
        if (! empty($this->clientSecret)) {
            $appSecretProof = hash_hmac('sha256', $token, $this->clientSecret);
            $meUrl .= '&appsecret_proof='.$appSecretProof;
        }
        $response = $this->getHttpClient()->get($meUrl, [
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
        $avatarUrl = $this->graphUrl.'/'.$this->version.'/'.$user['id'].'/picture';
        return (new User)->setRaw($user)->map([
            'id' => $user['id'],
            'nickname' => null,
            'name' => isset($user['name']) ? $user['name'] : null,
            'first_name' => isset($user['first_name']) ? $user['first_name'] : null,
            'last_name' => isset($user['last_name']) ? $user['last_name'] : null,
            'email' => isset($user['email']) ? $user['email'] : null,
            'avatar' => $avatarUrl.'?width=1920',
            'avatar_original' => $avatarUrl.'?width=1920',
            'profileUrl' => isset($user['link']) ? $user['link'] : null,
        ]);
    }
    /**
     * {@inheritdoc}
     */
    protected function getCodeFields($state = null)
    {
        $fields = parent::getCodeFields($state);
        if ($this->popup) {
            $fields['display'] = 'popup';
        }
        if ($this->reRequest) {
            $fields['auth_type'] = 'rerequest';
        }
        return $fields;
    }
    /**
     * Set the user fields to request from Facebook.
     *
     * @param  array  $fields
     * @return $this
     */
    public function fields(array $fields)
    {
        $this->fields = $fields;
        return $this;
    }
    /**
     * Set the dialog to be displayed as a popup.
     *
     * @return $this
     */
    public function asPopup()
    {
        $this->popup = true;
        return $this;
    }
    /**
     * Re-request permissions which were previously declined.
     *
     * @return $this
     */
    public function reRequest()
    {
        $this->reRequest = true;
        return $this;
    }

    
    /**
     * {@inheritdoc}
     */
    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'grant_type' => 'authorization_code'
        ]);
    }
}