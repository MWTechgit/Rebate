<?php

namespace Tests\Unit;

use App\Address;
use App\Application;
use App\History;
use App\Property;
use Bwp\QuickAudit\Fetcher;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class QuickAuditTest extends TestCase
{
    // use RefreshDatabase;
    
    public function createApplicationAddress($vars)
    {
        $app = factory(Application::class)->create();
        $property = factory(Property::class)->create([
            'application_id' => $app->id
        ]);
        $app->property->address->delete();
        $address = factory(Address::class)->create(array_merge($vars, [
            'addressable_type' => get_class($property),
            'addressable_id'   => $property->id
        ]));

        $property->address()->save($address);
        $this->assertTrue($property->address->is($address));
        $app->load('property.address');

        return $app;
    }

    public function getMatchResults($address1, $address2)
    {
        $app = $this->createApplicationAddress($address1);

        $history = factory(History::class)->create($address2);

        return (new Fetcher())->addressQuery($app)->get()->contains($history->id);
    }

    public function isMatch($address1, $address2)
    {
        $this->assertTrue($this->getMatchResults($address1, $address2), 
            Address::buildIndex( (object) $address1) . ' DOES NOT MATCH ' . Address::buildIndex( (object) $address2));
    }

    public function notMatches($address1, $address2)
    {
        $this->assertFalse($this->getMatchResults($address1, $address2), 
        Address::buildIndex( (object) $address1) . ' DOES MATCH ' . Address::buildIndex( (object) $address2));
    }

    /** @test */
    public function test_matching_patterns()
    {
        $city = 'Pembroke Pines';
        $zip = 33029;
        $state = 'FL';

        $a1 = ['line_one' => '2001 NW 178 Terrace', 'city' => $city, 'state' => $state, 'postcode' => $zip];
        $a2 = ['line_one' => '20011 NW 2nd St', 'city' => $city, 'state' => $state, 'postcode' => $zip];

        $this->isMatch($a1,$a1);
        $this->notMatches($a1,$a2);
        $this->notMatches($a2,$a1);

        $a3 = ['line_one' => '650 SW 138th Ave J107', 'city' => $city, 'state' => $state, 'postcode' => 33027];

        $this->notMatches($a1,$a3);
        $this->notMatches($a3,$a1);

        $a3 = ['line_one' => '650 SW 138th Ave', 'line_two' => 'J107', 'city' => $city, 'state' => $state, 'postcode' => 33027];
        $a5 = ['line_one' => '650 SW 138th', 'line_two' => 'J107', 'city' => $city, 'state' => $state, 'postcode' => 33027];

        $this->notMatches($a1,$a5);
        $this->notMatches($a5,$a1);
        $this->notMatches($a1,$a3);
        $this->notMatches($a3,$a1);
        $this->isMatch($a5,$a3);

        $a4 = ['line_one' => '2281 Yucca Ave', 'city' => $city, 'state' => $state, 'postcode' => 33026];
        $this->notMatches($a1,$a4);
        $this->notMatches($a4,$a1);

        $b1 = ['line_one' => '8070 SW 20th Court', 'city' => 'Davie', 'state' => $state, 'postcode' => 33324];
        $b2 = ['line_one' => '8070 SW 20 Ct', 'city' => 'Davie', 'state' => $state, 'postcode' => 33324];
        $this->isMatch($b1,$b2);
        $this->isMatch($b2,$b1);

    }
}
