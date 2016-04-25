<?php
namespace thewulf7\SeventeenTrack;


/**
 * Class SeventeenTrack
 *
 * @package App\extensions\Seventrack
 */
class SeventeenTrack
{
    /**
     * @var ApiClient
     */
    private $_client;

    /**
     * @var DataReceiver
     */
    private $_data;

    /**
     * SeventeenTrack constructor.
     */
    public function __construct()
    {
        $this->_client = new ApiClient();
        $this->_data   = new DataReceiver();
    }

    /**
     * @param string $num     Tracking number
     */
    public function getTracking($num)
    {
        $arResult = [];

        $data = json_encode(
            [
                'guid' => '',
                'data' =>
                    [
                        ['num' => trim($num), 'fc' => 100000],
                    ],
            ]
        );

        $response = $this->getClient()->execute($data);

        foreach ($response['dat'] as $pack)
        {
            $package = [
                'num'          => $pack['no'],
                'carrier'      => [
                    $this->getData()->getCarrier($pack['track']['w1']),
                    $this->getData()->getCarrier($pack['track']['w2']),
                ],
                'country_from' => $this->getData()->getCountry($pack['track']['b']),
                'country_to'   => $this->getData()->getCountry($pack['track']['c']),
                'status'       => $this->getData()->getStatus($pack['track']['e']),
                'duration'     => $pack['track']['f'],
                'last_event'   => [
                    'date'     => $pack['track']['z0']['a'],
                    'location' => $pack['track']['z0']['c'],
                    'message'  => $pack['track']['z0']['z'],
                ],
                'all_events'   => array_map(function ($eventA, $eventB) use ($pack)
                {
                    return [
                        'date'     => $eventA['a'],
                        'location' => [
                            $pack['track']['ln1'] => $eventA['c'],
                            $pack['track']['ln2'] => $eventB['c']
                        ],
                        'message'  => [
                            $pack['track']['ln1'] => $eventA['z'],
                            $pack['track']['ln2'] => $eventB['z'],
                        ],
                    ];
                }, $pack['track']['z1'], $pack['track']['z2'])
            ];

            $arResult[] = $package;
        }

        return $arResult;
    }

    /**
     * Get Client
     *
     * @return ApiClient
     */
    public function getClient()
    {
        return $this->_client;
    }

    /**
     * Get Data
     *
     * @return DataReceiver
     */
    public function getData()
    {
        return $this->_data;
    }
}