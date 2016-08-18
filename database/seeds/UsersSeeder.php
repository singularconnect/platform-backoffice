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
        $permissionsRepository = app()->make('\App\Domains\Repositories\PermissionsRepository');

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

        $employee = $bruno->roles()->create(['id' => 'employee']);
        $this->i18n->set('roles.' . $employee->getKey(), 'Employee');
        $this->i18n->set('roles.' . $employee->getKey() . '_description', 'User employee standard');
        $this->i18n->set('pt.roles.' . $employee->getKey(), 'Funcionário');
        $this->i18n->set('pt.roles.' . $employee->getKey() . '_description', 'Usuário padrão para funcionários');

        //create roles
        $super_admin = $bruno->roles()->create(['id' => 'super_admin']);
        $this->i18n->set('roles.' . $super_admin->getKey(), 'Super Admin');
        $this->i18n->set('roles.' . $super_admin->getKey() . '_description', 'Root user, can do everything!!');
        $this->i18n->set('pt.roles.' . $super_admin->getKey(), 'Super Administrador');
        $this->i18n->set('pt.roles.' . $super_admin->getKey() . '_description', 'Usuário de nível mais alto, pode fazer qualquer coisa!!');

        $fernando->roles()->attach([$super_admin->getKey(), $employee->getKey()]);
        $roberto->roles()->attach([$super_admin->getKey(), $employee->getKey()]);
    }
}