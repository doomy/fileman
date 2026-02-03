# FileMan - File Management Web Application

A micro-web application for managing uploaded files in a specified location, similar to CKFinder functionality.

## 🏗️ Architecture

**Monorepo** structure with backend and frontend in a single repository:
- **Backend:** PHP 8.4+ REST API using Restopus framework (github.com/doomy/restopus) built on Nette
- **Frontend:** React 18+ with TypeScript, Vite, and TanStack Query
- **Infrastructure:** Docker with Nginx reverse proxy

## ✨ Features

- **File Operations:** Upload, download, delete, rename, move, copy files
- **Directory Management:** Create, delete, rename directories, navigate tree structure
- **File Viewing:** List/grid views with sorting, search, filtering
- **File Information:** Size, MIME type, dates, image dimensions
- **Security:** File type validation, size limits, path traversal prevention, authentication

## 📋 Prerequisites

- **Docker** and **Docker Compose** (recommended for development)
- **PHP 8.4+** with Composer (if running backend locally)
- **Node.js 18+** with npm (if running frontend locally)

## 🚀 Quick Start

### With Docker (Recommended)

1. **Clone the repository:**
   ```bash
   git clone <repository-url>
   cd fileman
   ```

2. **Set up environment variables:**
   ```bash
   cp .env.example .env
   # Edit .env with your configuration
   ```

3. **Start the entire stack:**
   ```bash
   docker-compose up
   ```

4. **Access the application:**
   - Frontend: http://localhost:3000
   - Backend API: http://localhost:8080/api
   - Nginx Proxy: http://localhost:80

### Without Docker

#### Backend Setup
```bash
cd backend
composer install
cp .env.example .env
# Configure backend/.env
php -S localhost:8080 -t public
```

#### Frontend Setup
```bash
cd frontend
npm install
cp .env.example .env
# Configure frontend/.env with VITE_API_URL=http://localhost:8080/api
npm run dev
```

## 📁 Project Structure

```
fileman/                          # Root monorepo
├── backend/                      # PHP 8.4+ REST API with Restopus
│   ├── www/                      # Entry point
│   ├── src/                      # Application code (Presenters, Services, etc.)
│   ├── config/                   # Nette configuration files (config.neon)
│   ├── storage/uploads/          # File storage
│   └── tests/                    # PHPUnit tests
├── frontend/                     # React + TypeScript SPA
│   ├── src/                      # React components, hooks, services
│   ├── public/                   # Static assets
│   └── tests/                    # Frontend tests
├── docker/                       # Docker configuration
│   ├── nginx/                    # Nginx reverse proxy
│   ├── php/                      # PHP 8.4 Dockerfile
│   └── node/                     # Node Dockerfile
└── docs/                         # Documentation
```

## 🧪 Testing

### Backend Tests
```bash
cd backend
composer test
# or
./vendor/bin/phpunit
```

### Frontend Tests
```bash
cd frontend
npm test
```

## 🔧 Development

### Backend Development
```bash
# Install dependencies
cd backend && composer install

# Run tests
composer test

# Check code standards
composer run-script phpcs
```

### Frontend Development
```bash
# Install dependencies
cd frontend && npm install

# Start dev server
npm run dev

# Build for production
npm run build

# Run linter
npm run lint

# Type check
npm run typecheck
```

## 🐳 Docker Commands

```bash
# Start services
docker-compose up

# Start with rebuild
docker-compose up --build

# Stop services
docker-compose down

# View logs
docker-compose logs -f
```

## 📚 Documentation

- [Plan & Development Phases](plan.MD)
- [Warp AI Integration Guide](WARP.md)
- API Documentation (TBD)
- Deployment Guide (TBD)
- Development Setup Guide (TBD)

## 🔐 Security

This application implements several security measures:
- Path traversal prevention
- File type validation and whitelisting
- File size restrictions
- Authentication middleware
- CORS configuration
- Input sanitization

## 🛠️ Technology Stack

### Backend
- PHP 8.4+ (property hooks, asymmetric visibility, JIT)
- Restopus framework (github.com/doomy/restopus) - attribute-based REST API
- Nette Framework (foundation for Restopus)
- Composer
- PHPUnit
- JWT or session-based authentication via Restopus #[Authenticated] attribute

### Frontend
- React 18+
- TypeScript
- Vite
- TanStack Query (React Query)
- Axios
- TailwindCSS or Material-UI
- Vitest or Jest

### DevOps
- Docker & Docker Compose
- Nginx
- PHP-FPM
- Git

## 📝 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/files` | List files in directory |
| GET | `/api/files/{path}` | Get file metadata |
| POST | `/api/files/upload` | Upload files |
| GET | `/api/files/download/{path}` | Download file |
| DELETE | `/api/files/{path}` | Delete file |
| PUT | `/api/files/{path}/rename` | Rename file |
| POST | `/api/files/{path}/copy` | Copy file |
| POST | `/api/files/{path}/move` | Move file |
| GET | `/api/files/search` | Search files |
| POST | `/api/directories` | Create directory |
| DELETE | `/api/directories/{path}` | Delete directory |
| PUT | `/api/directories/{path}/rename` | Rename directory |
| GET | `/api/directories/tree` | Get directory tree |

## 🤝 Contributing

1. Create a feature branch
2. Make your changes
3. Write or update tests
4. Ensure tests pass and code standards are met
5. Submit a pull request

## 📄 License

[Add your license here]

## 🙋 Support

For issues or questions, please [open an issue](link-to-issues) in the repository.

---

**Status:** 🚧 In Development - Phase 1: Project Setup & Infrastructure
