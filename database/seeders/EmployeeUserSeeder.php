<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeUserSeeder extends Seeder
{
    public function run(): void
    {
        $employees = Employee::all();

        foreach ($employees as $employee) {
            $email = strtolower(str_replace(' ', '.', $employee->employee_name)) . '@waskita.test';

            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'name' => $employee->employee_name,
                    'password' => Hash::make('password'),
                    'role' => 'pegawai',
                ]
            );

            $employee->update([
                'user_id' => $user->id,
            ]);
        }
    }
}