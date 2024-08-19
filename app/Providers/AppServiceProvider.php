<?php

namespace App\Providers;

use App\Repositories\Interfaces\IQuizRepository;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\QuizRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\IQuizService;
use App\Services\Interfaces\IUserService;
use App\Services\QuizService;
use App\Services\UserService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     *
     * @var array
     */
    public $bindings = [
        IUserService::class => UserService::class,
        IUserRepository::class => UserRepository::class,
        IQuizService::class => QuizService::class,
        IQuizRepository::class => QuizRepository::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
