<?php

namespace Tests\Feature;

use App\Slack\SlackUserData;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SlackUserDataTest extends TestCase {
	
	public function testGetUserInformationFromSlack() {
		$this->assertIsObject( SlackUserData::getUserInformationFromSlack( 'Actually Connor' ) );
	}
	
	public function testFailingGetUserInformationFromSlack() {
		$this->assertFalse( SlackUserData::getUserInformationFromSlack( 'dopey' ) );
	}
}
