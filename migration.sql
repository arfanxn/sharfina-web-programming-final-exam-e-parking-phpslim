USE `fanparking`;

SET foreign_key_checks = 0;
DROP TABLE IF EXISTS parked_vehicles;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS vehicle_colors;
DROP TABLE IF EXISTS vehicle_types;
DROP TABLE IF EXISTS vehicle_rate_per_hours;
DROP TABLE IF EXISTS vehicles;
SET foreign_key_checks = 1;

CREATE TABLE parked_vehicles (
    id                 INTEGER NOT NULL,
    vehicle_id         INTEGER NOT NULL,
    plate_number       VARCHAR(12) NOT NULL,
    entered_by_user_id INTEGER NOT NULL,
    entered_at         DATETIME NOT NULL,
    left_by_user_id    INTEGER,
    left_at            DATETIME
);
ALTER TABLE parked_vehicles ADD CONSTRAINT parked_vehicles_pk PRIMARY KEY ( id );
ALTER TABLE parked_vehicles MODIFY COLUMN id INT AUTO_INCREMENT;

CREATE TABLE users (
    id         INTEGER NOT NULL,
    name       VARCHAR(50) NOT NULL,
    email      VARCHAR(50) NOT NULL,
    password VARCHAR(256) NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    deactived_at DATETIME
);
ALTER TABLE users ADD CONSTRAINT users_pk PRIMARY KEY ( id );
ALTER TABLE users MODIFY COLUMN id INT AUTO_INCREMENT;


CREATE TABLE vehicle_colors (
    id       INTEGER NOT NULL,
    name     VARCHAR(25) NOT NULL,
    hex_code CHAR(6)
);
ALTER TABLE vehicle_colors ADD CONSTRAINT vehicle_colors_pk PRIMARY KEY ( id );
ALTER TABLE vehicle_colors MODIFY COLUMN id INT AUTO_INCREMENT;


CREATE TABLE vehicle_rate_per_hours (
    id   INTEGER NOT NULL,
    rate INTEGER NOT NULL
);
ALTER TABLE vehicle_rate_per_hours ADD CONSTRAINT vehicle_rate_per_hours_pk PRIMARY KEY ( id );
ALTER TABLE vehicle_rate_per_hours MODIFY COLUMN id INT AUTO_INCREMENT;


CREATE TABLE vehicle_types (
    id   INTEGER NOT NULL,
    type VARCHAR(100) NOT NULL
);
ALTER TABLE vehicle_types ADD CONSTRAINT vehicle_types_pk PRIMARY KEY ( id );
ALTER TABLE vehicle_types MODIFY COLUMN id INT AUTO_INCREMENT;


CREATE TABLE vehicles (
    id                        INTEGER NOT NULL,
    vehicle_color_id          INTEGER NOT NULL,
    vehicle_type_id           INTEGER NOT NULL,
    vehicle_rate_per_hour_id  INTEGER NOT NULL,
    created_at                DATETIME NOT NULL,
    updated_at                DATETIME
);
ALTER TABLE vehicles ADD CONSTRAINT vehicles_pk PRIMARY KEY ( id );
ALTER TABLE vehicles MODIFY COLUMN id INT AUTO_INCREMENT;

ALTER TABLE parked_vehicles ADD CONSTRAINT vehicle_fk
  FOREIGN KEY (vehicle_id)
  REFERENCES vehicles(id);
ALTER TABLE parked_vehicles ADD CONSTRAINT entered_by_user_fk
  FOREIGN KEY (entered_by_user_id)
  REFERENCES users(id);
ALTER TABLE parked_vehicles ADD CONSTRAINT left_by_user_fk
  FOREIGN KEY (left_by_user_id)
  REFERENCES users(id);

ALTER TABLE vehicles ADD CONSTRAINT vehicle_color_fk
  FOREIGN KEY (vehicle_color_id)
  REFERENCES vehicle_colors(id);
ALTER TABLE vehicles ADD CONSTRAINT vehicle_type_fk
  FOREIGN KEY (vehicle_type_id)
  REFERENCES vehicle_types(id);
ALTER TABLE vehicles ADD CONSTRAINT vehicle_rate_per_hour_fk
  FOREIGN KEY (vehicle_rate_per_hour_id)
  REFERENCES vehicle_rate_per_hours(id);

