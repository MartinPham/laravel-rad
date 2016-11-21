<?php

namespace Martinpham\LaravelRAD\Providers\SocialiteProviders\Facebook;

use App\RAD\Exceptions\CouldNotExtendSocialiteException;
use SocialiteProviders\Manager\Exception\InvalidArgumentException;
use SocialiteProviders\Manager\SocialiteWasCalled;

class FacebookExtendSocialite
{
    /**
     * Execute the provider.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled)
    {
        try {
            $socialiteWasCalled->extendSocialite('facebook', __NAMESPACE__.'\Provider');
        }catch (InvalidArgumentException $e){

        }

    }
}
