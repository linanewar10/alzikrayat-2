# Alzikrayat - Architecture Report

**Course:** Advanced Web Technologies
**Project:** Photo Sharing Application (Alzikrayat)
**Language / Stack:** PHP, MySQL (PDO), Bootstrap 5

---

## 1. Overview

Alzikrayat is a photo sharing web application. Registered users can upload
photos, view them in a gallery, open any photo to see it in full size and add
comments. The goal of the project was to build the application with a
**hand-written MVC framework** and a **3-tier architecture**, without using any
backend framework (Laravel, Django, ...) or any ORM. Every SQL query is written
by hand with PDO prepared statements, and the routing is done manually with
regular expressions.

## 2. 3-Tier Architecture

The application is split into three physical tiers:

- **Presentation tier (Views):** the HTML pages that the user sees. These live
  in the `views/` folder and use Bootstrap for the layout. All dynamic values
  are escaped with `htmlspecialchars()` before being printed.
- **Application / business tier (Controllers + core):** the server logic. This
  is the router, the controllers and the small base classes in `core/`. This
  tier receives the request, validates the input, decides what to do and picks
  the view to render.
- **Data tier (Models + database):** the models in `models/` and the MySQL
  database. The models are the only classes that talk to the database.

Keeping these tiers separate means, for example, that a view never runs a SQL
query directly, and a model never prints HTML.

## 3. MVC Pattern

Inside the application tier the code follows the Model-View-Controller pattern:

- **Model** - one class per table (`User`, `Photo`, `Comment`). Each model
  extends a base `Model` class that opens the PDO connection, so the child
  models just write their queries. Example methods: `User::create()`,
  `Photo::getAll()`, `Comment::getByPhotoId()`.
- **View** - plain PHP templates that only display data passed to them. A shared
  layout (`views/layout/header.php` and `footer.php`) holds the navbar and
  footer so every page looks the same.
- **Controller** - one class per feature (`AuthController`, `PhotoController`,
  `CommentController`). A controller reads the request, calls the models,
  and then renders a view or redirects. Controllers extend a base `Controller`
  class that gives them two helpers: `view()` (render a view inside the layout)
  and `redirect()`.

## 4. The Router

The heart of the framework is the custom router in `core/router.php`. Routes are
registered in `public/index.php` like this:

```php
$router->get('/photo/{id}', function ($id) {
    (new PhotoController())->show($id);
});
```

When a request comes in, `dispatch()` takes the URL path and compares it against
every registered route. Dynamic parts written as `{id}` are turned into a regex
group using `preg_replace`:

```php
$pattern = preg_replace('/\{([^}]+)\}/', '([^/]+)', $route);
$pattern = '#^' . $pattern . '$#';
```

If the pattern matches, the captured values (like the photo id) are passed to the
route's function with `call_user_func_array()`. If no route matches, a 404 is
returned. The base folder of the app (for example `/alzikrayat/public`) is
detected automatically and removed before matching, so the same code works both
on XAMPP and on the PHP built-in server.

## 5. Database Design

The database has three normalized tables with auto-increment integer primary
keys and foreign keys that enforce referential integrity:

- **users** - `id`, `first_name`, `last_name`, `email` (unique), `password`
  (bcrypt hash), and optional `location`, `description`, `occupation`.
- **photos** - `id`, `user_id` (FK to users), `file_name`, `title`,
  `description`, `date_time`.
- **comments** - `id`, `photo_id` (FK to photos), `user_id` (FK to users),
  `comment`, `date_time`.

The foreign keys use **`ON DELETE CASCADE`**: if a user is deleted, their photos
and comments are removed automatically, and if a photo is deleted, its comments
are removed too. Indexes are added on the foreign key columns to keep the joins
fast. The password column only ever stores the bcrypt hash produced by
`password_hash()`, never the raw password.

## 6. Authentication and Sessions

Registration hashes the password with `password_hash(..., PASSWORD_BCRYPT)`.
Login looks up the user by email and checks the password with
`password_verify()`. On success the user's id is stored in `$_SESSION`, which
keeps them logged in across pages.

On every successful login the app also writes a **cookie** called `last_login`
with the current timestamp and a 7-day lifetime. The next time the login page is
opened, this cookie is read and shown as "Last login from this computer was ...".
The cookie is separate from the session, so it stays even after logout.

The navbar reads the session on every page: when a user is logged in it shows
`Hi <first_name>` and a Logout button, otherwise it shows `Please Login` with
Login and Register buttons.

## 7. Validation and Security

Every form is validated on three levels, as required:

1. **HTML5** - `required`, `type="email"`, `maxlength`, `minlength` on the
   inputs.
2. **JavaScript** - a small script on each form checks the values before submit
   (letters-only names, password length, non-empty comment, a file is chosen).
3. **Server-side PHP** - the controllers re-check everything, because the first
   two layers can be bypassed. Names must be letters only, the email must be a
   valid RFC email and unique, the password must be at least 6 characters, and
   uploaded files must be real images under 5 MB.

Two more security points:

- **SQL injection** is prevented by using PDO **prepared statements** with named
  placeholders everywhere - user input is never concatenated into SQL.
- **XSS** is prevented by escaping every dynamic value with `htmlspecialchars()`
  before it is printed in a view.

For the delete feature, ownership is checked twice: the controller compares the
photo's `user_id` with the logged-in user, and the `DELETE` query also has an
`AND user_id = ?` condition, so a user can never delete someone else's photo.

## 8. Conclusion

The project meets the requirements of building a photo sharing application on a
custom MVC framework and a 3-tier architecture, with a hand-written regex
router, raw parameterized SQL, secure authentication, three validation layers
and a responsive Bootstrap interface. The code is organized so that each part
has one clear responsibility, which makes it easier to read and extend.
