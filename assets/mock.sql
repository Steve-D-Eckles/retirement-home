USE retirement;

INSERT INTO roles (role_name, access_level)
VALUES ('admin', 1),
       ('super', 2),
       ('doctor', 3),
       ('caretaker', 4),
       ('patient', 5),
       ('family', 6);

INSERT INTO users (first_name, last_name, email, password, phone, dob, role, confirmed)
VALUES ('alex', 'admin', 'alex@admin.com', '$2y$10$lubrrjNXcgMzdAX/Bfy8Q.3ECfqnODxiTY2J5o55Sd.7vB6qQosXO', '5555555', '1900-01-01', 1, '1900-01-01'),
       ('sam', 'super', 'sam@super.com', '$2y$10$SNAt7APuAhan4VKS0sTDBO6Vq/aK9kh1w0W4zGuyv6w2v8uOcw0Aq', '5555555', '1900-01-01', 2, '1900-01-01'),
       ('dana', 'doctor', 'dana@doctor.com', '$2y$10$N0eI6UXVfWqzzGrxmHX9T.j.dNbquLAKkeeFzZaccRHBWvMSsP4ui', '5555555', '1900-01-01', 3, '1900-01-01'),
       ('chris', 'caregiver', 'chris@caregiver.com', '$2y$10$ijEx.yJ5ouFfAfqAYJK/YeGWWyryQf1NGDl.D3j0U.6dybTJ64632', '5555555', '1900-01-01', 4, '1900-01-01'),
       ('percy', 'patient', 'percy@patient.com', '$2y$10$jKlbNd48PBoOx/1mOQPYgOr6hUySSdxVrDwPrPlREZvGSjuVuvqbK', '5555555', '1900-01-01', 5, '1900-01-01'),
       ('prudence', 'patient', 'prudence@patient.com', '$2y$10$hmYr1ytaOox39hsB.ZJ8dO2MJVku6WViwpmvM3nvplwN2HxTjK.9G', '5555555', '1900-01-01', 5, NULL),
       ('frank', 'family', 'frank@family.com', '$2y$10$1yKhdPu1FOiGPGmZ8v7fzumbuydyYhtFs6RCEV0OHvQq7WoYTNMMq', '5555555', '1900-01-01', 6, '1900-01-01');

INSERT INTO patients (patient_id, family_code, emergency_contact, ec_relation, group_id)
VALUES (5, 'famcode', 'Frank Family', 'Second Cousin', 1),
       (6, 'famcode', 'Frank Family', 'Second Cousin-in-law', 1);

INSERT INTO employees (employee_id, salary, group_id)
VALUES (1, 100000, NULL),
       (2, 65000, NULL),
       (3, 180000, NULL),
       (4, 22000, 1);

INSERT INTO checklists (patient_id, caregiver_id, list_date, morn_med, afternoon_med,
                        night_med, breakfast, lunch, dinner)
VALUES (5, 4, '2020-11-09', 1, 1, 1, 1, 1, 1),
       (5, 4, CURDATE(), 0, 0, 0, 0, 0, 0);

INSERT INTO appointments (patient_id, doctor_id, appt_date, comment, morn_med,
                          afternoon_med, night_med)
VALUES (5, 3, '2020-11-09', 'Pain levels unchanged', 'advil', 'advil', 'advil PM'),
       (5, 3, CURDATE(), NULL, NULL, NULL, NULL);
