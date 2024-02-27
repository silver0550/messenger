<?php

namespace Tests\Feature;

use App\Enums\StatusEnum;
use App\Models\Message;
use App\Repositories\MessageRepository;
use App\Repositories\UserRepository;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Log;
use Tests\TestCase;
use Symfony\Component\HttpFoundation\Response as ResponseCode;

class MessageTest extends TestCase
{

    use RefreshDatabase;

    private readonly MessageRepository $messageRepository;
    private readonly UserRepository $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(PermissionSeeder::class);

        $this->messageRepository = resolve(MessageRepository::class);
        $this->userRepository = resolve(UserRepository::class);
    }

    public function testCanRegister(): void
    {
        $data = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
        ];

        $response = $this->post('/api/register', $data);

        $user = $this->userRepository->getFirst();

        $response->assertStatus(ResponseCode::HTTP_CREATED);
        $this->assertNotNull($user);
        $this->assertEquals($data['name'], $user->name);
        $this->assertEquals($data['email'], $user->email);
        $this->assertEquals(StatusEnum::ACTIVE, $user->status);
    }

    public function testCanLogin(): void
    {
        $data = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'device_name' => 'Notebook',
        ];

        $this->userRepository->create($data);
        $response = $this->post('/api/login', $data);

        $response->assertStatus(ResponseCode::HTTP_OK)
            ->assertJson(fn(AssertableJson $json) => $json->has('access_token')
            );
    }

    public function testForbiddenLoginIfWrongEmail(): void
    {
        $data = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'device_name' => 'Notebook',
        ];

        $this->userRepository->create($data);
        $data['email'] = 'wrong@email.com';
        $response = $this->post('/api/login', $data);

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN)
            ->assertJson(fn(AssertableJson $json) => $json->missing('access_token')
                ->where('message', __('auth.failed'))
            );
    }

    public function testForbiddenLoginIfWrongPassword(): void
    {
        $data = [
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
            'device_name' => 'Notebook',
        ];

        $this->userRepository->create($data);
        $data['password'] = 'wrongPassword';
        $response = $this->post('/api/login', $data);

        $response->assertStatus(ResponseCode::HTTP_FORBIDDEN)
            ->assertJson(fn(AssertableJson $json) => $json->missing('access_token')
                ->where('message', __('auth.failed'))
            );
    }

    public function testCanCreateMessage(): void
    {
        $accessToken = $this->createAndLoginWith('admin');

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$accessToken])
            ->post('/api/message', ['message' => 'Test message']);

        $message = $this->messageRepository->getFirst();

        $response->assertStatus(ResponseCode::HTTP_CREATED);
        $this->assertNotNull($message);
        $this->assertEquals('Test message', $message->message);
    }

    public function testCanRetrieveMessageWithoutParams(): void
    {
        $accessToken = $this->createAndLoginWith('Admin');
        $this->createUser('Tester');

        Message::factory(9)->create([
            'sender_id' => 1,
            'recipient_id' => 2,
        ]);
        Message::factory()->create([
            'sender_id' => 1,
            'recipient_id' => null,
            'message' => 'Test message',
        ]);

        Message::factory(10)->create([
            'sender_id' => 2,
            'recipient_id' => null,
        ]);

        $response = $this->withHeaders(['Authorization' => 'Bearer '.$accessToken])
            ->get('/api/message');

        $response->assertStatus(ResponseCode::HTTP_OK)
            ->assertJson(fn(AssertableJson $json) =>
                $json->where('0.sender', 'Admin')
                     ->where('0.recipient', 'Tester')
                     ->where('9.sender', 'Admin')
                     ->where('9.recipient', 'all')
                     ->where('9.message', 'Test message')
                     ->where('10.sender', 'Tester')
                     ->where('10.recipient', 'all')
            );
    }

    // Validation tests

    public function testRegisterHasEmailRequiredValidation(): void
    {
        $data = [
            'name' => 'admin',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['email' => __('validation.required', ['attribute' => 'E-mail'])]
        );

        $user = $this->userRepository->getFirst();
        $this->assertNull($user);
    }

    public function testRegisterHasEmailIsEmailValidation(): void
    {
        $data = [
            'name' => 'admin',
            'email' => 'fail_email_type.doc',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['email' => __('validation.email', ['attribute' => 'E-mail'])]
        );

        $user = $this->userRepository->getFirst();
        $this->assertNull($user);
    }

    public function testRegisterHasEmailUniqueValidation(): void
    {
        $data = [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
        ];

        $this->userRepository->create($data);

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['email' => __('validation.unique', ['attribute' => 'E-mail'])]
        );

        $user = $this->userRepository->getall();
        $this->assertCount(1, $user);
    }

    public function testRegisterHasNameRequiredValidation(): void
    {
        $data = [
            'email' => 'admin@admin.com',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['name' => __('validation.required', ['attribute' => 'Név'])]
        );

        $user = $this->userRepository->getFirst();
        $this->assertNull($user);
    }

    public function testRegisterHasNameNotNumberValidation(): void
    {
        $data = [
            'name' => 'admin12',
            'email' => 'fail_email_type.doc',
            'password' => 'password',
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['name' => __('validation.regex', ['attribute' => 'Név'])]
        );

        $user = $this->userRepository->getFirst();
        $this->assertNull($user);
    }

    public function testRegisterHasNameUniqueValidation(): void
    {
        $data = [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'password',
        ];

        $this->userRepository->create($data);

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['name' => __('validation.unique', ['attribute' => 'Név'])]
        );

        $user = $this->userRepository->getall();
        $this->assertCount(1, $user);
    }

    public function testRegisterHasPasswordRequiredValidation(): void
    {
        $data = [
            'name' => 'admin',
            'email' => 'admin@admin.com',
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['password' => __('validation.required', ['attribute' => 'Jelszó'])]
        );

        $user = $this->userRepository->getFirst();
        $this->assertNull($user);
    }

    public function testRegisterHasPasswordMinValidation(): void
    {
        $data = [
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'password' => 'low'
        ];

        $response = $this->json('POST', '/api/register', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['password' => __('validation.min.string', ['attribute' => 'Jelszó', 'min' => '8'])]
        );

        $user = $this->userRepository->getFirst();
        $this->assertNull($user);
    }

    public function testCreateMessageHasMessageRequiredValidation(): void
    {
        $data = [
            'message' => 'Test message',
        ];

        $response = $this->json('POST', '/api/message', $data);

        $response->assertStatus(ResponseCode::HTTP_UNPROCESSABLE_ENTITY);
        $response->assertInValid(
            ['password' => __('validation.required', ['attribute' => 'Jelszó'])]
        );

        $user = $this->userRepository->getFirst();
        $this->assertNull($user);
    }

    private function createUser(string $name): void
    {
        $this->userRepository->create([
            'name' => $name,
            'email' => $name.'@test.com',
            'password' => 'password',
        ]);
    }

    private function createAndLoginWith(string $name): string
    {
        $this->createUser($name);

        $loginData = [
            'email' => $name.'@test.com',
            'password' => 'password',
            'device_name' => 'notebook',
        ];

        return $this->post('/api/login', $loginData)->json('access_token');
    }
}
