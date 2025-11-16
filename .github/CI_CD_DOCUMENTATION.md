# CI/CD Pipeline Documentation

This repository uses GitHub Actions for automated testing, code quality checks, and deployment.

## 🔄 CI/CD Flow

The CI/CD pipeline follows a **strict quality gate** approach:

```
Push to main ──┬─► Laravel Tests ──┬─► Code Quality ──┬─► Quality Gate Check ──┬─► Deploy to Production
               │                  │                  │                        │
               └─► ❌ Fail        └─► ❌ Fail        └─► ❌ Block Deployment   └─► ✅ Deploy + Auto Migrate
```

### Quality Gates Process
1. **Laravel Tests** run automatically on push to main
2. **Code Quality** checks run automatically on push to main  
3. **Deploy workflow** checks the status of both prerequisite workflows
4. **Only if both pass**: Deployment proceeds with automatic database migration
5. **If either fails**: Deployment is blocked and the workflow fails with clear error message

### Auto-Migration Safety
- Database migrations run automatically during successful deployments
- Uses `--force` flag to ensure migrations run in production
- Prevents deployment with outdated database schema
- All migrations are tracked and version-controlled

## 🚀 Setup Instructions
Runs on every push and pull request to `main` and `develop` branches.

**What it does:**
- Sets up PHP 8.4 with required extensions
- Installs Composer and NPM dependencies
- Builds frontend assets with Vite
- Runs PostgreSQL 17 service for testing
- Executes all PHPUnit tests with coverage reporting
- Uploads coverage reports as artifacts

**Triggers:**
- Push to `main` or `develop`
- Pull requests to `main` or `develop`

### 2. **Code Quality** (`.github/workflows/code-quality.yml`)
Ensures code meets quality standards.

**What it does:**
- Runs Laravel Pint for code style checking
- Checks for security vulnerabilities with `composer audit`

**Triggers:**
- Push to `main` or `develop`
- Pull requests to `main` or `develop`

### 3. **Deploy to Production** (`.github/workflows/deploy.yml`)
Automated deployment with strict quality gates and auto-migration.

**What it does:**
- Checks the latest status of both Laravel Tests and Code Quality workflows
- Only proceeds with deployment if both quality gates pass
- Builds production-ready assets and deploys to DigitalOcean Droplet
- **Automatically runs database migrations** during deployment
- Fails the entire workflow if quality checks fail

**Triggers:**
- Push to `main` branch (after quality checks complete)
- Manual workflow dispatch for emergency deployments

**Quality Gates:**
- ✅ **Laravel Tests** must have completed successfully
- ✅ **Code Quality** checks must have completed successfully
- ❌ If either fails, deployment is blocked and workflow fails

**Auto-Migration Features:**
- Runs `php artisan migrate --force` automatically
- Ensures database schema is always up-to-date
- Prevents deployment with outdated database structure

## 🚀 Setup Instructions

### GitHub Secrets (Required)
Configure these in your GitHub repository settings under **Settings → Secrets and variables → Actions**:

#### For DigitalOcean Droplet Deployment:
```
DROPLET_HOST: your-droplet-ip-address (e.g., 192.168.1.100)
DROPLET_USERNAME: your-ssh-username (e.g., root or deploy-user)
DROPLET_PASSWORD: your-ssh-password
DROPLET_PORT: 22 (optional, defaults to 22)
DEPLOY_PATH: /var/www/html (optional, defaults to /var/www/html)
```

**Note:** Using password authentication is enabled. For better security, consider switching to SSH key authentication in the future.

### Local Testing
Test workflows locally using [act](https://github.com/nektos/act):

```bash
# Install act
brew install act  # macOS
# or
curl https://raw.githubusercontent.com/nektos/act/master/install.sh | sudo bash  # Linux

# Run tests workflow
act push -W .github/workflows/laravel-tests.yml

# Run code quality checks
act push -W .github/workflows/code-quality.yml
```

## 📊 Test Coverage

The test workflow generates coverage reports with a minimum threshold of 80%. Coverage reports are uploaded as artifacts and can be downloaded from the Actions tab.

## 🔧 Customizing Workflows

### Changing PHP Version
Edit the PHP version in workflow files:
```yaml
- name: Setup PHP
  uses: shivammathur/setup-php@v2
  with:
    php-version: '8.4'  # Change this
```

### Adding More Branches
Add branches to the trigger configuration:
```yaml
on:
  push:
    branches: [ main, develop, staging ]
```

### Modifying Test Database
Update the PostgreSQL service configuration:
```yaml
services:
  postgres:
    image: postgres:17
    env:
      POSTGRES_DB: your_db_name
```

## 🎯 Deployment Options

The deploy workflow is configured for **DigitalOcean Droplet** deployment using password-based SSH authentication.

### Deployment Process:
1. Connects to your droplet via SSH using password
2. Pulls latest code from `main` branch
3. Installs production dependencies
4. Builds frontend assets
5. **Automatically runs database migrations** (`php artisan migrate --force`)
6. Clears and caches Laravel configurations
7. Restarts queue workers
8. Sets proper file permissions

### Auto-Migration Details:
- **Automatic**: Migrations run on every successful deployment
- **Forced**: Uses `--force` flag for production safety
- **Versioned**: All migrations are tracked in your repository
- **Safe**: Only runs after all quality checks pass
- **Logged**: Migration output is visible in deployment logs

### Prerequisites on Your Droplet:
- Git repository cloned at deployment path
- PHP 8.4+ installed
- Composer installed globally
- Node.js and NPM installed
- Web server (Nginx/Apache) configured
- PostgreSQL database setup
- Proper sudo permissions for www-data ownership

### First-Time Droplet Setup:
```bash
# Clone your repository
cd /var/www
git clone https://github.com/aunghtet-star/beanstack_e-commerce-small-enterprise-structure.git html
cd html

# Set up environment
cp .env.example .env
nano .env  # Configure database and app settings

# Install dependencies
composer install
npm install

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate

# Set permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

## 📈 Workflow Status

Add status badges to your README:

```markdown
![Laravel Tests](https://github.com/aunghtet-star/beanstack_e-commerce-small-enterprise-structure/workflows/Laravel%20Tests/badge.svg)
![Code Quality](https://github.com/aunghtet-star/beanstack_e-commerce-small-enterprise-structure/workflows/Code%20Quality/badge.svg)
```

## 🐛 Troubleshooting

### Tests Failing Locally but Pass in CI
- Check PHP version matches (8.4)
- Ensure PostgreSQL is running
- Verify environment variables

### Code Quality Checks Failing
Run locally:
```bash
./vendor/bin/pint        # Fix code style
composer audit           # Check security
```

### Deployment Not Triggering
- Check that both Laravel Tests and Code Quality workflows completed successfully on the main branch
- Verify the push was made directly to the `main` branch
- Look for "Quality Gate Check" failures in the deployment workflow logs

### Quality Gates Failing
- Ensure Laravel Tests workflow shows "success" status
- Ensure Code Quality workflow shows "success" status  
- Check that workflows completed recently (within the same push cycle)
- Manual workflow dispatch bypasses quality gates

### Migration Issues During Deployment
- Check database connection in production `.env`
- Verify database user has migration permissions
- Review migration logs in deployment output
- Ensure no breaking changes in migrations
- Verify all secrets are configured
- Check server SSH access
- Ensure deployment paths are correct

## 📝 Next Steps

1. **Add Environment-Specific Configs**: Create separate workflows for staging
2. **Enable Branch Protection**: Require CI checks to pass before merging
3. **Add Slack/Discord Notifications**: Get notified on failures
4. **Implement Database Backups**: Before production deployments
5. **Add Performance Testing**: Monitor application performance

## 🔐 Security Notes

- Never commit secrets to the repository
- Use GitHub Secrets for sensitive data
- Rotate SSH keys and API tokens regularly
- Enable two-factor authentication on GitHub
- Review dependency vulnerabilities regularly with `composer audit`
