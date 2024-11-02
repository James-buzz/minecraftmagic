# Standards

## Architecture Overview
- Following a layered architecture: Controllers → Services → Models
- Clear separation of concerns between business logic and presentation.
- Chose to not go with Repository pattern as it is not necessary for such a small project.

## Service Layer
- Contains all business logic and orchestration
- Throws custom exceptions for business rule violations or unexpected scenarios
- Custom exceptions should be descriptive and meaningful
- Handles complex operations involving multiple repositories

## Controller Layer (Presentation)
- Responsible for HTTP request/response handling
- Handles all exception catching and response formatting
- Returns consistent JSON response structures
- Uses appropriate HTTP status codes
- Example:
```php
public function store(CreateUserRequest $request)
{
    try {
        $user = $this->userService->createUser($request->validated());
        
        return response()->json([
            'data' => new UserResource($user),
            'message' => 'User created successfully'
        ], 201);
    } catch (UserAlreadyExistsException $e) {
        return response()->json([
            'message' => $e->getMessage()
        ], 409);
    } catch (Exception $e) {
        return response()->json([
            'message' => 'An unexpected error occurred'
        ], 500);
    }
}
```

## Exception Handling
- Custom exceptions for different business scenarios
- Centralized exception handling when possible
- Clear exception hierarchy
- Example exception structure:
```php
namespace App\Exceptions;

class UserAlreadyExistsException extends BusinessException
{
    protected $message = 'User already exists';
}
```

## Testing Strategy
- Repositories are designed to be easily mockable in unit tests
- Service layer tests use mocked repositories
- Feature tests cover full HTTP request/response lifecycle
- Unit tests typically mock all dependencies except for Repository unit tests which use an in-memory SQLite database

## HTTP Response Standards
- Utilise status codes over message in responses where possible
