<?php namespace Cyb3r\Tper;

use Carbon\Carbon;
use GuzzleHttp\Client;

class Tper {

    /**
     * Tper HelloBus endpoint
     *
     * @var string
     */
    protected $url = 'https://hellobuswsweb.tper.it/web-services/hello-bus.asmx/QueryHellobus';

    /**
     * Bus stop number
     *
     * @var string
     */
    protected $station;

    /**
     * Bus line number
     *
     * @var string
     */
    protected $line;
    /**
     * Time to check
     *
     * @var string
     */
    protected $time;

    /**
     * Guzzle client
     *
     * @var Client
     */
    protected $client;

    public function __construct($station = null, $line = null, $time = null)
    {
        $this->setStation($station);
        $this->setLine($line);
        $this->setTime($time);
        $this->client = new Client;
    }

    /**
     * Make from input array
     *
     * @param array $data
     * @return Tper
     */
    public static function make($data = [])
    {
        $station = isset($data['station']) ? $data['station'] : null;
        $line = isset($data['line']) ? $data['line'] : null;
        $time = isset($data['time']) ? $data['time'] : null;

        return new static($station, $line, $time);
    }


    /**
     * @return string
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * @param string $time
     * @example '0914' means 09:14
     */
    public function setTime($time = null)
    {
        if ($time === null) {
            $time = Carbon::now('Europe/Rome')->format('Gi');
        }

        $this->time = $time;
    }

    /**
     * @return string
     */
    public function getStation()
    {
        return $this->station;
    }

    /**
     * @param string $station
     */
    public function setStation($station)
    {
        $this->station = $station;
    }

    /**
     * @return string
     */
    public function getLine()
    {
        return $this->line;
    }

    /**
     * @param string $line
     */
    public function setLine($line)
    {
        $this->line = $line;
    }


    public function fetch()
    {

        //$this->validate();

        $message = $this->client->get($this->url, [
            'query' => [
                'fermata' => $this->getStation(),
                'linea'   => $this->getLine(),
                'oraHHMM' => $this->getTime()
            ]
        ]);

        return new TperResponse($message);
    }

}