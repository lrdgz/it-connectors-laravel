<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Faker\Factory as Faker;


abstract class TestCase extends BaseTestCase
{

    use CreatesApplication, DatabaseMigrations;

    protected $faker;
    protected $headers = [];
    protected $credentials = [];
    protected $scopes = [];
    protected $body = [];

    protected $user;
    protected $endpoint;
    protected $prefix;
    protected $urlApp;

    public function setUp() : void
    {
        parent::setUp();

        //INITIALIZE A UTILS VARIABLES
        $this->faker = Faker::create();
        $this->prefix = 'api';
        $this->urlApp = "{$this->prefix}";

        //TEST CREDENTIALS
        $this->credentials['email']     = 'app@dev.com';
        $this->credentials['password']  = 'Secr3t';

        //API HEADERS
        $this->headers = ['Accept' => 'application/json', 'Content-Type' => 'application/json'];

        Artisan::call('migrate:fresh');
        Artisan::call('db:seed');
        Artisan::call('passport:install');
    }

    //FUNCTION TO GET USER LOGGED IN
    public function getUserToken(){
        $data = $this->json('POST', "/{$this->prefix}/login",$this->credentials, $this->headers);
        return $this->user = $data->decodeResponseJson();
    }
}
