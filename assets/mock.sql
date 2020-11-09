USE retirement;

INSERT INTO roles (role_name, access_level)
VALUES ('admin', 1),
       ('super', 2),
       ('doctor', 3),
       ('caretaker', 4),
       ('patient', 5),
       ('family', 6);

INSERT INTO users (first_name, last_name, email, password, phone, dob, role, confirmed)
VALUES ('alex', 'admin', 'alex@admin.com', 'password', '5555555', '1900-01-01', 1, 1),
       ('sam', 'super', 'sam@super.com', 'password', '5555555', '1900-01-01', 2, 1),
       ('dana', 'doctor', 'dana@doctor.com', 'password', '5555555', '1900-01-01', 3, 1),
       ('chris', 'caretaker', 'chris@caretaker.com', 'password', '5555555', '1900-01-01', 4, 1),
       ('percy', 'patient', 'percy@patient.com', 'password', '5555555', '1900-01-01', 5, 1),
       ('prudence', 'patient', 'prudence@patient.com', 'password', '5555555', '1900-01-01', 5, 0),
       ('frank', 'family', 'frank@family.com', 'password', '5555555', '1900-01-01', 6, 1);

INSERT INTO patients (patient_id, family_code, emergency_contact, ec_relation, group_id)
VALUES (5, 'famcode', 'Frank Family', 'Second Cousin', 1),
       (6, 'famcode', 'Frank Family', 'Second Cousin-in-law', 1);

INSERT INTO employees (employee_id, salary, group_id)
VALUES (1, 100000, NULL),
       (2, 65000, NULL),
       (3, 180000, NULL),
       (4, 22000, 1);

INSERT INTO checklists (patient_id, caretaker_id, list_date, morn_med, afternoon_med,
                        night_med, breakfast, lunch, dinner)
VALUES (5, 4, '2020-11-09', 1, 1, 1, 1, 1, 1),
       (5, 4, CURDATE(), 0, 0, 0, 0, 0, 0);

INSERT INTO appointments (patient_id, doctor_id, appt_date, comment, morn_med,
                          afternoon_med, night_med)
VALUES (5, 3, '2020-11-09', 'Pain levels unchanged', 'advil', 'advil', 'advil PM'),
       (5, 3, CURDATE(), NULL, NULL, NULL, NULL);
