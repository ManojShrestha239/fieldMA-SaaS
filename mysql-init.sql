-- Optional initial SQL commands
-- This file is executed when the MySQL container is first created.
-- The database and user are already created via environment variables,
-- but you can add extra setup here if needed.

-- Example: grant privileges
GRANT ALL PRIVILEGES ON *.* TO 'fieldMA_SAAS_user'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
