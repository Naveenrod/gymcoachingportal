-- Migration: Add check-in fields to clients table
-- Run this if the clients table already exists

USE gym_coaching_portal;

ALTER TABLE clients 
ADD COLUMN IF NOT EXISTS loom_link VARCHAR(500) AFTER notes,
ADD COLUMN IF NOT EXISTS package VARCHAR(100) AFTER loom_link,
ADD COLUMN IF NOT EXISTS check_in_frequency VARCHAR(50) AFTER package,
ADD COLUMN IF NOT EXISTS check_in_day VARCHAR(50) AFTER check_in_frequency,
ADD COLUMN IF NOT EXISTS submitted ENUM('Submitted', '') DEFAULT '' AFTER check_in_day,
ADD COLUMN IF NOT EXISTS rank VARCHAR(50) AFTER submitted;
