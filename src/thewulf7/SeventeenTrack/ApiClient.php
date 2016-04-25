<?php
namespace thewulf7\SeventeenTrack;

use GuzzleHttp\Client as HttpClient;

/**
 * Class ApiClient
 *
 * @package App\extensions\Seventrack
 */
class ApiClient
{
    /**
     *   HOST
     */
    const API_HOST = 'http://www.17track.net/restapi/handlertrack.ashx';

    /**
     * @var HttpClient
     */
    private $_client;

    /**
     * ApiClient constructor.
     */
    public function __construct()
    {
        $this->_client = new HttpClient(
            [
                'base_uri' => self::API_HOST,
                'headers'  =>
                    [
                        'Content-Type'     => 'application/x-www-form-urlencoded; charset=UTF-8',
                        'Accept-Encoding'  => 'gzip,deflate,sdch',
                        'Origin'           => 'http://www.17track.net',
                        'Referer'          => 'http://www.17track.net/pt/track?nums=&fc=0',
                        'X-Requested-With' => 'XMLHttpRequest',
                    ],
            ]
        );
    }

    /**
     * Execute the request
     *
     * @param mixed $options
     *
     * @return mixed
     */
    public function execute($options)
    {
        $params = [
            'http_errors' => false,
            'body'        => $options,
        ];

        /** @var \GuzzleHttp\Psr7\Request $res */
        $res        = $this->getClient()->request('POST', null, $params);
        $statusCode = $res->getStatusCode();
        $body       = $res->getBody();

        if ($statusCode !== 200)
        {
            $strBody = json_decode((string)$body, true);
            throw new \RuntimeException("{$statusCode}:{$strBody['message']}");
        }

        return $this->makeApiResponse($body);
    }

    /**
     * @param string $jsonString
     *
     * @return mixed
     * @throws \RuntimeException
     */
    private function makeApiResponse($jsonString)
    {
        $data = json_decode($jsonString, true);

        if (!$data)
        {
            throw new \RuntimeException("Unable to decode json response: $jsonString");
        } elseif ($data['msg'] !== 'Ok') {
            throw new \RuntimeException($data['msg']);
        }

        return $data;
    }

    /**
     * @return HttpClient
     */
    public function getClient()
    {
        return $this->_client;
    }
}