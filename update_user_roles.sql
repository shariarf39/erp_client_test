-- Update all users to Super Admin role (role_id = 1)
UPDATE users SET role_id = 1 WHERE role_id IS NULL OR role_id = 0;

-- Verify the update
SELECT id, name, email, role_id FROM users;
