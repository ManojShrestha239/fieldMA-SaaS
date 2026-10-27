You are a Principal Software Architect and Technical Writer. Your task is to analyze this entire codebase and generate a comprehensive set of project documentation files (.md).

Please read the project structure, configuration files (composer.json, package.json, docker-compose.yml, etc.), and key source code directories before generating the files.

Create or update the following 5 files in the root directory:

1. **Rules.md** (Development Guidelines & Standards)
    - Coding standards, naming conventions, architectural boundaries (e.g., Service layers, DTOs).
    - Git workflow, testing requirements, and error-handling rules.
    - Environment and tooling requirements (PHP, Node, Docker, etc.).

2. **Memory.md** (Project State & Context)
    - Current project status, active modules, and completed features.
    - Known technical debt, pending refactors, and active work items.
    - Important configuration notes or gotchas for developers working on this codebase.

3. **Architecture.md** (System Design & Structure)
    - High-level system architecture (e.g., Modular Monolith, MVC, microservices).
    - Directory structure breakdown and what each folder/layer is responsible for.
    - Data flow (e.g., Request -> Controller -> FormRequest -> Service -> DTO -> Database).
    - External integrations, database schema overview, and caching/queuing mechanisms.

4. **PRD.md** (Product Requirements Document)
    - Project vision, goals, and core value proposition.
    - Target users and core features (e.g., Sales Force Automation, dealer management, tracking).
    - Functional and non-functional requirements based on the current codebase capabilities.

5. **Design.md** (Technical & UI/UX Design System)
    - Frontend design patterns, CSS frameworks used (e.g., Tailwind CSS).
    - UI component structure, layout rules, and responsiveness standards.
    - API design patterns (RESTful endpoints, resource responses, authentication/authorization).

**Instructions:**

- Inspect the file tree and key files thoroughly first.
- Be extremely specific to _this_ project (do not use generic placeholders).
- Keep formatting clean, using Markdown headings, bullet points, and code blocks where appropriate.
