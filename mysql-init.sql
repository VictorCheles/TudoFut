CREATE DATABASE IF NOT EXISTS tudofut;
CREATE USER IF NOT EXISTS 'tudofut_user'@'%' IDENTIFIED BY 'secret123';
GRANT ALL PRIVILEGES ON tudofut.* TO 'tudofut_user'@'%';
FLUSH PRIVILEGES;

-- Permitir conexões remotas
ALTER USER 'tudofut_user'@'%' IDENTIFIED WITH mysql_native_password BY 'secret123';
GRANT ALL PRIVILEGES ON *.* TO 'tudofut_user'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
