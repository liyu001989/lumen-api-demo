<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class CreateUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $email = $this->ask('email');

        if (User::where('email', $email)->exists()) {
            $this->error('User already exists!');
            exit;
        }

        $user = User::create([
            'email' => $email,
            'password' => app('hash')->make('123456'),
        ]);

        $this->info('Success');
        $this->line('email: '.$email);
        $this->line('password: 123456');
    }
}
