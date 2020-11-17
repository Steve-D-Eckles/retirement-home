USE retirement;

INSERT INTO roles (role_name, access_level)
VALUES ('admin', 1),
       ('super', 2),
       ('doctor', 3),
       ('caretaker', 4),
       ('patient', 5),
       ('family', 6);

INSERT INTO users (first_name, last_name, email, password, phone, dob, role, confirmed)
VALUES ('alex', 'admin', 'alex@admin.com', '$2y$10$lubrrjNXcgMzdAX/Bfy8Q.3ECfqnODxiTY2J5o55Sd.7vB6qQosXO', '5555555', '1900-01-01', 1, 1),
       ('sam', 'super', 'sam@super.com', '$2y$10$SNAt7APuAhan4VKS0sTDBO6Vq/aK9kh1w0W4zGuyv6w2v8uOcw0Aq', '5555555', '1900-01-01', 2, 1),
       ('dana', 'doctor', 'dana@doctor.com', '$2y$10$N0eI6UXVfWqzzGrxmHX9T.j.dNbquLAKkeeFzZaccRHBWvMSsP4ui', '5555555', '1900-01-01', 3, 1),
       ('chris', 'caregiver', 'chris@caregiver.com', '$2y$10$ijEx.yJ5ouFfAfqAYJK/YeGWWyryQf1NGDl.D3j0U.6dybTJ64632', '5555555', '1900-01-01', 4, 1),
       ('charlie', 'caregiver', 'charlie@caregiver.com' , '$2y$10$UvIzg2dJyQVnqSSmawXigeeGd0cHmJNPV2nMiVX/IEZdNOs/0dHie', '5555555', '1900-01-01', 4, 1),
       ('chuck', 'caregiver', 'chuck@caregiver.com', '$2y$10$vYiB8PL0nQrc9BipqZqlzeoCTcP9OB9FTOawS7SV4I2S3GMT6/bre', '5555555', '1900-01-01', 4, 1),
       ('chelsea', 'caregiver', 'chelsea@caregiver.com', '$2y$10$VHQgoKAJ82TZxy31baVLKezq6Tl5D/pRA5nkdKRvPjHKXQ7uhv6Mu', '5555555', '1900-01-01', 4, 1),
       ('percy', 'patient', 'percy@patient.com', '$2y$10$jKlbNd48PBoOx/1mOQPYgOr6hUySSdxVrDwPrPlREZvGSjuVuvqbK', '5555555', '1900-01-01', 5, 1),
       ('prudence', 'patient', 'prudence@patient.com', '$2y$10$hmYr1ytaOox39hsB.ZJ8dO2MJVku6WViwpmvM3nvplwN2HxTjK.9G', '5555555', '1900-01-01', 5, 0),
       ('frank', 'family', 'frank@family.com', '$2y$10$1yKhdPu1FOiGPGmZ8v7fzumbuydyYhtFs6RCEV0OHvQq7WoYTNMMq', '5555555', '1900-01-01', 6, 1);

INSERT INTO patients (patient_id, family_code, emergency_contact, ec_relation, group_id, admit_date, due)
VALUES (8, 'famcode', 'Frank Family', 'Second Cousin', 1, '1900-01-01', 290),
       (9, 'famcode', 'Frank Family', 'Second Cousin-in-law', 1, NULL, NULL);

INSERT INTO employees (employee_id, salary)
VALUES (1, 100000),
       (2, 65000),
       (3, 180000),
       (4, 22000),
       (5, 22000),
       (6, 22000),
       (7, 22000);

INSERT INTO checklists (patient_id, caregiver_id, list_date, morn_med, afternoon_med,
                        night_med, breakfast, lunch, dinner)
VALUES (8, 4, '2020-11-09', 1, 1, 1, 1, 1, 1),
       (8, 4, CURDATE(), 0, 0, 0, 0, 0, 0);

INSERT INTO appointments (patient_id, doctor_id, appt_date, comment, morn_med,
                          afternoon_med, night_med)
VALUES (8, 3, '2020-11-09', 'Pain levels unchanged', 'advil', 'advil', 'advil PM'),
       (8, 3, CURDATE(), NULL, NULL, NULL, NULL);

INSERT INTO roster (roster_date, supervisor_id, doctor_id, care_one_id, care_one_group,
                    care_two_id, care_two_group, care_three_id, care_three_group,
                    care_four_id, care_four_group)
VALUES ('2020-11-16', 2, 3, 4, 1, 5, 2, 6, 3, 7, 4),
       (CURDATE(), 2, 3, 4, 1, 5, 2, 6, 3, 7, 4);
