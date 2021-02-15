<?php

namespace Tests\Feature\Auth;

use Dotenv\Loader\Resolver;
use http\Client\Curl\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
use Tests\CreatesApplication;
use Tests\TestCase;

class LoginTest extends TestCase
{
    /**
     * Returns a new Ldap user model
     *
     * @param array $attributes
     *
     * @return \App\Ldap\User
     */

    protected function makeLdapUser(array $attributes = []) {
        $provider = config('ldap_auth.connection');
        return Adldap::getProvider($provider)->make()->user($attributes);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_ldap_authentication_works()
    {
        $credentials = ['username' => 'famarino', 'password' => 'Millonarios35'];

        $user = $this->makeLdapUser([
            'guid'            => [$this->faker->uuid],
            'cn'                    => ['Fabian Antonio Marino Riveros'],
            'sAMAccountName'     => ['famarino'],
        ]);

        Resolver::shouldReceive('byCredentials')->once()->andReturn($user)
            ->shouldReceive('authenticate')->once()->andReturn(true);

        $this->post(route('login'), $credentials)->assertRedirect('/home');

        $this->assertInstanceOf(\App\Models\User::class, Auth::user());
    }

}
