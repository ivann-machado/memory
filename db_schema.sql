-- db_schema.sql

-- Database creation (optional, but good practice if you don't use a GUI tool)
-- CREATE DATABASE IF NOT EXISTS memory_game CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
-- USE memory_game;

-- 1. Table for Players (Profiles)
CREATE TABLE players (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    -- Store the hashed password
    password_hash VARCHAR(255) NOT NULL, 
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Table for Scores (Game Results)
CREATE TABLE scores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    -- Link to the players table
    player_id INT UNSIGNED NOT NULL, 
    -- Game size
    pairs_count TINYINT UNSIGNED NOT NULL,  
    -- Game performance metrics
    moves_count INT UNSIGNED NOT NULL,  
    time_taken_seconds INT UNSIGNED,    
    finished_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Define the foreign key relationship
    FOREIGN KEY (player_id) REFERENCES players(id) ON DELETE CASCADE
);

-- Optional: Index for efficient ranking lookup
-- A good ranking is usually based on moves, then time, for a specific pair count.
CREATE INDEX idx_ranking ON scores (pairs_count, moves_count, time_taken_seconds);