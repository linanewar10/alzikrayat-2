# Alzikrayat - Photo Sharing Application

Alzikrayat ("the memories") is a small photo sharing web app built for the
Advanced Web Technologies course. Users can register, log in, upload photos,
browse a gallery, open a photo to see it in detail and leave comments.

It is written in **PHP + MySQL (PDO)** using a **hand-written MVC framework** and
a **3-tier architecture**. No backend framework and no ORM are used - the router
and all SQL queries are written by hand, as required by the project.

## Features

- User registration with server-side validation and bcrypt password hashing
- Login / logout using PHP sessions
- "Last login" cookie that lasts 7 days and is shown on the login page
- Navbar that changes: shows `Hi <name>` + Logout when logged in, `Please Login` otherwise
- Upload photos (saved to `public/images/uploads/`, metadata saved in the database)
- Gallery with 3 display styles: 3 columns, 4 columns and list
- Photo detail page with the image, author, date, description and comments
- Comment on any photo (logged in users only)
- Delete your own photos only (ownership is checked before deleting)
- Bootstrap 5 for a responsive layout (mobile / tablet / desktop)
- Three validation layers on the forms: HTML5, JavaScript and server-side PHP

## Requirements

- PHP 7.4+ (tested on PHP 8.4)
- MySQL / MariaDB
- Apache with `mod_rewrite` (XAMPP is fine)

## Setup (XAMPP)

1. Copy the project folder into `xampp/htdocs/` so the path is
   `xampp/htdocs/alzikrayat`.
2. Start **Apache** and **MySQL** from the XAMPP control panel.
3. Create the database: open phpMyAdmin, go to **Import** and choose the
   `database.sql` file in this project (it creates the `alzikrayat` database
   and the three tables).
4. Check the database settings in `config/database.php` (default is host
   `localhost`, user `root`, empty password - the normal XAMPP values).
5. Open the app in the browser:
   `http://localhost/alzikrayat/public/`

The base URL is detected automatically, so the links also work if you run it
with the PHP built-in server from the `public` folder.

## Project Structure

```
alzikrayat/
├── config/
│   └── database.php          # PDO database connection
├── core/
│   ├── model.php             # base Model (shares the DB connection)
│   ├── controller.php        # base Controller (view + redirect helpers)
│   └── router.php            # regex-based router
├── controllers/
│   ├── AuthController.php     # register / login / logout
│   ├── photoController.php    # gallery, upload, show, delete
│   └── commentController.php  # store comments
├── models/
│   ├── user.php
│   ├── photo.php
│   └── comment.php
├── views/
│   ├── layout/               # header (navbar) + footer
│   ├── auth/                 # login + register
│   ├── photos/               # gallery, upload, detail
│   └── home.php              # landing page
├── public/
│   ├── index.php             # front controller (entry point)
│   ├── .htaccess             # sends all requests to index.php
│   └── images/uploads/       # uploaded photos are stored here
└── database.sql              # database schema
```

## Routes

| Method | Path                     | Action                        |
|--------|--------------------------|-------------------------------|
| GET    | `/`                      | Home / landing page           |
| GET    | `/register`              | Registration form             |
| POST   | `/register`              | Create the account            |
| GET    | `/login`                 | Login form                    |
| POST   | `/login`                 | Log in                        |
| GET    | `/logout`                | Log out                       |
| GET    | `/photos`                | Gallery (`?grid=` for style)  |
| GET    | `/photo/create`          | Upload form                   |
| POST   | `/photo/store`           | Save the uploaded photo       |
| GET    | `/photo/{id}`            | Photo detail + comments       |
| GET    | `/photo/{id}/delete`     | Delete own photo              |
| POST   | `/photo/{id}/comment`    | Add a comment                 |
