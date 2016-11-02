<?php

use Cyb3r\Tper\Exceptions\TperException;
use Cyb3r\Tper\Tper;
use PHPUnit\Framework\TestCase;

class TperTest extends TestCase {

    function getValidData()
    {
        return [
            'station' => '7001',
            'line'    => '38'
        ];
    }

    function getInvalidData()
    {
        return [
            'station' => '7001',
            'line'    => '328'
        ];
    }

    /** @test */
    function it_tests_tper_can_be_instantiated()
    {
        $tper = new Tper();
    }

    /** @test */
    function it_tests_tper_can_be_instantiated_using_parameters()
    {
        $tper = new Tper('7001', '38', '1700');
        $this->assertEquals($tper->getTime(), '1700');
        $this->assertEquals($tper->getLine(), '38');
        $this->assertEquals($tper->getStation(), '7001');
    }

    /** @test */
    function it_tests_tper_can_be_instantiated_using_static_make_call()
    {
        $data = [
            'station' => '7001',
            'line'    => '38',
            'time'    => '1700',
        ];
        $tper = Tper::make($data);
        $this->assertEquals($tper->getTime(), '1700');
        $this->assertEquals($tper->getLine(), '38');
        $this->assertEquals($tper->getStation(), '7001');

    }

    /** @test */
    function it_tests_time_can_be_set_to_now_if_not_provided()
    {
        $tper = new Tper;
        $this->assertNotNull($tper->getTime());
    }

    /** @test */
    function it_tests_fetching_right_data_returns_right_results()
    {
        $tper = Tper::make($this->getValidData());
        $this->assertFalse($tper->fetch()->hasError());
    }

    /** @test */
    function it_tests_it_throws_exception_with_non_valid_data()
    {
        $this->expectException(TperException::class);
        $tper = Tper::make($this->getInvalidData());
        $tper->fetch()->data();
    }
}