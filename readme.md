# XXX

## Running the Project

To get the project up and running, follow the steps below:

1. **Build the project:**
    ```bash
    npm run build
    ```

2. **Start Docker containers:**
    ```bash
    docker compose up -d
    ```

3. **Run migrations manually:**
    - Migrations can be found in the `migrations` directory. Execute the necessary migration steps to set up your database.

4. **Configure Mailer:**
    - Set up your mailer configuration to enable email functionalities such as email verification.

5. **Database:**
    - The database `xxx` is automatically created.

---

## Improvements

- The migration process could benefit from using a robust migration library.
- The `UserRepository` could potentially be split into Command and Query repositories for a more domain-driven design.
- It's recommended to use an ORM for managing database interactions.

---

### Emails

- A very simple email verification process has been implemented. However, a dedicated library like JWT should be used for improved security and flexibility.
- For sending more complex emails, `Nette\Bridges\ApplicationLatte\TemplateFactory` should be utilized.

---

### Admin Area

1. **Password Protection:**
    - The admin area should have password protection to secure access.

2. **User Update Process:**
    - The current user update process is simple and could be enhanced, for instance, by tracking whether a field has been changed before updating.

3. **Use Case Refactor:**
    - The same use case (`SignUpUseCase`) is used for user creation via the admin area with email verification. However, if different behavior is needed (e.g., setting the `verified` flag to `true` during user creation), a separate use case (such as `CreateUserUseCase`) should be created to handle this scenario.

4. **User Update Implementation:**
    - User update is done on a separate page but could be implemented directly on the 'User Management' page if needed.

5. **Missing Features on User Management Page:**
    - Sorting and searching functionalities are not yet implemented on the 'User Management' page.

---

## Skipped

1. **Documentation**
2. **Unit Tests**