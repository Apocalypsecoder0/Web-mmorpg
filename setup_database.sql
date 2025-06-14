
-- War of Ages Database Schema

-- Users table
CREATE TABLE IF NOT EXISTS users (
    uid SERIAL PRIMARY KEY,
    uname VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100),
    rid INTEGER DEFAULT 1,
    hpname VARCHAR(100),
    ip VARCHAR(45),
    access_level INTEGER DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User data table
CREATE TABLE IF NOT EXISTS userdata (
    uid INTEGER PRIMARY KEY REFERENCES users(uid),
    rid INTEGER DEFAULT 1,
    credits BIGINT DEFAULT 1000,
    networth BIGINT DEFAULT 1000,
    turns INTEGER DEFAULT 500,
    actionTurns INTEGER DEFAULT 250,
    link VARCHAR(255),
    last_turn_update TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Bank table for user finances
CREATE TABLE IF NOT EXISTS bank (
    uid INTEGER PRIMARY KEY REFERENCES users(uid),
    onHand BIGINT DEFAULT 250000,
    inBank BIGINT DEFAULT 0
);

-- Power table for user power calculations
CREATE TABLE IF NOT EXISTS power (
    uid INTEGER PRIMARY KEY REFERENCES users(uid),
    totalPower BIGINT DEFAULT 0,
    lastUpdate TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Race table
CREATE TABLE IF NOT EXISTS race (
    rid SERIAL PRIMARY KEY,
    r_name VARCHAR(50) NOT NULL,
    description TEXT
);

-- Rankings table
CREATE TABLE IF NOT EXISTS rank (
    uid INTEGER PRIMARY KEY REFERENCES users(uid),
    overall INTEGER DEFAULT 0,
    credits INTEGER DEFAULT 0,
    networth INTEGER DEFAULT 0
);

-- Messages table
CREATE TABLE IF NOT EXISTS messages (
    mid SERIAL PRIMARY KEY,
    fromUID INTEGER REFERENCES users(uid),
    toUID INTEGER REFERENCES users(uid),
    subject VARCHAR(255) DEFAULT 'None',
    message TEXT,
    timeSent TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    isDeleted BOOLEAN DEFAULT FALSE
);

-- Alliance table
CREATE TABLE IF NOT EXISTS alliance (
    aid SERIAL PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    leader_uid INTEGER REFERENCES users(uid)
);

-- Insert default races
INSERT INTO race (r_name, description) VALUES 
('Human', 'Balanced race with no special bonuses'),
('Elf', 'Magical race with enhanced technology research'),
('Dwarf', 'Hardy race with enhanced military capabilities'),
('Orc', 'Aggressive race with combat bonuses')
ON CONFLICT DO NOTHING;

-- Units table for military units
CREATE TABLE IF NOT EXISTS units (
    uid INTEGER PRIMARY KEY REFERENCES users(uid),
    untrained BIGINT DEFAULT 250,
    miners BIGINT DEFAULT 0,
    lifers BIGINT DEFAULT 0,
    soldiers BIGINT DEFAULT 0,
    fighters BIGINT DEFAULT 0,
    cruisers BIGINT DEFAULT 0,
    destroyers BIGINT DEFAULT 0
);

-- Technology table for research
CREATE TABLE IF NOT EXISTS technology (
    uid INTEGER PRIMARY KEY REFERENCES users(uid),
    income INTEGER DEFAULT 0,
    unitProd INTEGER DEFAULT 0,
    uppl INTEGER DEFAULT 0
);

-- Planets table
CREATE TABLE IF NOT EXISTS planets (
    pid SERIAL PRIMARY KEY,
    uid INTEGER REFERENCES users(uid),
    plnt_name VARCHAR(100),
    plnt_size INTEGER DEFAULT 1,
    income_bonus INTEGER DEFAULT 0,
    up_bonus INTEGER DEFAULT 0,
    isHome INTEGER DEFAULT 0
);

-- Planet size lookup
CREATE TABLE IF NOT EXISTS planetsize (
    size INTEGER PRIMARY KEY,
    text VARCHAR(50) NOT NULL
);

-- Insert default planet sizes
INSERT INTO planetsize (size, text) VALUES 
(1, 'Small'),
(2, 'Medium'),
(3, 'Large'),
(4, 'Huge')
ON CONFLICT DO NOTHING;

-- Attack logs table
CREATE TABLE IF NOT EXISTS attackLogs (
    logID SERIAL PRIMARY KEY,
    attacker INTEGER REFERENCES users(uid),
    defender INTEGER REFERENCES users(uid),
    attackerLosses TEXT,
    defenderLosses TEXT,
    result VARCHAR(50),
    timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- User progress table for tutorial/game progression
CREATE TABLE IF NOT EXISTS progress (
    uid INTEGER PRIMARY KEY REFERENCES users(uid),
    step INTEGER DEFAULT 0,
    completed BOOLEAN DEFAULT FALSE
);

-- Game settings table
CREATE TABLE IF NOT EXISTS settings (
    setting_name VARCHAR(50) PRIMARY KEY,
    setting_value TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert default game settings
INSERT INTO settings (setting_name, setting_value) VALUES 
('game_active', '1'),
('turn_time', '30'),
('max_turns', '500')
ON CONFLICT (setting_name) DO NOTHING;

-- Create indexes for performance
CREATE INDEX IF NOT EXISTS idx_users_uname ON users(uname);
CREATE INDEX IF NOT EXISTS idx_messages_touid ON messages(toUID);
CREATE INDEX IF NOT EXISTS idx_rank_overall ON rank(overall);
CREATE INDEX IF NOT EXISTS idx_planets_uid ON planets(uid);
CREATE INDEX IF NOT EXISTS idx_units_uid ON units(uid);
CREATE INDEX IF NOT EXISTS idx_technology_uid ON technology(uid);
CREATE INDEX IF NOT EXISTS idx_attacklogs_attacker ON attackLogs(attacker);
CREATE INDEX IF NOT EXISTS idx_attacklogs_defender ON attackLogs(defender);
