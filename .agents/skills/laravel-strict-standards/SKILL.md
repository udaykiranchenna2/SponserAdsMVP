---
name: laravel-strict-standards
description: Enforces strict Laravel development standards including Action classes, Form Requests, safety protocols, progress tracking, explicit imports, and Enums.
---

# Laravel Strict Standards

This skill enforces high-quality, scalable, and safe development practices for Laravel applications. It is activated when working on Laravel backend features.

## 1. Architecture & Logic

### Action Classes

- **Rule**: ALL business logic must be encapsulated in Action classes. Do not put complex logic in Controllers or Models.
- **Location**: `app/Actions`. If the folder does not exist, create it.
- **Naming**: `VerbSubject` (e.g., `CreateUser`, `PublishPost`, `ProcessPayment`).
- **Structure**:
    - Each Action should ideally have a single public method (e.g., `execute`, `handle`, or `__invoke`).
    - Actions should be reusable and framework-agnostic where possible.
- **Usage**:
    - Controllers should handle HTTP request/response and delegate logic to Actions.
    - In Controllers, inject the Action class and call its method.
    - Create Actions manually or use `php artisan make:class ActionName` if `make:action` is unavailable.

### Form Requests

- **Rule**: NEVER validate data directly in a Controller using `$request->validate()`.
- **Usage**:
    - Always generate a Form Request: `php artisan make:request [Name]`.
    - Place all validation rules (`rules()`) and authorization logic (`authorize()`) in the Form Request.
    - Type-hint the Form Request in the controller method (e.g., `public function store(StorePostRequest $request)`).
    - This ensures controllers remain clean and validation logic is centralized.

## 2. Safety Protocols

### ⚠️ DESTRUCTIVE COMMANDS WARNING ⚠️

- **CRITICAL RULE**: You are **STRICTLY PROHIBITED** from running destructive database commands without explicit, written permission from the user in the current turn.
- **Restricted Commands**:
    - `php artisan migrate:fresh`
    - `php artisan migrate:refresh`
    - `php artisan migrate:reset`
    - `php artisan db:wipe`
    - Any command that drops tables or deletes all data.
- **Procedure**: If you believe a destructive command is necessary to proceed (e.g., schema changes are too complex for a standard migration):
    1.  **Stop**.
    2.  **Explain** why it is necessary.
    3.  **Ask** the user to run it themselves OR ask for explicit confirmation to run it.

## 3. Workflow & Tracking

### Module Progress Tracking

- **Rule**: Whenever you start working on a specific module or logical unit (e.g., "Authentication", "Billing", "User Management"), you MUST create a progress tracker.
- **Location**: `.docs/progress/[module_name].md`.
- **Template**:

    ```markdown
    # [Module Name] Progress Tracker

    **Status**: [Planning / In Progress / Testing / Completed]
    **Started**: [Date]

    ## Checklist

    ### Database

    - [ ] Migrations created
    - [ ] Models created
    - [ ] Relationships defined
    - [ ] Seeders / Factories created

    ### Logic (Backend)

    - [ ] Action Classes implemented
    - [ ] Form Requests created
    - [ ] Controllers & Routes setup
    - [ ] API Resources (if applicable)

    ### Frontend

    - [ ] Components created
    - [ ] Pages / Views built
    - [ ] State management (if applicable)

    ### Quality Assurance

    - [ ] Automated Tests (Feature/Unit) written & passing
    - [ ] Manual testing performed

    ## Implementation Details

    - [Notes on architecture decisions, API endpoints, etc.]
    ```

- **Updates**: Check off items as you complete them to maintain a clear status of the module.

## 4. Code Style

### Explicit Imports

- **Rule**: ALWAYS import classes at the top of the file using the `use` keyword. NEVER use fully qualified class names inline within the code logic.
- **Incorrect**:
    ```php
    $user = \App\Models\User::find(1);
    ```
- **Correct**:

    ```php
    use App\Models\User;

    // ...

    $user = User::find(1);
    ```

- **Reasoning**: This improves readability and maintains a clean codebase. It also makes it easier to spot dependencies at the top of the file.

### Enums over Hardcoded Values

- **Rule**: NEVER use hardcoded strings or integers for status, roles, types, or other fixed sets of values. ALWAYS use PHP 8.1+ Enums.
- **Location**: `app/Enums`.
- **Usage**:
    - Create backed Enums for database storage (e.g., `enum UserRole: string`).
    - Use Enum cases in code, validation, and database queries.
    - **Incorrect**:
        ```php
        $user->status = 'active';
        ```
    - **Correct**:
        ```php
        $user->status = UserStatus::Active;
        ```
    - **Casts**: Ensure you add the enum to the `$casts` array in your Eloquent models.
