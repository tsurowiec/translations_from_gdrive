<?php


class GDrive
{
    const APPLICATION_NAME = 'Drive API PHP Quickstart';
    const CREDENTIALS_PATH = '~/.credentials/drive-php-quickstart.json';
    const CLIENT_SECRET_PATH = __DIR__ .'/../client_secret.json';

    /**
     * Returns an authorized API client.
     *
     * @return Google_Service_Drive
     */
    public static function getService()
    {
        $client = new Google_Client();
        $client->setApplicationName(self::APPLICATION_NAME);
        $client->setScopes(implode(' ', [
            Google_Service_Drive::DRIVE,
        ]));
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
            printf("Credentials saved to %s\n", $credentialsPath);
        }
        $client->setAccessToken($accessToken);

        // Refresh the token if it's expired.
        if ($client->isAccessTokenExpired()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            file_put_contents($credentialsPath, json_encode($client->getAccessToken()));
        }

        return new Google_Service_Drive($client);
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