INSERT INTO categories (user_id, name, budget) VALUES
(1, 'Groceries', 300.00),
(1, 'Entertainment', 100.00),
(1, 'Utilities', 150.00),
(1, 'Rent', 500.00),
(1, 'Transportation', 100.00),
(1, 'Miscellaneous', 50.00),
(2, 'Groceries', 400.00),
(2, 'Entertainment', 150.00),
(2, 'Utilities', 120.00);

INSERT INTO expenses (user_id, amount, category_id, description, date, payment_method, location) VALUES
(1, 50.00, 1, 'Weekly groceries', '2023-01-05', 'Credit Card', 'Local Supermarket'),
(1, 20.00, 2, 'Movie night', '2023-01-10', 'Cash', 'Local Cinema'),
(2, 80.00, 7, 'Monthly grocery shopping', '2023-01-08', 'Debit Card', 'Online Grocery Store'),
(2, 50.00, 8, 'Concert tickets', '2023-01-15', 'Credit Card', 'Local Concert Hall'),
(2, 50.00, 9, 'Other', '2023-01-18', 'Debit Card', 'Hall'),
(2, 50.00, 9, 'Other', '2023-01-19', 'Cash', 'Local Concert'),
(1, 100.00, 1, 'Weekly groceries', '2023-01-05', 'Credit Card', 'Local Supermarket'),
(1, 20.00, 2, 'Movie night', '2023-01-10', 'Cash', 'Local Cinema'),
(1, 100.00, 4, 'Monthly Rent', '2023-01-08', 'Debit Card', 'Home'),
(1, 50.00, 5, 'Bus tickets', '2023-01-15', 'Credit Card', 'Local Bus Station'),
(1, 100.00, 6, 'Weekly groceries', '2023-01-05', 'Credit Card', 'Local Supermarket'),
(1, 20.00, 3, 'Utilities', '2023-01-10', 'Cash', 'Local Cinema');