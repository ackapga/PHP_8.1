<?php

namespace Http\Actions;

use Ackapga\Habrahabr\Blog\UUID;
use Ackapga\Habrahabr\Exceptions\UserNotFoundException;
use Ackapga\Habrahabr\Http\Actions\Users\FindByUsername;
use Ackapga\Habrahabr\Http\ErrorResponse;
use Ackapga\Habrahabr\Http\Request;
use Ackapga\Habrahabr\Http\SuccessfulResponse;
use Ackapga\Habrahabr\Interfaces\UsersRepositoryInterface;
use Ackapga\Habrahabr\Person\Name;
use Ackapga\Habrahabr\Person\User;
use PHPUnit\Framework\TestCase;

class FindByUsernameActionTest extends TestCase
{
    /**
     * Запускаем тест в отдельном процессе
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */

// Тест, проверяющий, что будет возвращён неудачный ответ, если в запросе нет параметра username
    public function testItReturnsErrorResponseIfNoUsernameProvided(): void
    {
        $request = new Request([], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"No such query param in the request: username"}');

        $response->send();
    }

    /**
     * Запускаем тест в отдельном процессе
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
// Тест, проверяющий, что будет возвращён неудачный ответ, если пользователь не найден
    public function testItReturnsErrorResponseIfUserNotFound(): void
    {

        $request = new Request(['username' => 'ackapga'], [], '');

        $usersRepository = $this->usersRepository([]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(ErrorResponse::class, $response);

        $this->expectOutputString('{"success":false,"reason":"Not found"}');

        $response->send();
    }

    /**
     * Запускаем тест в отдельном процессе
     * @runInSeparateProcess
     * @preserveGlobalState disabled
     */
// Тест, проверяющий, что будет возвращён удачный ответ, если пользователь найден
    public function testItReturnsSuccessfulResponse(): void
    {
        $request = new Request(['username' => 'ackapga'], [], '');

        $usersRepository = $this->usersRepository([
            new User(
                UUID::random(),
                'ackapga',
                new Name('Ackap', 'Maemgenov')
            ),
        ]);

        $action = new FindByUsername($usersRepository);

        $response = $action->handle($request);

        $this->assertInstanceOf(SuccessfulResponse::class, $response);

        $this->expectOutputString('{"success":true,"data":{"username":"ackapga","name":"Ackap Maemgenov"}}');

        $response->send();
    }

// Функция, создающая STAB репозитория пользователей, принимает массив "существующих" пользователей
    private function usersRepository(array $users): UsersRepositoryInterface
    {

        return new class($users) implements UsersRepositoryInterface {
            public function __construct(
                private array $users
            )
            {
            }

            public function save(User $user): void
            {
            }

            public function get(UUID $uuid): User
            {
                throw new UserNotFoundException("Not found");
            }

            public function getByUsername(string $username): User
            {
                foreach ($this->users as $user) {
                    if ($user instanceof User && $username === $user->getUsername()) {
                        return $user;
                    }
                }
                throw new UserNotFoundException("Not found");
            }
        };
    }


}