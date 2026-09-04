<?php

declare(strict_types=1);

use App\Models\User;

it('lets an admin user open the panel', function () {
    $admin = User::factory()->admin()->create();

    $this->actingAs($admin)
        ->get('/admin')
        ->assertOk();
});

it('forbids a non-admin user from opening the panel', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/admin')
        ->assertForbidden();
});

it('redirects guests to the login page', function () {
    $this->get('/admin')
        ->assertRedirect('/admin/login');
});
