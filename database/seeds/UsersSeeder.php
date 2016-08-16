<?php

class UsersSeeder extends BaseSeeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var \App\Domains\Repositories\UsersRepository */
        $usersRepository = app()->make('\App\Domains\Repositories\UsersRepository');

        /** @var \App\Domains\Repositories\RolesRepository */
        $rolesRepository = app()->make('\App\Domains\Repositories\RolesRepository');

        /** @var \App\Domains\Repositories\PermissionsRepository */
        $permsRepository = app()->make('\App\Domains\Repositories\PermissionsRepository');

        //create users
        $bruno = $usersRepository->create([
            'name' => 'Bruno Jorge Kosloski',
            'email' => 'brunojorgek@gmail.com',
            'password' => bcrypt('bdev6969')
        ]);
        $fernando = $usersRepository->create([
            'name' => 'Fernando Ribeiro',
            'email' => 'fernando@ribeiro.com',
            'password' => bcrypt('fdev6969')
        ]);
        $roberto = $usersRepository->create([
            'name' => 'Roberto Tauille',
            'email' => 'roberto@gmail.com',
            'password' => bcrypt('rdev6969')
        ]);
        $manu = $usersRepository->create([
            'name' => 'Manu',
            'email' => 'manu@aposta.la',
            'password' => bcrypt('mdev6969')
        ]);
        $emilio = $usersRepository->create([
            'name' => 'Emilio',
            'email' => 'emilio@aposta.la',
            'password' => bcrypt('edev6969')
        ]);
        $ariel = $usersRepository->create([
            'name' => 'Ariel',
            'email' => 'ariel@aposta.la',
            'password' => bcrypt('adev6969')
        ]);
        $andre = $usersRepository->create([
            'name' => 'Andrézinho',
            'email' => 'andre@aposta.la',
            'password' => bcrypt('adev6969')
        ]);

        //create roles
        $super_admin = $bruno->roles()->create([
            'id' => 'super_admin',
//            'display_name' => 'Super Admin',
//            'description' => 'Root user, can do everything!!'
        ]);
        $this->i18n->set('roles.' . $super_admin->getKey(), 'Super Admin');
        $this->i18n->set('roles.' . $super_admin->getKey() . '_description', 'Root user, can do everything!!');
        $this->i18n->set('pt.roles.' . $super_admin->getKey(), 'Super Administrador');
        $this->i18n->set('pt.roles.' . $super_admin->getKey() . '_description', 'Usuário de nível mais alto, pode fazer qualquer coisa!!');

        $fernando->roles()->attach($super_admin);
        $roberto->roles()->attach($super_admin);

        $admin = $manu->roles()->create([
            'id' => 'admin',
//            'display_name' => 'Administrator',
//            'description' => 'User is allowed to manage and edit other users'
        ]);
        $this->i18n->set('roles.' . $admin->getKey(), 'Administrator');
        $this->i18n->set('roles.' . $admin->getKey() . '_description', 'User is allowed to manage and edit other users');
        $this->i18n->set('pt.roles.' . $admin->getKey(), 'Administrador');
        $this->i18n->set('pt.roles.' . $admin->getKey() . '_description', 'Usuário pode gerenciar e editar outros usuários');

        $emilio->roles()->attach($admin);

        $financial = $rolesRepository->construct([
            'id' => 'financial',
//            'display_name' => 'Financial',
//            'description' => 'User is allowed to manage financial transactions between users/company'
        ]);
        $this->i18n->set('roles.' . $financial->getKey(), 'Financial');
        $this->i18n->set('roles.' . $financial->getKey() . '_description', 'User is allowed to manage financial transactions between users and/or company');
        $this->i18n->set('pt.roles.' . $financial->getKey(), 'Financeiro');
        $this->i18n->set('pt.roles.' . $financial->getKey() . '_description', 'Usuário pode gerenciar transações financeiras entre usuários e/ou empresa');

        $ariel->roles()->save($financial);

        $employee = $ariel->roles()->create([
            'id' => 'employee',
//            'display_name' => 'Employee',
//            'description' => 'User is allowed to print and manage bets'
        ]);
        $this->i18n->set('roles.' . $employee->getKey(), 'Employee');
        $this->i18n->set('roles.' . $employee->getKey() . '_description', 'User is allowed to print and manage bets');
        $this->i18n->set('pt.roles.' . $employee->getKey(), 'Funcionário');
        $this->i18n->set('pt.roles.' . $employee->getKey() . '_description', 'Usuário pode imprimir e gerenciar apostas');

        $andre->roles()->attach($employee);
        $delivery = $andre->roles()->create([
            'id' => 'delivery',
//            'display_name' => 'Delivery',
//            'description' => 'User is allowed to manage some customers and some financial transactions'
        ]);
        $this->i18n->set('roles.' . $delivery->getKey(), 'Delivery');
        $this->i18n->set('roles.' . $delivery->getKey() . '_description', 'User is allowed to manage some customers and some financial transactions');
        $this->i18n->set('pt.roles.' . $delivery->getKey(), 'Vendedor externo');
        $this->i18n->set('pt.roles.' . $delivery->getKey() . '_description', 'Usuário pode gerenciar alguns clientes e algumas transações financeiras');














//        $froles = [
//            factory(\App\Domains\Models\Role::class)->create(),
//            factory(\App\Domains\Models\Role::class)->create(),
//            factory(\App\Domains\Models\Role::class)->create(),
//            factory(\App\Domains\Models\Role::class)->create(),
//        ];
//
//        $fpermissions = [
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//            factory(\App\Domains\Models\Permission::class)->create(),
//        ];
//
//        $count = 0;
//
//        $frolecurr = 0;
//
//        foreach(collect($fpermissions) as $perm ) {
//            $count++;
//
//            $perm->roles()->attach( $froles[$frolecurr] );
//
//            if( !($count % 5) )
//                $frolecurr++;
//        }
//
//        $count = 0;
//        $frolecurr = 0;
//
//        foreach( factory(\App\Domains\Models\User::class, 32)->create() as $user ) {
//            $count++;
//
//            $user->roles()->attach( $froles[$frolecurr] );
//
//            if( !($count % 8) )
//                $frolecurr++;
//        }
    }
}