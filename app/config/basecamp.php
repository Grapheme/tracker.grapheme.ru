<?php

return array(

    'urlAuthorize' => 'https://launchpad.37signals.com/authorization/new',
    'urlAccessToken' => 'https://launchpad.37signals.com/authorization/token',
    'urlUserDetails' => 'https://launchpad.37signals.com/authorization.json',
    'client_id' => 'b5605153b8751391f9bb7ab47199bf03dab2c8a7',
    'client_secret' => 'cd798809d909ea451557c767d90458ab4ca68453',
    'redirect_uri' => URL::route('oauth.callback')
);