# File Management Web Application - Development Plan

## Project Overview
A micro-web application for managing uploaded files in a specified location, similar to CKFinder functionality.

**Architecture:** Monorepo with backend and frontend in a single repository  
**Backend:** PHP REST API  
**Frontend:** React with TypeScript (chosen for its maturity, ecosystem, component reusability, and strong typing support)

---

## Technology Stack

### Backend
- **PHP 8.4+** - Latest PHP with property hooks, asymmetric visibility, and performance improvements
- **Restopus** (github.com/doomy/restopus) - Custom REST API framework built on Nette, supports attribute-based routing, HTTP method declarations, request body validation, and authentication
- **Nette Framework** - Foundation for Restopus (presenters & routing)
- **Composer** - Dependency management
- **JWT** or session-based authentication - Security layer via Restopus #[Authenticated] attribute
- **PHP-FIG standards** - PSR-4 autoloading, PSR-7 HTTP messages

### Frontend
- **React 18+** - Component-based UI library
- **TypeScript** - Type safety and better developer experience
- **Vite** - Fast build tool and dev server
- **TanStack Query (React Query)** - Server state management and caching
- **Axios** - HTTP client for API communication
- **TailwindCSS** or **Material-UI** - Styling framework
- **React Router** - Client-side routing

### Development Tools
- **Docker** - Containerization for consistent environments
- **PHPUnit** - Backend testing
- **Vitest** or **Jest** - Frontend testing
- **ESLint & Prettier** - Code quality and formatting
- **PHP_CodeSniffer** - PHP code standards

---

## Core Features

1. **File Operations**
   - Upload single/multiple files
   - Download files
   - Delete files
   - Rename files
   - Move files between directories
   - Copy files

2. **Directory Operations**
   - Create directories
   - Delete directories (with contents)
   - Rename directories
   - Navigate directory tree

3. **File Viewing**
   - List view with sorting (name, size, date, type)
   - Grid/thumbnail view for images
   - File search functionality
   - File type filtering

4. **File Information**
   - File size
   - MIME type
   - Upload/modification dates
   - Image dimensions (for images)
   - File permissions

5. **Security**
   - File type validation and restrictions
   - File size limits
   - Path traversal prevention
   - Access control (basic authentication)

---

## Development Phases

### Phase 1: Project Setup & Infrastructure (Week 1)

**1.1 Initialize Monorepo Structure**
- Initialize single Git repository for the project
- Create root .gitignore for both backend and frontend
- Create project-wide README.md with overview
- Set up root-level configuration files

**1.2 Backend Setup**
- Initialize PHP project with Composer
- Add `doomy/restopus` dependency
- Set up Nette configuration (config.neon) with Restopus router factory
- Configure autoloading (PSR-4)
- Create directory structure:
  ```
  backend/
  ├── www/
  │   └── index.php
  ├── src/
  │   ├── Presenter/              # REST presenters (instead of Controllers)
  │   ├── Services/
  │   ├── RequestBody/            # Request body validation classes
  │   ├── Models/
  │   └── Utils/
  ├── config/
  │   └── config.neon             # Nette configuration
  ├── storage/
  │   └── uploads/
  ├── tests/
  └── composer.json
  ```

**1.3 Frontend Setup**
- Initialize React project with Vite + TypeScript
- Install core dependencies (React Query, Axios, Router)
- Set up TailwindCSS or Material-UI
- Create directory structure:
  ```
  frontend/
  ├── src/
  │   ├── components/
  │   ├── pages/
  │   ├── services/
  │   ├── hooks/
  │   ├── types/
  │   ├── utils/
  │   └── App.tsx
  ├── public/
  ├── tests/
  └── package.json
  ```

**1.4 Docker Configuration (Monorepo)**
- Create Dockerfile for PHP 8.4 backend in docker/php/
- Create Dockerfile for frontend in docker/node/
- Create root docker-compose.yml orchestrating both services
- Configure Nginx as reverse proxy for both backend API and frontend
- Set up volume mappings for hot-reload during development

**1.5 Environment Configuration**
- Create root .env.example with all environment variables
- Optionally create backend/.env.example and frontend/.env.example for service-specific vars
- Document all required environment variables in README
- Set up configuration loaders in both services

---

### Phase 2: Backend API Development (Week 2-3)

**2.1 Core API Structure**
- Define RESTful API endpoints using Restopus attributes (#[Route], #[HttpMethod])
- Configure Restopus router factory in config.neon
- Create AbstractRestPresenter base class extending Restopus
- Implement CORS handling (Nette middleware or Restopus extension)
- Add error handling (Nette error presenter or Restopus extension)
- Use Restopus Response class for consistent JSON responses

**2.2 File System Service Layer**
- Create FileSystemService class
- Implement secure path resolution
- Add path traversal protection
- Create file metadata extraction utilities
- Implement MIME type detection

**2.3 File Operations Endpoints**
- `GET /api/files` - List files in directory
- `GET /api/files/{path}` - Get file metadata
- `POST /api/files/upload` - Upload files
- `GET /api/files/download/{path}` - Download file
- `DELETE /api/files/{path}` - Delete file
- `PUT /api/files/{path}/rename` - Rename file
- `POST /api/files/{path}/copy` - Copy file
- `POST /api/files/{path}/move` - Move file

**2.4 Directory Operations Endpoints**
- `POST /api/directories` - Create directory
- `DELETE /api/directories/{path}` - Delete directory
- `PUT /api/directories/{path}/rename` - Rename directory
- `GET /api/directories/tree` - Get directory tree structure

**2.5 Search & Filter**
- `GET /api/files/search?q={query}` - Search files
- Add filtering capabilities (by type, date range)
- Implement sorting options

**2.6 Security Implementation**
- Add file type validation
- Implement file size restrictions
- Use Restopus #[Authenticated] attribute for protected endpoints
- Create request body validation classes using #[RequestBody] attribute
- Sanitize file names
- Prevent directory traversal attacks

**2.7 Testing**
- Write unit tests for FileSystemService
- Write integration tests for API endpoints
- Test security measures
- Test error handling scenarios

---

### Phase 3: Frontend Development (Week 3-4)

**3.1 API Client Layer**
- Create Axios instance with interceptors
- Define TypeScript interfaces for API responses
- Implement API service functions
- Add error handling and retry logic

**3.2 State Management Setup**
- Configure React Query
- Create custom hooks for API calls:
  - `useFiles(path)`
  - `useDirectories()`
  - `useUpload()`
  - `useFileOperations()`

**3.3 Core Components**
- FileList component (list view)
- FileGrid component (grid/thumbnail view)
- FileItem component
- DirectoryTree component (sidebar navigation)
- Breadcrumb navigation component
- UploadButton/UploadZone component
- SearchBar component
- FilterPanel component

**3.4 File Operations UI**
- Context menu for file actions
- Drag-and-drop upload interface
- Progress indicators for uploads
- Confirmation modals (delete, overwrite)
- Rename inline editing
- Multi-select functionality

**3.5 Main Pages**
- FileManager page (main interface)
- Login page (if authentication required)
- Settings page (configuration options)

**3.6 User Experience Features**
- Loading states and skeletons
- Error boundaries and error messages
- Toast notifications for operations
- Keyboard shortcuts
- Responsive design for mobile/tablet
- Accessibility (ARIA labels, keyboard navigation)

**3.7 Testing**
- Component unit tests (Vitest/Jest + Testing Library)
- Integration tests for user flows
- E2E tests for critical paths (optional: Playwright/Cypress)

---

### Phase 4: Integration & Features (Week 5)

**4.1 File Preview System**
- Image preview/lightbox
- Text file viewer
- PDF viewer integration (optional)
- Video player for media files
- Audio player for audio files

**4.2 Advanced Features**
- Bulk operations (delete, move multiple files)
- File compression/download as ZIP
- Storage quota display
- Recent files view
- Favorites/bookmarks

**4.3 Configuration Options**
- Configurable upload directory
- File type whitelist/blacklist
- Maximum file size settings
- Thumbnail generation settings
- View preferences (list/grid default)

**4.4 Performance Optimization**
- Implement pagination or virtual scrolling
- Add caching strategies
- Optimize image thumbnails
- Lazy loading for images
- Debounce search input

---

### Phase 5: Deployment & Documentation (Week 6)

**5.1 Production Build**
- Optimize frontend build (code splitting, minification)
- Configure PHP 8.4 for production (OPcache, JIT, error handling)
- Set up proper file permissions
- Create optimized production Docker images from monorepo
- Configure Nginx reverse proxy for production deployment
- Create docker-compose.prod.yml for production

**5.2 Security Hardening**
- Review and test all security measures
- Add rate limiting
- Implement CSRF protection
- Add security headers
- Configure HTTPS

**5.3 Documentation**
- API documentation (OpenAPI/Swagger)
- Installation guide
- Configuration guide
- User manual
- Developer documentation
- Deployment instructions

**5.4 Deployment**
- Create deployment scripts for monorepo
- Set up CI/CD pipeline (GitHub Actions/GitLab CI) - single pipeline for both services
- Deploy to staging environment
- Perform user acceptance testing
- Deploy to production with coordinated backend/frontend releases

**5.5 Monitoring & Maintenance**
- Set up error logging
- Add application monitoring
- Create backup strategy
- Document maintenance procedures

---

## API Endpoint Summary

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

---

## Monorepo Directory Structure (Final)

```
fileman/                          # Root monorepo
├── .git/                         # Single Git repository
├── .gitignore                    # Project-wide gitignore
├── README.md                     # Project overview and setup instructions
├── plan.MD                       # This planning document
├── .env.example                  # Environment variables template
├── docker-compose.yml            # Development orchestration
├── docker-compose.prod.yml       # Production orchestration
│
├── backend/                      # PHP 8.4+ REST API with Restopus
│   ├── www/
│   │   └── index.php             # API entry point
│   ├── src/
│   │   ├── Presenter/            # REST presenters (Restopus pattern)
│   │   │   ├── FilePresenter.php
│   │   │   └── DirectoryPresenter.php
│   │   ├── RequestBody/          # Request body validation classes
│   │   │   ├── FileUploadRequestBody.php
│   │   │   ├── FileRenameRequestBody.php
│   │   │   └── DirectoryCreateRequestBody.php
│   │   ├── Services/
│   │   │   ├── FileSystemService.php
│   │   │   └── ValidationService.php
│   │   ├── Models/
│   │   └── Utils/
│   │       └── PathValidator.php
│   ├── config/
│   │   └── config.neon           # Nette configuration
│   ├── storage/
│   │   └── uploads/              # File storage location
│   ├── tests/
│   │   ├── Unit/
│   │   └── Integration/
│   ├── composer.json
│   ├── composer.lock
│   └── phpunit.xml
│
├── frontend/                     # React + TypeScript SPA
│   ├── src/
│   │   ├── components/
│   │   │   ├── FileList.tsx
│   │   │   ├── FileGrid.tsx
│   │   │   ├── DirectoryTree.tsx
│   │   │   ├── UploadZone.tsx
│   │   │   └── ...
│   │   ├── pages/
│   │   │   └── FileManager.tsx
│   │   ├── services/
│   │   │   └── api.ts
│   │   ├── hooks/
│   │   │   ├── useFiles.ts
│   │   │   ├── useDirectories.ts
│   │   │   └── useFileOperations.ts
│   │   ├── types/
│   │   │   └── index.ts
│   │   ├── utils/
│   │   ├── App.tsx
│   │   └── main.tsx
│   ├── public/
│   ├── tests/
│   ├── package.json
│   ├── package-lock.json
│   ├── tsconfig.json
│   ├── vite.config.ts
│   └── tailwind.config.js
│
├── docker/                       # Docker configuration
│   ├── nginx/
│   │   ├── nginx.conf
│   │   └── default.conf          # Reverse proxy config
│   ├── php/
│   │   ├── Dockerfile            # PHP 8.4 image
│   │   └── php.ini               # PHP configuration
│   └── node/
│       └── Dockerfile            # Node image for frontend
│
└── docs/                         # Project documentation
    ├── API.md                    # API documentation
    ├── DEPLOYMENT.md             # Deployment guide
    └── DEVELOPMENT.md            # Development setup guide
```

---

## Monorepo Benefits

### Development Workflow
- **Single clone** - Get entire project with one `git clone`
- **Unified versioning** - Backend API changes and frontend updates stay synchronized
- **Simplified CI/CD** - One pipeline tests and deploys both services
- **Easier refactoring** - Rename API endpoints and update frontend calls in single commit
- **Shared tooling** - Git hooks, linting, and formatting configs at root level

### Deployment
- **Coordinated releases** - Deploy backend and frontend together
- **Single Docker Compose** - Run entire stack with `docker-compose up`
- **Consistent environment** - All services share same .env configuration

### Maintenance
- **Unified issue tracking** - All bugs and features in one place
- **Single documentation** - One README, one set of docs
- **Easier onboarding** - New developers clone one repo and are ready to go

---

## Risk Assessment & Mitigation

### Security Risks
- **Risk:** Path traversal attacks
  - **Mitigation:** Strict path validation, use realpath(), check against base directory
  
- **Risk:** Malicious file uploads
  - **Mitigation:** File type validation, size limits, scan files, rename on upload

- **Risk:** Unauthorized access
  - **Mitigation:** Authentication system, session management, access control

### Technical Risks
- **Risk:** Large file uploads causing timeouts
  - **Mitigation:** Chunked uploads, progress tracking, proper PHP configuration

- **Risk:** Concurrent file operations
  - **Mitigation:** File locking, atomic operations, proper error handling

- **Risk:** Performance with large directories
  - **Mitigation:** Pagination, lazy loading, caching, indexing

---

## Success Criteria

1. ✅ Users can upload, download, and manage files securely
2. ✅ All file operations work without errors
3. ✅ Interface is responsive and intuitive
4. ✅ Security measures prevent unauthorized access and malicious uploads
5. ✅ Application performs well with 1000+ files
6. ✅ Comprehensive documentation is available
7. ✅ Test coverage > 80% for critical functionality

---

## Next Steps

After approval of this plan:
1. Set up the project infrastructure (Phase 1)
2. Begin backend API development (Phase 2)
3. Proceed with subsequent phases sequentially
4. Regular checkpoints after each phase completion

---

## Estimated Timeline

- **Phase 1:** 5-7 days
- **Phase 2:** 10-14 days
- **Phase 3:** 10-14 days
- **Phase 4:** 7-10 days
- **Phase 5:** 5-7 days

**Total:** 5-6 weeks for full implementation

---

*This plan is flexible and can be adjusted based on specific requirements or constraints discovered during development.*
