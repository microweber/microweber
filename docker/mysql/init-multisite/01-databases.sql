-- Multisite sample: one MySQL server, one database per site.
-- Each Microweber site is fully independent (its own schema + content), selected
-- at runtime by the incoming Host header. The `microweber` DB below is created by
-- the container's MYSQL_DATABASE env; here we add the extra per-site databases and
-- make sure the app user can reach them.

CREATE DATABASE IF NOT EXISTS mw_site_a CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE DATABASE IF NOT EXISTS mw_site_b CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

GRANT ALL PRIVILEGES ON mw_site_a.* TO 'microweber'@'%';
GRANT ALL PRIVILEGES ON mw_site_b.* TO 'microweber'@'%';

FLUSH PRIVILEGES;
