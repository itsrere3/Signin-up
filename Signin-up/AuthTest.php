<?php
// Auth class (simulated login)
class Auth {
    private $users = [
        ["email" => "test@example.com", "password" => "12345"],
        ["email" => "admin@example.com", "password" => "admin123"]
    ];

    public function login($email, $password) {
        foreach ($this->users as $user) {
            if ($user["email"] === $email && $user["password"] === $password) {
                return true; // login success
            }
        }
        return false; // login failed
    }
}

// Simulated test class with custom assert
class AuthTest {

    private function assertEquals($expected, $actual, $message) {
        if ($expected === $actual) {
            echo "✅ Test Passed: $message\n";
        } else {
            echo "❌ Test Failed: $message — Expected $expected, got $actual\n";
        }
    }

    // Test login with correct data
    public function testValidLogin() {
        $auth = new Auth();
        $result = $auth->login("test@example.com", "12345");
        $this->assertEquals(true, $result, "Login should succeed with correct credentials");
    }

    // Test login with wrong password
    public function testInvalidLogin() {
        $auth = new Auth();
        $result = $auth->login("test@example.com", "wrongpass");
        $this->assertEquals(false, $result, "Login should fail with incorrect password");
    }

    // Test login with email not found
    public function testUnknownUser() {
        $auth = new Auth();
        $result = $auth->login("unknown@example.com", "12345");
        $this->assertEquals(false, $result, "Login should fail for unknown email");
    }

    // Test login with empty input
    public function testEmptyFields() {
        $auth = new Auth();
        $result = $auth->login("", "");
        $this->assertEquals(false, $result, "Login should fail when fields are empty");
    }
}

// Run simulated tests
$test = new AuthTest();
$test->testValidLogin();
$test->testInvalidLogin();
$test->testUnknownUser();
$test->testEmptyFields();
?>
