<?php

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use \App\Domains\Repositories\UsersRepository;
use \App\Domains\Repositories\PermissionsRepository;
use \App\Domains\Repositories\RolesRepository;

class UsersPermsRolesTest extends TestCase
{

    /** @var UsersRepository */
    protected $usersRepository;

    /** @var RolesRepository */
    protected $rolesRepository;

    /** @var PermissionsRepository */
    protected $permsRepository;

    public function createApplication() {
        $app = parent::createApplication();

        if (!Schema::hasTable('users'))
            Schema::create('users');

        if (!Schema::hasTable('roles'))
            Schema::create('roles');

        if (!Schema::hasTable('permissions'))
            Schema::create('permissions');

        $this->usersRepository = $app->make(UsersRepository::class);
        $this->rolesRepository = $app->make(RolesRepository::class);
        $this->permsRepository = $app->make(PermissionsRepository::class);

        $this->beforeApplicationDestroyed(function(){
//            Schema::drop('users');
//            Schema::drop('roles');
//            Schema::drop('permissions');
        });

        return $app;
    }

    protected function setUp() {
        parent::setUp();
    }

    protected function tearDown() {
        $this->usersRepository->truncate();
        $this->rolesRepository->truncate();
        $this->permsRepository->truncate();

        parent::tearDown();
    }

    public function testCreatePermissions() {

        $role = $this->rolesRepository->create([
            'id' => 'super_admin',
            'display_name' => 'Super Admin',
            'description' => 'Root user, can do everything!!'
        ]);

        $this->assertNotNull($role);
        $this->assertEquals(1, $this->rolesRepository->count());

        $perm1 = $this->permsRepository->create([
            'id' => 'create_exchanges',
            'display_name' => 'Create Exchanges',
            'description' => 'Create news exchanges pairs',
        ]);
        $this->assertNotNull($perm1);
        $role->permissions()->attach($perm1);

        $role->permissions()->create([
            'id' => 'edit_exchanges',
            'display_name' => 'Edit Exchanges',
            'description' => 'Edit existing exchanges pairs',
        ]);

        $perm2 = $this->permsRepository->create([
            'id' => 'delete_exchanges',
            'display_name' => 'Delete Exchanges',
            'description' => 'Delete existing exchanges pairs',
        ]);
        $this->assertNotNull($perm2);
        $role->permissions()->attach($perm2);

        $role = \App\Domains\Models\Role::with('permissions')->find($role->id);
        $permission = \App\Domains\Models\Permission::with('roles')->first();

        $this->assertTrue(array_key_exists('role_ids', $permission->getAttributes()));
        $this->assertTrue(array_key_exists('permission_ids', $role->getAttributes()));

        $permissions = $role->getRelation('permissions');
        $roles = $permission->getRelation('roles');

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $roles);
        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $permissions);

        $this->assertInstanceOf('\App\Domains\Models\Permission', $permissions[0]);
        $this->assertInstanceOf('\App\Domains\Models\Role', $roles[0]);
        $this->assertCount(3, $role->permissions);
        $this->assertCount(1, $permission->roles);

        $permission = $role->permissions()->create([
            'id' => 'create_currencies',
            'display_name' => 'Create Currencies',
            'description' => 'Create new currencies',
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $permission->roles);
        $this->assertInstanceOf('\App\Domains\Models\Role', $permission->roles->first());
        $this->assertCount(1, $permission->roles);

        $permissions = $this->permsRepository->whereIn('role_ids', $role->getKey())->get();
        $roles = $this->rolesRepository->whereIn('permission_ids', $permission->getKey())->get();

        $this->assertCount(4, $permissions);
        $this->assertCount(1, $roles);

        $role2 = $this->rolesRepository->create([
            'id' => 'root',
            'display_name' => 'Root',
            'description' => 'Root user, can do everything and more!'
        ]);

        $permission = $role->permissions()->create([
            'id' => 'delete_currencies',
            'display_name' => 'Delete Currencies',
            'description' => 'Delete existing currencies',
        ]);

        $this->assertInstanceOf('Illuminate\Database\Eloquent\Collection', $permission->roles);
        $this->assertInstanceOf('\App\Domains\Models\Role', $permission->roles->first());
        $this->assertCount(1, $permission->roles);

        $role2->permissions()->attach($permission);

        $roles = $this->rolesRepository->whereIn('permission_ids', $permission->getKey())->get();
        $this->assertCount(2, $roles);

        //detach
        $permission = $this->permsRepository->findByID('delete_currencies');

        $permission->roles()->detach($role);
        $this->assertCount(1, $permission->roles);
    }

    public function testCreateRoles() {

        $role = $this->rolesRepository->create([
            'id' => 'super_admin',
            'display_name' => 'Super Admin',
            'description' => 'Root user, can do everything!!'
        ]);

        $this->assertNotNull($role);
        $this->assertEquals(1, $this->rolesRepository->count());
    }

    public function testCreateUniquesUsers() {
        $pass = 'segredoporra';
        $passhash = Hash::make($pass);

        $this->expectException(\App\Domains\Exceptions\DuplicateKeyException::class);

        $user = $this->usersRepository->create([
            'name' => 'Bruno Jorge Kosloski',
            'email' => 'brunojorgek@gmail.com',
            'password' => $passhash
        ]);

        $user2 = $this->usersRepository->create([
            'name' => 'Bruno Jorge Kosloski',
            'email' => 'brunojorgek@gmail.com',
            'password' => $passhash
        ]);

        $this->assertEquals(1, $this->usersRepository->count());

        $user3 = $this->usersRepository->create([
            'name' => 'Bruno Jorge Kosloski',
            'email' => 'brunojk@gmail.com',
            'password' => $passhash
        ]);

        $this->assertEquals(2, $this->usersRepository->count());
    }

    public function testCreateUsers() {
        $pass = 'segredoporra';
        $passhash = Hash::make($pass);

        $user = $this->usersRepository->create([
            'name' => 'Bruno Jorge Kosloski',
            'email' => 'brunojorgek@gmail.com',
            'password' => $passhash
        ]);

        $this->assertEquals(1, $this->usersRepository->count());
        $this->assertTrue(Hash::check($pass, $passhash));

        $this->assertTrue( Auth::attempt(['email' => 'brunojorgek@gmail.com', 'password' => $pass]) );
        $this->assertNotNull(Auth::user());
        $this->assertEquals(Auth::user()->email, $user->email);
    }
}
