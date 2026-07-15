<?php

namespace App\Models;

class UserModel {
    public ?int $id = null;
    public string $name;
    public string $email;
    public string $password;
    public string $role;
    public ?string $createdAt = null;

    public function __construct(array $data = []) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'] ?? '';
        $this->email = $data['email'] ?? '';
        $this->password = $data['password'] ?? '';
        $this->role = $data['role'] ?? '';
        $this->createdAt = $data['created_at'] ?? null;
    }
}