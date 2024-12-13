CREATE TABLE user_accounts (
	user_id INT AUTO_INCREMENT PRIMARY KEY,
	username VARCHAR(255),
	first_name VARCHAR(255),
	last_name VARCHAR(255),
	password TEXT,
	is_admin TINYINT(1) NOT NULL DEFAULT 0,
	is_suspended TINYINT(1) NOT NULL DEFAULT 0,
	date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP 
);

CREATE TABLE job_posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT NOT NULL,
    posted_by INT NOT NULL,
    date_posted DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE job_applications (
    application_id INT AUTO_INCREMENT PRIMARY KEY,
    job_id INT NOT NULL,
    applicant_id INT NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    position_applied VARCHAR(255) NOT NULL,
    resume_path TEXT NOT NULL,
    date_applied TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	status VARCHAR(255) DEFAULT 'pending'
);


