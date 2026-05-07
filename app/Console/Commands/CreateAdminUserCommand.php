<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUserCommand extends Command
{
    protected $signature = 'app:create-admin
        {--name= : Имя администратора}
        {--email= : Email администратора}
        {--password= : Пароль администратора}';

    protected $description = 'Создать администратора для доступа в админ-панель';

    public function handle(): int
    {
        $name = $this->option('name') ?: $this->ask('Имя администратора');
        $email = $this->option('email') ?: $this->ask('Email администратора');
        $password = $this->option('password') ?: $this->secret('Пароль администратора (минимум 8 символов)');

        $validator = Validator::make([
            'name' => $name,
            'email' => $email,
            'password' => $password,
        ], [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'email' => ['required', 'email:rfc,dns', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $existingUser = User::query()->where('email', $email)->first();

        if ($existingUser) {
            if ($existingUser->role === 'admin') {
                $this->warn("Пользователь {$email} уже является администратором.");
                return self::SUCCESS;
            }

            if (! $this->confirm("Пользователь {$email} уже существует. Назначить ему роль admin?")) {
                $this->info('Операция отменена.');
                return self::SUCCESS;
            }

            $existingUser->update(['role' => 'admin']);
            $this->info("Роль пользователя {$email} обновлена до admin.");

            return self::SUCCESS;
        }

        // Создаем нового администратора только после прохождения всех проверок.
        $user = User::query()->create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'role' => 'admin',
        ]);

        $this->info("Администратор {$user->email} успешно создан.");

        return self::SUCCESS;
    }
}

