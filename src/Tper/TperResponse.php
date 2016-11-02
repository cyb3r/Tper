<?php namespace Cyb3r\Tper;

use Carbon\Carbon;
use Cyb3r\Tper\Exceptions\TperException;
use Psr\Http\Message\ResponseInterface;

class TperResponse {

    protected $response;
    protected $result;

    /**
     * TperResponse constructor.
     * @param ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
        $this->result = $this->parse($response);
    }

    public function parse($response)
    {
        return simplexml_load_string($response->getBody()->getContents());
    }

    public function __toString()
    {
        return $this->result->__toString();
    }

    public function hasError()
    {
        return strpos(strtolower((string)$this->result), 'non gestita') !== false;
    }

    public function data()
    {
        if ($this->hasError()) {
            throw new TperException('Wrong Data!');
        }

        return $this->findTime();
    }

    protected function findTime()
    {
        preg_match_all('/[0-9]{2}:[0-9]{2}/', $this->result, $results);
        $time = $results[0][1];

        return [
            'time' => Carbon::parse($time)
        ];
    }


}