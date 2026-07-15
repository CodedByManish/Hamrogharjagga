# Verify Email

Validates the registration OTP code, activates the account, and returns an access token.

**Endpoint:** `POST /api/auth/verify_email.php`

## Request

```json
{
  "email": "manish@example.com",
  "code": "583921"
}
```

## Response

```json
{
  "success": true,
  "message": "Email verified successfully!",
  "data": {
    "token": "generated-bearer-token-string"
  },
  "redirectTo": "find_property.php"
}
```