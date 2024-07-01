

CREATE TABLE employee (
    employee_ID INT PRIMARY KEY,
    Name VARCHAR(100),
    Salary DECIMAL(10, 2)
);

CREATE TABLE department (
    department_ID INT PRIMARY KEY,
    department_name VARCHAR(100),
    manager_ID INT,
    FOREIGN KEY (manager_ID) REFERENCES employee(employee_ID)
);

CREATE TABLE position (
    position_ID INT PRIMARY KEY,
    position_name VARCHAR(100),
    employee_ID INT,
    FOREIGN KEY (employee_ID) REFERENCES employee(employee_ID)
);

CREATE TABLE employee_department (
    employee_ID INT,
    department_ID INT,
    PRIMARY KEY (employee_ID, department_ID),
    FOREIGN KEY (employee_ID) REFERENCES employee(employee_ID),
    FOREIGN KEY (department_ID) REFERENCES department(department_ID)
);

-- Populate tables with sample data
INSERT INTO employee (employee_ID, Name, salary) VALUES 
(1, 'Marko Marić', 50000.00),
(2, 'Ivo Ivić', 60000.00),
(3, 'Darko Šimić', 70000.00),
(4, 'Antonija Antunović', 80000.00);

INSERT INTO department (department_ID, department_name, manager_ID) VALUES
(1, 'HR', 1),
(2, 'Engineering', 2),
(3, 'Sales', 3);

INSERT INTO position (position_ID, position_name, employee_ID) VALUES
(1, 'HR Manager', 1),
(2, 'Software Engineer', 2),
(3, 'Sales Manager', 3),
(4, 'Developer', 4);

INSERT INTO employee_department (employee_ID, department_ID) VALUES
(1, 1),
(2, 2),
(3, 3),
(4, 2);

-- Retrieve all employees and their salaries
SELECT employee_ID, Name, salary FROM employee;

-- Retrieve all department managers
SELECT e.employee_ID, e.Name, e.salary 
FROM employee e
JOIN department d ON e.employee_ID = d.manager_ID;

-- Calculate the average salary of department managers
SELECT AVG(e.salary) AS AverageManagerSalary
FROM employee e
JOIN department d ON e.employee_ID = d.manager_ID;

-- Calculate the average salary of all employees
SELECT AVG(salary) AS AverageSalary FROM employee;

DROP PROCEDURE IF EXISTS CalculateAverageSalary;

-- Create the procedure
DELIMITER //

CREATE PROCEDURE CalculateAverageSalary()
BEGIN
    DECLARE avgSalary DECIMAL(10, 2);

    -- Calculate the average salary
    SELECT AVG(salary) INTO avgSalary FROM employee;

    -- Display the average salary
    SELECT avgSalary AS AverageSalary;
END //

DELIMITER ;
