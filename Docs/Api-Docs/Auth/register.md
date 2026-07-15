# Register User

Register new buyer/seller account.

**Endpoint:** `POST /api/auth/register.php`

### Request
```json
{
  "name": "Manish Kafle",
  "email": "manish@example.com",
  "password": "securepassword123",
  "confirm_password": "securepassword123",
  "role": "buyer"
}
```

### Response
```json
{
  "success": true,
  "message": "Registration successful!",
  "token": "generated-token-key",
  "redirectTo": "find_property.php"
}
```