# CI/CD Pipeline Documentation

This repository uses GitHub Actions for automated testing, code quality checks, and deployment.

## 🔄 Workflows

### 1. **Laravel Tests** (`.github/workflows/laravel-tests.yml`)
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
Automated deployment workflow (currently template-based).

**What it does:**
- Builds production-ready assets
- Installs production dependencies (no dev packages)
- Provides deployment templates for common platforms

**Triggers:**
- Push to `main`
- Manual workflow dispatch

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
5. Runs database migrations
6. Clears and caches Laravel configurations
7. Restarts queue workers
8. Sets proper file permissions

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

### Deployment Issues
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
