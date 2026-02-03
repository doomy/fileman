# WARP.md

This file provides guidance to WARP (warp.dev) when working with code in this repository.

## Project Overview

A micro-web application for managing uploaded files, similar to CKFinder. **Monorepo** architecture with backend and frontend in a single repository.

- **Backend:** PHP 8.4+ REST API using Restopus framework (https://github.com/doomy/restopus) built on Nette
- **Frontend:** React 18+ with TypeScript, Vite, TanStack Query
- **Infrastructure:** Docker with Nginx reverse proxy

## Project Structure

```
fileman/                          # Root monorepo
├── backend/                      # PHP 8.4+ REST API with Restopus
│   ├── www/index.php             # API entry point
│   ├── src/
│   │   ├── Presenter/            # FilePresenter, DirectoryPresenter (Restopus pattern)
│   │   ├── RequestBody/          # Request body validation classes
│   │   ├── Services/             # FileSystemService, ValidationService
│   │   ├── Models/
│   │   └── Utils/                # PathValidator
│   ├── config/config.neon        # Nette configuration
│   ├── storage/uploads/          # File storage location
│   └── tests/
├── frontend/                     # React + TypeScript SPA
│   ├── src/
│   │   ├── components/           # FileList, FileGrid, DirectoryTree, UploadZone
│   │   ├── pages/                # FileManager main page
│   │   ├── services/api.ts       # API client with Axios
│   │   ├── hooks/                # useFiles, useDirectories, useFileOperations
│   │   └── types/                # TypeScript interfaces
│   └── tests/
├── docker/                       # Docker configuration
│   ├── nginx/                    # Reverse proxy config
│   ├── php/                      # PHP 8.4 Dockerfile
│   └── node/                     # Node Dockerfile
└── docs/                         # API.md, DEPLOYMENT.md, DEVELOPMENT.md
```

## Common Commands

### Development Environment

```bash
# Start entire stack with Docker
docker-compose up

# Start with rebuild
docker-compose up --build

# Stop all services
docker-compose down
```

### Backend (PHP with Restopus)

```bash
# Install dependencies (includes doomy/restopus)
cd backend && composer install

# Run PHPUnit tests
cd backend && composer test
# OR
cd backend && ./vendor/bin/phpunit

# Run single test file
cd backend && ./vendor/bin/phpunit tests/Unit/FileSystemServiceTest.php

# Check code standards (ECS - Easy Coding Standard)
cd backend && composer run-script ecs
```

### Frontend (React + TypeScript)

```bash
# Install dependencies
cd frontend && npm install

# Start dev server
cd frontend && npm run dev

# Build for production
cd frontend && npm run build

# Run tests (Vitest or Jest)
cd frontend && npm test

# Run single test file
cd frontend && npm test FileList.test.tsx

# Lint
cd frontend && npm run lint

# Type check
cd frontend && npm run typecheck

# Format with Prettier
cd frontend && npm run format
```

## Architecture & Key Concepts

### Backend Architecture

**RESTful API Design with Restopus:**
- All endpoints under `/api/` prefix
- Presenters (not Controllers) handle HTTP layer using Restopus attributes
- Attribute-based routing: #[Route('/path')], #[HttpMethod(HttpRequestMethod::POST)]
- Authentication via #[Authenticated(userEntityClass: User::class)] attribute
- Request validation via #[RequestBody(RequestBodyClass::class)] attribute
- Services contain business logic and file system operations
- Nette framework provides foundation (DI container, routing)

**Security-Critical Components:**
- **PathValidator:** Prevents directory traversal attacks using `realpath()` validation against base directory
- **ValidationService:** File type whitelist/blacklist, size limits, MIME type verification
- **Restopus #[Authenticated]:** Built-in authentication attribute for protected endpoints
- **Request Body Classes:** Type-safe request validation using #[RequestBody] attribute

**File System Service:**
- Central service for all file operations
- Atomic operations with proper file locking
- Metadata extraction (size, MIME, dates, image dimensions)
- Path resolution relative to configured upload directory

### Frontend Architecture

**State Management:**
- TanStack Query (React Query) for server state, caching, and optimistic updates
- Custom hooks abstract API calls: `useFiles(path)`, `useUpload()`, `useFileOperations()`
- No global state management needed (Zustand/Redux) - server state handles everything

**Component Organization:**
- **Presentation components:** FileList, FileGrid, FileItem (display only)
- **Container components:** FileManager page (orchestrates operations)
- **Utility components:** DirectoryTree (navigation), UploadZone (drag-drop)

**API Client Layer:**
- Single Axios instance with interceptors for auth tokens and error handling
- TypeScript interfaces for all API responses
- Retry logic for failed requests

### Monorepo Workflow

- **Single commit** for coordinated backend/frontend changes (e.g., API endpoint rename)
- **Unified versioning** - tag releases for entire application
- **Docker Compose** orchestrates both services for development and production
- Environment variables managed at root level in `.env`

## API Endpoints Reference

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/files` | List files in directory |
| GET | `/api/files/{path}` | Get file metadata |
| POST | `/api/files/upload` | Upload files (multipart/form-data) |
| GET | `/api/files/download/{path}` | Download file |
| DELETE | `/api/files/{path}` | Delete file |
| PUT | `/api/files/{path}/rename` | Rename file |
| POST | `/api/files/{path}/copy` | Copy file |
| POST | `/api/files/{path}/move` | Move file |
| GET | `/api/files/search?q={query}` | Search files by name |
| POST | `/api/directories` | Create directory |
| DELETE | `/api/directories/{path}` | Delete directory (with contents) |
| PUT | `/api/directories/{path}/rename` | Rename directory |
| GET | `/api/directories/tree` | Get directory tree structure |

## Security Considerations

When implementing file operations:

1. **Path Validation:** Always use `PathValidator` to prevent traversal attacks
2. **File Upload:** Validate MIME type, check against whitelist, enforce size limits
3. **File Names:** Sanitize on upload (remove special characters, prevent overwrite attacks)
4. **Authentication:** All endpoints except health checks require valid auth token/session
5. **CORS:** Configured for frontend origin only

## Testing Strategy

### Backend Tests
- **Unit tests:** Services in isolation (FileSystemService, ValidationService)
- **Integration tests:** Full request/response cycle through controllers
- **Security tests:** Path traversal, malicious uploads, unauthorized access

### Frontend Tests
- **Component tests:** React Testing Library for UI components
- **Hook tests:** Test custom hooks with mock API responses
- **Integration tests:** User flows (upload file → see in list → delete)
- **E2E tests (optional):** Playwright/Cypress for critical paths

## Configuration

### Environment Variables

Backend (`backend/.env`):
- `UPLOAD_DIR` - Base directory for file storage
- `MAX_FILE_SIZE` - Maximum upload size in bytes
- `ALLOWED_FILE_TYPES` - Comma-separated MIME types or extensions
- `JWT_SECRET` - Secret for JWT token generation (if using JWT)
- `CORS_ORIGIN` - Allowed frontend origin

Frontend (`frontend/.env`):
- `VITE_API_URL` - Backend API base URL (e.g., `http://localhost:8080/api`)

### Docker Ports

- **Frontend:** http://localhost:3000 (Vite dev server)
- **Backend:** http://localhost:8080 (PHP-FPM via Nginx)
- **Nginx Proxy:** http://localhost:80 (routes to both services)

## PHP 8.4 Specific Features

This project uses PHP 8.4's latest features:
- **Property hooks:** For encapsulated property access in models
- **Asymmetric visibility:** Public getters with private setters
- **JIT compilation:** Enabled in production for performance
- **Enhanced error handling:** Leveraging improved stack traces

## Known Constraints

- File uploads default to chunked for large files to avoid PHP timeouts
- Concurrent operations on same file protected by file locks
- Large directory listings (1000+ files) use pagination
- Image thumbnails generated on-demand and cached
