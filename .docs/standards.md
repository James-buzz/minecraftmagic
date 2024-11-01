# Standards

## Architecture Overview
- Following a layered architecture: Controllers → Services → Repositories → Models
- Clear separation of concerns between data access, business logic, and presentation
- Consistent error handling and response patterns across the application

## Repository Layer
- Repositories abstract all database operations and data access
- Return `null` when entities are not found rather than throwing exceptions
- All database queries are encapsulated within repository methods
- Methods should have clear, descriptive names indicating their purpose
- Example:
```php
/**
 * Find a user by id
 * 
 * @param int $id
 * @return array{id: int, name: string}|null
 */
public function findById(int $id): array
{
    return $this->model->find($id);
}
```

## Service Layer
- Contains all business logic and orchestration
- Throws custom exceptions for business rule violations or unexpected scenarios
- Custom exceptions should be descriptive and meaningful
- Handles complex operations involving multiple repositories
- Example:
```php
/**
 * Create a new user
 * 
 * @param array<string, mixed> $data 
 * @return int
 */
public function createUser(array $data): int
{
    if ($this->userRepository->findByEmail($data['email'])) {
        throw new UserAlreadyExistsException('A user with this email already exists');
    }
    
    return $this->userRepository->create($data);
}
```

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
