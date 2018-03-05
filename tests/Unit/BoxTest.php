<?php

namespace Tests\Unit;

use App\Box;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BoxTest extends TestCase
{

    /**
     * Test method has()
     *
     * @return void
     */
    public function testHas__should_find_the_element_whether_it_exists_in_the_given_array()
    {
        $box = new Box(['cat', 'mouse', 'snake']);
        $this->assertTrue($box->has('cat'));
        $this->assertFalse($box->has('ball'));
    }

    /**
     * Test method first()
     *
     * @return void
     */
    public function testFirst__should_return_the_first_element_in_the_given_array()
    {
        $box = new Box(['cat']);
        $this->assertEquals('cat', $box->first());

        $this->assertNull($box->first());
    }

    /**
     * Test method startWith()
     *
     * @return void
     */
    public function testStartWith__should_return_the_elements_with_the_given_letters()
    {
        $box = new Box(['toy', 'torch', 'ball', 'cat', 'tissue']);
        $result = $box->startWith('t');

        $this->assertCount(3, $result);        
        $this->assertContains('torch', $result);
        $this->assertContains('toy', $result);
        $this->assertContains('tissue', $result);

        //empty array if not found
        $this->assertEmpty($box->startWith('s'));
    }
}
