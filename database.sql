-- ============================================================
-- Alzikrayat - Photo Sharing Application
-- Database schema (MySQL)
--
-- Run this file once to create the database and its tables.
-- In phpMyAdmin: Import > choose this file.
-- Or from the command line:  mysql -u root -p < database.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS alzikrayat
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE alzikrayat;

-- ------------------------------------------------------------
-- Users table
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS users (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    first_name  VARCHAR(50)  NOT NULL,
    last_name   VARCHAR(50)  NOT NULL,
    email       VARCHAR(100) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,          -- bcrypt hash, never the raw password
    location    VARCHAR(100) DEFAULT NULL,
    description TEXT         DEFAULT NULL,
    occupation  VARCHAR(100) DEFAULT NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Photos table
-- If a user is deleted, all of their photos are removed too.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS photos (
    id          INT AUTO_INCREMENT PRIMARY KEY,
    user_id     INT NOT NULL,
    file_name   VARCHAR(255) NOT NULL,
    title       VARCHAR(200) NOT NULL,
    description TEXT DEFAULT NULL,
    date_time   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_photos_user (user_id),
    CONSTRAINT fk_photos_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Comments table
-- Deleting a photo or a user cascades to their comments.
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS comments (
    id        INT AUTO_INCREMENT PRIMARY KEY,
    photo_id  INT NOT NULL,
    user_id   INT NOT NULL,
    comment   TEXT NOT NULL,
    date_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_comments_photo (photo_id),
    INDEX idx_comments_user (user_id),
    CONSTRAINT fk_comments_photo
        FOREIGN KEY (photo_id) REFERENCES photos(id)
        ON DELETE CASCADE,
    CONSTRAINT fk_comments_user
        FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE
) ENGINE=InnoDB;
