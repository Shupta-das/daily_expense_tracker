CREATE TABLE expenses (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    amount DECIMAL(10, 2) NOT NULL,
    category_id INT UNSIGNED NOT NULL,
    description VARCHAR(255),
    date DATE NOT NULL,
    payment_method VARCHAR(50) NOT NULL,
    location VARCHAR(100),
    PRIMARY KEY (id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,   
    FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE CASCADE   
);