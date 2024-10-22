<?php

namespace Tests\Unit;

use App\Models\Task\Enum\TaskPriorityEnum;
use App\Models\Task\Enum\TaskStatusEnum;
use App\Models\Task\Task;
use App\Models\User;
use App\Services\AuthService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthService();
    }

    public function testRegister()
    {
        // Arrange
        $requestData = [
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ];

        // Act
        $response = $this->postJson('api/auth/register', $requestData);

        // Assert
        $response->assertStatus(201)
            ->assertJsonStructure(['data' => ['id', 'email', 'access_token']]);
    }

    public function testLogin()
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Arrange
        $requestData = [
            'email' => 'test@example.com',
            'password' => 'password',
        ];

        // Act
        $response = $this->postJson('api/auth/login', $requestData);

        // Assert
        $response->assertStatus(200)
            ->assertJsonStructure(['data' => ['id', 'email', 'access_token']]);
    }

    public function testForgotPassword()
    {
        // Arrange
        $email = 'test@example.com';

        User::factory()->create([
            'email' => $email,
        ]);

        $requestData = [
            'email' => $email,
        ];

        // Act
        $response = $this->postJson('api/auth/forgot-password', $requestData);

        // Assert
        $response->assertStatus(200);
        $this->assertDatabaseHas('password_resets', [
            'email' => $email,
        ]);
    }

    public function testResetPassword()
    {
        $email = 'test@example.com';
        $token = 'reset-token';
        $newPassword = 'new-password';

        $user = User::factory()->create([
            'email' => $email,
        ]);

        DB::table('password_resets')->insert([
            'email' => $email,
            'token' => Hash::make($token),
        ]);

        // Arrange
        $requestData = [
            'email' => $email,
            'password' => $newPassword,
            'password_confirmation' => $newPassword,
            'token' => $token,
        ];

        // Act
        $response = $this->postJson('api/auth/reset-password', $requestData);

        // Assert
        $response->assertStatus(200);
        $user->refresh();
        $this->assertTrue(Hash::check($newPassword, $user->password));
    }
}
