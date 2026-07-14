# Login User

Login to buyer or seller account.

**Endpoint:** `POST /api/auth/login.php`

### Request
```json
{
  "email": "manish@example.com",
  "password": "securepassword123",
  "loginRole": "seller"
}
```

### Response
```json
{
  "success": true,
  "message": "Login successful!",
  "token": "your-token",
  "redirectTo": "manage_property.php"
}
```