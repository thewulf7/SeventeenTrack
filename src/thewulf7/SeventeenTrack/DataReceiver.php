<?php
namespace thewulf7\SeventeenTrack;


/**
 * Class DataReceiver
 *
 * @package App\extensions\Seventrack
 */
class DataReceiver
{

    /**
     * @var array
     */
    protected $statusList = [];

    /**
     * @var array
     */
    protected $countryList = [];

    /**
     * @var array
     */
    protected $carrierList = [];

    /**
     * @param $statusCode
     *
     * @return mixed
     */
    public function getStatus($statusCode)
    {
        if(count($this->statusList) === 0) {
            $this->statusList = json_decode(file_get_contents(__DIR__ . '/data/status.json'), true);
        }

        $key = array_search((string)$statusCode, array_column($this->statusList,'key'), true);

        return $this->statusList[$key];
    }

    /**
     * @param $countryCode
     *
     * @return mixed
     */
    public function getCountry($countryCode)
    {
        if(count($this->countryList) === 0) {
            $this->countryList = json_decode(file_get_contents(__DIR__ . '/data/countries.json'), true);
        }

        $countryCode = (int)$countryCode < 1000 ? '0' . $countryCode : $countryCode;

        $key = array_search((string)$countryCode, array_column($this->countryList,'key'), true);

        return $this->countryList[$key];
    }

    /**
     * @param $carrierCode
     *
     * @return mixed
     */
    public function getCarrier($carrierCode)
    {
        if(count($this->carrierList) === 0) {
            $this->carrierList = json_decode(file_get_contents(__DIR__ . '/data/carriers.json'), true);
        }

        $carrierCode = (int)$carrierCode < 1000 ? '0' . $carrierCode : $carrierCode;

        $key = array_search((string)$carrierCode, array_column($this->carrierList,'key'), true);

        return $this->carrierList[$key];
    }

}