Backend For HACKRPI WALKR


take in coordinates, hazard.
display on front end with heat map from google apis


CREATE TABLE hazard_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    latitude DECIMAL(10, 8) NOT NULL,
    longitude DECIMAL(11, 8) NOT NULL,
    hazard_type VARCHAR(50) NOT NULL,
    severity INT NOT NULL,
    report_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
