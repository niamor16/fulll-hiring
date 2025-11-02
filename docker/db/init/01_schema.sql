CREATE TABLE IF NOT EXISTS fleets (
    id VARCHAR(23) PRIMARY KEY,
    user_id VARCHAR(23) NOT NULL
);

CREATE TABLE IF NOT EXISTS vehicles (
    plate VARCHAR(16) PRIMARY KEY,
    latitude DOUBLE PRECISION NULL,
    longitude DOUBLE PRECISION NULL,
    altitude DOUBLE PRECISION NULL
);

CREATE TABLE IF NOT EXISTS fleet_vehicles (
    fleet_id  VARCHAR(23) NOT NULL REFERENCES fleets(id) ON DELETE CASCADE,
    plate     VARCHAR(16) NOT NULL,
    PRIMARY KEY (fleet_id, plate)
);

CREATE INDEX IF NOT EXISTS idx_fleet_vehicles_plate ON fleet_vehicles(plate);
CREATE INDEX IF NOT EXISTS fleet_user_id ON fleets(user_id);
