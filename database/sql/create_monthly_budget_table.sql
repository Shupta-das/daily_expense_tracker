CREATE TABLE monthly_budget(
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    total_budget DECIMAL(10, 2) NOT NULL,
    month_no DECIMAL(2, 0) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);