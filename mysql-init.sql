CREATE DATABASE IF NOT EXISTS tudofut;
CREATE USER IF NOT EXISTS 'tudofut_user'@'%' IDENTIFIED BY '$MYSQL_PASSWORD';
GRANT ALL PRIVILEGES ON tudofut.* TO 'tudofut_user'@'%';
FLUSH PRIVILEGES;

-- Permitir conexões remotas
ALTER USER 'tudofut_user'@'%' IDENTIFIED WITH caching_sha2_password BY '$MYSQL_PASSWORD';
GRANT ALL PRIVILEGES ON *.* TO 'tudofut_user'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
