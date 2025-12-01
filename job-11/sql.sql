CREATE TABLE clothing (
    product_id INT UNSIGNED PRIMARY KEY,
    size VARCHAR(10),
    color VARCHAR(50),
    type VARCHAR(50),
    material_fee INT,
    FOREIGN KEY (product_id) REFERENCES product(id)
);

CREATE TABLE electronic (
    product_id INT UNSIGNED PRIMARY KEY,
    brand VARCHAR(100),
    warranty_fee INT,
    FOREIGN KEY (product_id) REFERENCES product(id)
);