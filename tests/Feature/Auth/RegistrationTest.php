<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('registration screen can be rendered', function () {
    $response = $this->get('/register');

    $response->assertStatus(200);
});

test('new users can register', function () {
    Event::fake();

    $response = $this->post('/register', [
        'username' => 'testuser',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    Event::assertDispatched(Registered::class);
    $this->assertAuthenticated();
    $response->assertRedirect(route('posts.index'));
});

test('registration requires username', function () {
    $response = $this->post('/register', [
        'username' => '',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors(['username']);
});

test('registration requires password', function () {
    $response = $this->post('/register', [
        'username' => 'testuser',
        'password' => '',
        'password_confirmation' => '',
    ]);

    $response->assertSessionHasErrors(['password']);
});

test('registration requires unique username', function () {
    $user = User::factory()->create([
        'username' => 'testuser'
    ]);

    $response = $this->post('/register', [
        'username' => 'testuser',
        'password' => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertSessionHasErrors('username');
    $this->assertGuest();
});

test('registration requires password confirmation', function () {
    $response = $this->post('/register', [
        'username' => 'testuser',
        'password' => 'password',
        'password_confirmation' => 'different-password',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});