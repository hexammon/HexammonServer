<?php

use Phinx\Migration\AbstractMigration;

class CreateUserTable extends AbstractMigration
{

    public function change()
    {
        $userTable = $this->table('user', [
            'engine' => 'InnoDb',
        ]);
        $userTable->addColumn('login', 'string', ['limit' => '255', 'null' => false]);
        $userTable->addColumn('email', 'string', ['limit' => '255', 'null' => false]);
        $userTable->addColumn('password_hash', 'string', ['limit' => '255', 'null' => false]);
        $userTable->create();
    }
}
