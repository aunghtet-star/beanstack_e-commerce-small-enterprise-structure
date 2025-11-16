# Multi-Auth System Test Suite

## Test Coverage

### Unit Tests

#### UserModelTest (`tests/Unit/UserModelTest.php`)
- ✅ Verifies admin role checking via `isAdmin()` method
- ✅ Verifies regular user role checking via `isUser()` method
- ✅ Ensures default role is 'user' for new users
- ✅ Tests role attribute can be set during user creation
- ✅ Tests role can be updated after creation

#### IsAdminMiddlewareTest (`tests/Unit/IsAdminMiddlewareTest.php`)
- ✅ Allows admin users to access protected routes
- ✅ Blocks regular users from admin routes (throws HTTP 403)
- ✅ Blocks unauthenticated users from admin routes (throws HTTP 403)

### Feature Tests

#### MultiAuthTest (`tests/Feature/MultiAuthTest.php`)
- ✅ Admin users redirected to `/admin/dashboard` after login
- ✅ Regular users redirected to `/dashboard` after login
- ✅ Unauthenticated users cannot access dashboard (redirected to login)
- ✅ Unauthenticated users cannot access admin dashboard (redirected to login)
- ✅ Login fails with invalid credentials
- ✅ Users can logout successfully

#### AdminDashboardTest (`tests/Feature/AdminDashboardTest.php`)
- ✅ Admin users can access admin dashboard
- ✅ Regular users receive 403 Forbidden on admin dashboard
- ✅ Guest users redirected to login page
- ✅ Admin dashboard displays correct statistics (total users, admin users, products, stock)
- ✅ Admin middleware properly applied to admin routes

## Running Tests

### Run All Multi-Auth Tests
```bash
./vendor/bin/sail test --filter="UserModelTest|IsAdminMiddlewareTest|MultiAuthTest|AdminDashboardTest"
```

### Run All Tests
```bash
./vendor/bin/sail test
```

### Run Specific Test Suite
```bash
# Unit tests only
./vendor/bin/sail test --testsuite=Unit

# Feature tests only
./vendor/bin/sail test --testsuite=Feature
```

### Run Specific Test File
```bash
./vendor/bin/sail test tests/Unit/UserModelTest.php
```

## Test Database Configuration

Tests use a separate PostgreSQL database configured in `phpunit.xml`:
- Database: `testing`
- Connection: `pgsql`
- Uses `RefreshDatabase` trait to reset database between tests

## Test Users

### Created in Tests
- **Admin User**: `admin@test.com` / `password` (role: admin)
- **Regular User**: `user@test.com` / `password` (role: user)

### Created by Seeders (Development)
- **Admin**: `admin@beanstack.com` / `password`
- **User**: `user@beanstack.com` / `password`

## Coverage Summary

**Total Tests**: 19 tests across 4 test classes  
**Passing**: 19/19 (100%)  
**Assertions**: 63 total assertions

### Coverage Areas
1. **User Model**: Role management and helper methods
2. **Middleware**: Access control and authorization
3. **Authentication Flow**: Login, logout, and redirects
4. **Dashboard Access**: Role-based route protection
5. **Statistics**: Admin dashboard data accuracy
