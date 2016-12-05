<?php

namespace GDriveTranslations;

use GDriveTranslations\Config\Config;

class GDrive
{
    const APPLICATION_NAME = 'Translate from gDrive';
    const CREDENTIALS_PATH = '/lang/translate_token.json';
    const CLIENT_SECRET_PATH = __DIR__.'/../../client_secret.json';

    /**
     * Returns an authorized API client.
     *
     * @param Config $config
     *
     * @return \Google_Service_Drive
     */
    public static function getService($scope)
    {
        $scopes = [];
        $client = new \Google_Client();
        $client->setApplicationName(self::APPLICATION_NAME);
        switch ($scope) {
            case Config::ACCESS_FILE:
                $scopes[] = \Google_Service_Drive::DRIVE_FILE;
                break;
            case Config::ACCESS_DRIVE:
                $scopes[] = \Google_Service_Drive::DRIVE;
                break;
        }
        $client->setScopes(implode(' ', $scopes));
        $client->setAuthConfig(self::CLIENT_SECRET_PATH);
        $client->setAccessType('offline');
        // Load previously authorized credentials from a file.
        $credentialsPath = self::expandHomeDirectory(self::CREDENTIALS_PATH);
        if (file_exists($credentialsPath)) {
            $accessToken = json_decode(file_get_contents($credentialsPath), true);
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("\nOpen the following link in your browser:\n\n%s\n\n", $authUrl);
            echo 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);

            // Store the credentials to disk.
            if (!file_exists(dirname($credentialsPath))) {
                mkdir(dirname($credentialsPath), 0700, true);
            }
            file_put_contents($credentialsPath, json_encode($accessToken));
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $refresh_token = $client->getRefreshToken();
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            $token = $client->getAccessToken();
            $token['refresh_token'] = $refresh_token;
            file_put_contents($credentialsPath, json_encode($token));
        }

        return new \Google_Service_Drive($client);
    }

    /**
     * Expands the home directory alias '~' to the full path.
     *
     * @param string $path the path to expand.
     *
     * @return string the expanded path.
     */
    public static function expandHomeDirectory($path)
    {
        $homeDirectory = getenv('HOME');
        if (empty($homeDirectory)) {
            $homeDirectory = getenv('HOMEDRIVE').getenv('HOMEPATH');
        }

        return str_replace('~', realpath($homeDirectory), $path);
    }
}
