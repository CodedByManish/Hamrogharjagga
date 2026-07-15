# Confirm Password Reset

Validates the password reset OTP code and updates the user's password in the DB.

**Endpoint:** `POST /api/auth/reset_confirm.php`

## Request

```json
{
  "email": "manish@example.com",
  "code": "194820",
  "new_password": "newsecurepassword123",
  "confirm_password": "newsecurepassword123"
}
```

## Response

```json
{
  "success": true,
  "message": "Password reset completed successfully!",
  "data": null,
  "redirectTo": "login_register.php"
}
```