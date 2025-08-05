# Fundamentals

This chapter will cover the basics of PHP including the setup / installitions and some syntax

## Installing PHP, MySQL, Homebrew & DBeaver

The Version 8.4.7 of PHP was already installed on my machine. Before installing MySQL I installed HomeBrew to make the process easier.I used to commands:

```
pacman -Syu

pacman -S base-devel

/bin/bash -c "$(curl -fsSL https://raw.githubusercontent.com/Homebrew/install/master/install.sh)"

[ -d /home/linuxbrew/.linuxbrew ] && eval $(/home/linuxbrew/.linuxbrew/bin/brew shellenv)


```

To install MySQL I used the commands: 

`brew install mysql` 

To interact with my MySQL Database I decided to install the database GUI application DBeaver with the command:

`sudo pacman -S dbeaver`

---

## First PHP Server

In this chapter I setup a small PHP server that serves a dynamic html file. You can find the files inside the folder

`php-for-beginners/01-exercise`

### Static File Serving

1. created a simple index.html file 
2. served the file with command `php -S localhost:8888`

### Dynamic File Serving

1. create a simple index.php file with php code inside
2. served the file with command `php -S localhost:8888`

---

## Concat

In PHP you use the period `.` to combine strings.

`echo "hello" . " world";`

---

## Variables in PHP

You create a variable using the dollar sign `$`

`$greeting = "hello";`

There is an important difference between single and double quotes in PHP strings.

- Double quotes allow variable interpolation (variables     inside the string are evaluated).
- Single quotes treat the content literally (variables are not evaluated).

```
echo "$greeting everybody"; // Outputs: hello everybody
echo '$greeting everybody'; // Outputs: $greeting everybody
```

---

## Conditionals and Booleans

In PHP, conditionals start with the keyword `if`

`<?= $variable ?>` is shorthand for `<?php echo $message; ?>`
You can omit the semicolon in this shorthand because the PHP block closes immediately after.

---

## Arrays

You create an array in PHP using square brackets:
`$books = [];`

In PHP you can loop through the array with `foreach`:

```
<?php foreach ($books as $book) { ?>
    <li><?php echo $book; ?></li>
<?php } ?>
```

- runs once for every item in Books
- `book` is the current item 

### Alternative foreach Syntax for HTML

When mixing PHP and HTML, the alternative syntax can be cleaner:

```
<?php foreach ($books as $book): ?>
    <li><?= $book ?></li>
<?php endforeach; ?>
```

This uses a colon `:` instead of an opening brace and `endforeach;` instead of a closing brace.

This syntax is especially useful when rendering complex HTML inside loops or conditionals.

## Associative Arrays

Arrays in PHP are zero-based. If you want the first item you need to call:

`echo $books[0];`

### Storing more Complex Data with Arrays

Instead of just strings, you can store arrays within arrays. This way, each book becomes an array containing multiple pieces of information.

```
$books = [
    [
        "name" => "Do Androids Dream of Electric Sheep?",
        "author" => "Philip K. Dick",
        "purchaseUrl" => "https://example.com/androids-dream"
    ],
    [
        "name" => "Project Hail Mary",
        "author" => "Andy Weir",
        "purchaseUrl" => "https://example.com/hail-mary"
    ]
];
```

This is called an associative array, where each value is associated with a key.

### Filter for Variable

Inside your loop over books, add a conditional to check if the book's author is Andy Weir. Only render the list item if this condition is true.

```
<?php if ($book['author'] === 'Andy Weir'): ?>
    <li><?= $book['name'] ?> (<?= $book['releaseYear'] ?>)</li>
<?php endif; ?>
```
`=` to assign
`===` to compare

---

## Functions

How to define a funtion in PHP:

```
function filterByAuthor($books) {
    // funtion goes here
};
```

You call like this:

`$filteredBooks = filteredByAuthor($books);`

In PHP, you cannot directly echo an array. You need to loop through the array or convert it to a string before attempting to echo it

To list all results of the array, you need to display it like this:

```
 <ul>
        <?php foreach (filteredByAuthor($books, "Andy Weir") as $book) : ?>
            <li>
                <a href="<?= $book['purchaseUrl'] ?>">
                    <?= $book['name']; ?> (<?= $book['releaseYear'] ?>) - By <?= $book['author'] ?>
                </a>
            </li>
        <?php endforeach; ?>    
    </ul>
```

---

## Lambda Functions

PHP supports anonymous functions (lambda functions). They have no name and can be assigned to variables or passed as arguments.

They can look like this:

```
$filterByAuthor = function($books) {
    // filtering logic
};
```

You can then call it via the variable:

`$filteredBooks = $filterByAuthor($books);`

---

### Lambda with Callback Function

A callback funtion is a function that is passed as an argument to another funtion. It is executed in the context of the calling function

```
function callbackFuntion($arguments) {
    return $arguments + 1;
}

function parentFunction($callback, $arguments) {
    // Code to execute before the callback
    $result = $callback($arguments);
    // Code to execute after the callback
    return $result;
}
```

I can also directly define the callback function in the arguments head of the parent funtion like this:

```
filteredBooks = filter ($books, function ($book) {
            return $book['author'] === "Andy Weir";
        });
```

---

### PHP built-in functions 

PHP provides a built-in function array_filter that iterates through an array and filters out elements based on a callback function.

---

## Separating Logic from the Template

Many PHP developers separate the PHP code that gathers data from the HTML that displays it. For example, you can move all PHP logic out of the `<body>` tag and place it at the very top of the document. This clearly separates logic from presentation.

Regarding PHP code that loops over arrays and builds HTML, this part is often called the `template` or `view`. In programming, we say the template or view should be dumb—meaning it should avoid complex logic, external service calls, or database interactions. Its job is simply to render data passed to it.

At some point the application will grow to a point where a single file becomes messy. To avoid this, you should split up the logic into it's own file.

You might have one file for PHP logic (e.g., `index.php`) and another for the view or template (e.g., `index.view.php`).

In the view file, keep only the core HTML and any necessary PHP to echo or loop over data.

In the logic file, keep only the PHP that prepares data. You can omit the closing PHP tag if the file contains only PHP code.

To connect the two, use PHP's `require` or `include` to load the view file from the logic file:

`require 'index.view.php';`

This way, the view has access to all data defined in the logic file.

Adjust PHP logic in `index.php`

Adjust presentation in `index.view.php`

# Dynamic Web Applications

## Setting up tailwind.css

First I setup a basic logic and view file. Inside the view file I've used a tailwind html template (`https://tailwindcss.com/plus/ui-blocks/application-ui/application-shells/stacked`).

I imported Tailwind CSS via cdn:

`<script src="https://cdn.tailwindcss.com"></script>`

And added the required classes to the html and body tag.

```
<html class="h-full bg-white">
<body class="h-full">
```

To learn about different routes I created multiple logic and view files for the:

- Homepage
- Contact Page
- About Us Page
  
In the view files for each page I adjusted the links.

## PHP Partials

When building a simple static website, duplication is often unavoidable, especially with HTML and CSS. However, since using a dynamic language like PHP, you can eliminate this duplication entirely.

A partial is a reusable piece of HTML. The Navbar for example, will be reused on most pages. You can put the file nav.php inside the partials directory.

You can use a partial inside a main file like this:

`<?php require 'partials/nav.php'; ?>`

This way any changes to the navigation only need to be made in one place and all views will update.

### Making Partials Dynamic

When you need to display view specific content inside a partial, you can pass variables from the controller to your view and partials:

Declaring the variable inside the controller:

`$heading = 'About Us';`

Using it inside the partial:

`<h1><?= $heading ?></h1>`

### Controllers

PHP Files that prepare date und load views are often called controllers. A controller accepts a request and provides a response.

The separation of concerns, that controllers handle logic and views handle presenation, is foundamental in modern PHP applications and frameworks.

---

## Superglobals and Current Page

PHP provides superglobals like `$_SERVER` accessible anywhere. The key `$_SERVER['REQUEST_URI']` contains the current URL path.

You can use the superglobals to conditionally apply styles based on your current URL.

```
<?= ($_SERVER['REQUEST_URI'] === '/') 
    ? 'class="bg-gray-900 text-white"' 
    : 'class="text-gray-300 hover:bg-gray-700 hover:text-white"' ?>
```

---

### Helper Functions in separate File

Functions that multiple php files will use can also be extracted in an extra file. This avoids duplicating function across files.

## PHP Router

A router allows a single entry point responsible for routing URIs to controllers.

Inside my `router.php` I mapped all the routes to the corresponding controllers inside an associative array:

```
$routes = [
    '/' => "controllers/index.php",
    '/about' => "controllers/about.php",
    '/contact' => "controllers/contact.php",
];
```

The normal routes are opened inside another function:

```
function routeToController($uri, $routes) {

    if (array_key_exists($uri, $routes)) {
        require $routes[$uri];
    } else {
        abort(404);
    }
}
```

The built-in function `array_key_exists()` allows to check for specific keys inside an array. With that I'm looking up the URI.

If the URL is not inside my URL List, I'm calling a function that handles none existing routes or error codes:

```
function abort($code = 404) {
    http_response_code($code);
    require "views/{$code}.php";
    die();
}
```

---

## MySQL

### Fixing MariaDB Errors

Due to some errors and privilege issues my mariadb server couldnt access some important files. Too resolve this I stopped the server with the command:

`systemctl stop mariadb`

After that I removed any exisiting data and log directories:

`rm -rf /var/lib/mysql`

`rm -rf /var/log/mysql`

I created these directories again but empty:

`mkdir /var/lib/mysql`

`mkdir /var/log/mysql`

To give my user access to these directories I used the commands:

`chown mysql:mysql /var/lib/mysql`

`chown mysql:mysql /var/log/mysql`

To initialize mariadb again I executed:

`mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql`

To finish everything I started the mariadb server again:

`systemctl start mariadb`

---

### Creating a MySQl Database

You can create new database via the terminal with the commands:

```
mysql -u root
CREATE DATABASE myapp;
```

### Accessing the Database via DBeaver

When first trying to connect to the MariaDB Database with DBeaver I got the error:

`(conn=14) Access denied for user 'root'@'localhost'`

This meant that the root user for localhost couldn't connect to the database. First I checked to privileges of the user inside the database:

`SHOW GRANTS FOR 'root'@'localhost';`

Which got me the output:

```
+-----------------------------------------------------------------------------------------------------------------------------------------+
| Grants for root@localhost                                                                                                               |
+-----------------------------------------------------------------------------------------------------------------------------------------+
| GRANT ALL PRIVILEGES ON . TO root@localhost IDENTIFIED VIA mysql_native_password USING 'invalid' OR unix_socket WITH GRANT OPTION |
| GRANT PROXY ON ''@'%' TO 'root'@'localhost' WITH GRANT OPTION                                                                           |
+-----------------------------------------------------------------------------------------------------------------------------------------+
```

This meant that the user had the right privileges. Next I've wanted to see if it's a problem with the password. Theoretically it should be empty:

`SELECT User, Host, Password FROM mysql.user WHERE User = 'root';`

I got the output:

```
Connection id:    4
Current database: 02_dynamic_web
+------+-----------+----------+
| User | Host      | Password |
+------+-----------+----------+
| root | localhost | invalid  |
+------+-----------+----------+
```

This showed that something with the password is faulty. To reset it I used the command:

`SET PASSWORD FOR 'root'@'localhost' = '';`

Now the user table outputs:

```
+------+-----------+----------+
| User | Host      | Password |
+------+-----------+----------+
| root | localhost |          |
+------+-----------+----------+

```

---

Now I can make a connection with DBeaver on `root@localhost:3306`

---

### Creating a Table

First I created a Table with a column for the ID that automatically increments for the fitted row and a column for title where a book named is stored with this SQL Command:

```
CREATE TABLE posts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL
);
```

---

## Connecting to the Database with PHP

To enable the PDO extension for PHP you need to configure the `php.ini` file and write in the line:

`extension=pdo_mysql`

To connect to a database you need to create a `PDO` object (PHP Data Objects):

`$pdo = new PDO($dsn, $username, $password);`

A PDO needs a Data Source Name ($dsn) string as an argument. This string speficies connection details like host, port, database name and charset:

`$dsn = 'mysql:host=localhost;port=3306;dbname=myapp;charset=utf8mb4';`

### Retrieving the Data

First you need to create a PDO Object like this:

`$pdo = new PDO($dsn, $username, $password);`

You can prepare a query in PHP like this:

`$statement = $pdo -> prepare("select * from posts");`

- `$statement` : inside the variable a prepared PDO Object with a Query will be stored
- `$pdo -> prepare(query)"` : the arrow is the method invocation operator which triggers the function `prepare()` of the `$pdo` Class and returns a PDOStatement object
- `prepare("select * from posts");` : this stores the SQL query inside the object

But this only stores the Query inside the object but doesn't execute it. If you want to do this you can call the `execute()` method of the PDOStatement class first to check if the Query will execute succesfully:

`$statement -> execute();`

Because the fetchAll() method of the PDOStatement Object returns an array containing all fetched rows as an array, you need to store it in another variable:

`$posts = $statement -> fetchAll(PDO::FETCH_ASSOC);`

- the argument `PDO::FETCH_ASSOC` returns it as an associative array

---

## Creating a Class

I've created a Class Database inside the `Database.php` file. With this class you can create an Object that connects to the database on construction:

```
class Database {

    public $connection;

    public function __construct() {
        $dsn = 'mysql:host=localhost;port=3306;dbname=02_dynamic_web;charset=utf8mb4';
        $username = 'root';
        $password = ''; // or your password

        $this -> connection = new PDO($dsn, $username, $password);
    }
}
```

To make the PDO Object usable outside of the class scope I stored it with `$this -> connection` inside the public `$connection` class variable.

I also added a method that prepares and tests the eceution of an dynamic SQL Query:

```
public function query($query){
        

        $statement = $this -> connection -> prepare($query);

        $statement -> execute();

        return $statement;

    }
```

Inside the `index.php` file I've created a Database object, called the `query()` method and fetched the data inside a $variable.

---

## Creating a Config File

To make the Database setup more dynamic I've created a config.php file where I store the credentials in an array. To use this data inside another file the config.php uses a return statement to share the data.

```
return [
    'database' =>[
            'host' => 'localhost',
            'port' => 3306,
            'dbname' => '02_dynamic_web',
            'charset' => 'utf8mb4'
    ],
    'login' => [
        'user' => 'root',
        'password' => ''
    ]
];
```

Over the index.php I'm calling the config.php file and pass the array to the Database class:

```
$db = new Database($config['database'], $config['login']['user'], $config['login']['password']);
```

### Built-In Funtion to create DSN

To make the DSN dynamic I've changed the creation from a static URL:

`mysql:host=localhost;port=3306;database=myapp;charset=utf8mb4`

And used a Built-In PHP Function `http_build_query` which creates a query string from the passed Config Array:

`$dsn = 'mysql:' . http_build_query($config, '', ';');`

### Using Constants to Configure PDO

PDO uses constants like PDO::ATTR_DEFAULT_FETCH_MODE to configure behavior. These constants are static and shared across all instances.

I've used these to set the default fetch mode to associative arrays:

```
$this -> connection = new PDO($dsn, $username, $password, [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
```

---

## Protect against SQL Injections 

To avoid an SQL Injection vulnerability inside your application, you can split up your SQl Query String and the user input and validite first if it's a query that's approved.

In case of the current application you can store the ID from the URL in a separate variable:

`$id = $_GET['id'];`

To make the SQL Query open to insert a approved ID I've stored it like this:

`$query = "select * from posts where id = ?";`

- with the `?` at the end it's possible to insert something later

I pass both variables to my Database query method.

Inside the method the `$statement` gets prepared with only the flexible `$query` string. The ID gets appended during the `execute()` method:

```
$statement = $this -> connection -> prepare($query);

$statement -> execute($params);
```

# Mini Project 

In this part I'm starting to apply my previously learned fundamentals to a small note taking application.

## Creating the Database for the Project 

I started out again with creating a MariaDB Database for the project:

`CREATE DATABASE 03_mini_project`

After that I created a table called `notes` and `users` inside the DB with the commands:

```
CREATE TABLE `03_mini_project`.`notes`(
    id INT AUTO_INCREMENT PRIMARY KEY,
    body TEXT NOT NULL,
    user_id INT NOT NULL,
    FOREIGN KEY (user_id) REFERENCES `03_mini_project`.`users`(id) ON DELETE CASCADE
);
```

```
CREATE TABLE `03_mini_project`.`users`(
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL
);
```

These table have these properties:

```
notes_table:
    - primary key: yes
    - c1: 
      - cname: id
      - data_type:int 
      - cdefault: auto_increment
    - c2:
      - cname: body
      - data_type: text 
      - is_nullable: no
    - c3:
      - cname: user_id
      - data_type: int
      - is_nullable: no
      - foreign_key: 
        - reference_table: users
        - reference_columns: id
        - On_Delete: Cascade

users_table:
    - primary_key: yes
    - c1:
      - cname: id 
      - data_type: int 
      - cdefault: auto_increment
    - c2: 
      - cname: name
      - data_type: varchar(255)
      - is_nullable: no
    - c3:
      - cname: email
      - data_type: varchar(255)
      - is_nullable: no 
      - is_unique: true
```

--- 

To fill the tables with data that I can work with I used:

```
INSERT INTO `03_mini_project`.`users` (name, email)
VALUES
('John Doe', 'john.doe@example.com'),
('Jane Smith', 'jane.smith@example.com'),
('Alice Johnson', 'alice.johnson@example.com');
```

```
INSERT INTO `03_mini_project`.`notes` (body, user_id)
VALUES
-- John Doe's notes
('This is a sample note from John Doe.', 1),
('Another note about something important.', 1),

-- Jane Smith's notes
('First note from Jane Smith.', 2),
('Second note: Meeting at 3 PM tomorrow.', 2),

-- Alice Johnson's notes
('Alice''s first note.', 3),
('Remember to call back John Doe tomorrow afternoon.', 3);
```

## Preparing the Notes Page

I will continue to work with the Skeleton Project I set up in `02_Dynamic_Web_Applications`

First I had to change my `index.php` to load the `home.php` page with my router:

`require "router.php";`

After that I created a new page for the notes app:

1. added Route to Router
2. created Controller for Route
3. created View for Controller 

### Setting up DB Connection 

First I updated the `config.php` file to match my new Database.

To make routes access the Database Class they need to be instantiated after `Database.php`:

`index.php`:

```
<?php
require "functions.php";

require "Database.php";

require "router.php";
```

With that I can create a Database Object inside the `notes.php` controller:

```
$config = require('config.php');

$db = new Database($config['database'], $config['login']['user'], $config['login']['password']);
```

## Displaying Data 

With the code:

```
$id = "0";

$queryNotes = "select * from notes where id >= ?";

$notes = $db -> query($queryNotes,[$id]) -> fetchAll();
```

I can list all notes from my database.

---

Now I want to go to the single note. For that I also added a `Note` Router, Controller and View. 

In my `notes.view.php` I've made a list with the array from database with every note. This array also includes the note's ID. With that ID I'm creating a route for every note:

```
<?php foreach ($notes as $note) : ?>
    <li>
        <a href="/note?id=<?=$note['id'] ?>" class="text-blue-500 hover:underline">
            <?=$note['body'] ?>
        </a>
    </li>
<?php endforeach; ?>
```
Now I can use the ID from the URL in my `note.php` file to look up more data in my database:

`http://localhost:8888/notes?id=1` for ID 1.

With this ID I'm looking up the name of the Person in my Users table:

```
$queryUserName = "select * from users where id = ?";
$user_id = $note["user_id"];
$user = $db -> query($queryUserName,[$user_id]) -> fetch();
```

Now I can Use the name inside the `$heading variable`:

`$heading = $user["name"];`

---

## Authorization for SQL Request

To ensure that a user can only access resources they are permitted to there needs to be an authorization process in the controller that makes the SQL Request.

In this project I only want the user who created the note to be able to see the content of the note. I implement this by checking if the current `user_id` of the user matches the `user_id` linked in the database to the note.

Getting the note:
```
$note = $db -> query($queryNote,[
    'id' => $id
]) -> fetch();
```

Checking first if the note even exist and then if the user is authorized to see it:
```
$user_id = $note["user_id"];

if (! $note) {
    abort();
}

if ($user_id !== 1) {
    abort(403);
}
```

When the user is not authorized I'm redirecting him to the 403 error page.

---

## Post Request from Form

Until this point I only used Get-Request to retrieve data. In the next step I want to add data to my database. For that I need to implement post functions.

First I added a route to create a note. Inside this route view I added a form to write your own note. The default of a form is a GET method. If you want to send data you have spefically state that like this:

```
<form method="POST">
  <!-- form fields -->
</form>
```

GET requests are idempotent, meaning multiple identical requests do not change server state and always return the same result. 

POST requests are used for actions that change server state, like creating new records. Submitting a form multiple times with POST can create multiple records, so POST is appropriate for form submissions.

You can detect the request method in PHP using the `$_SERVER['REQUEST_METHOD']` superglobal:

```
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle form submission
    $formData = $_POST;
    // Process and save data
}
```

This allows the same script to display the form on GET requests and process data on POST requests. The `$_POST superglobal` contains all form data submitted via POST. 

With the following code I can insert into the database:

```
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db->query('INSERT INTO notes(body, user_id) VALUES(:body, :user_id)', [
        'body' => $_POST['body'],
        'user_id' => 1
    ]);
}
```

---

## Server-Side Validation

Adding a `required` attribute to the form field adds a client site restriction that the form can't be empty on submission. But this doesn't prevent the user from sending manual POST request like this:

`curl -X POST http://localhost:8888/note/create -d "body="`

To prevent this the server needs to validate the user input data before inserting it into the database. With the `strlen` method you can check the length request body. If it's empty you can dany the request like this:

```
$errors = [];

    if (strlen($_POST['body']) === 0) {
        $errors['body'] = 'A body is required';
    }

    if (strlen($_POST['body']) > 1000) {
        $errors['body'] = 'The body can not be more than 1,000 characters.';
    }
```

When the body is empty I'm adding the error message to the $errors array. This helps me on one side to check if user put valid data into the form to trigger the POST Request to the database and on the otherside to trigger the display of the error message in the HTML:

```
<?php if (!empty($errors['body'])): ?>
    <p class="text-red-500 text-xs mt-2"><?= htmlspecialchars($errors['body']) ?></p>
<?php endif; ?>
```

### Preserving User Input after Validation Error

If a user gives the wrong input it's helpful to let him correct his mistake without having to retype everything:

`<textarea name="body"><?= htmlspecialchars($_POST['body'] ?? '') ?></textarea>`

With the coalescing operator `??` avoids giving warnings when the POST data is not set yet. A coalescing operator is a logical operator in programming that returns its left-hand operand if it's not null or undefined. Otherwise, it returns its right-hand operand.

## Pure Functions and Static Methods

A pure function only depends on its input arguments and has no side effects or dependencies on external states, like the an instantiated class object. This means you can call the function without instantiating the class like this:

`Validator::string($value);`

---

## Organizing by Resource

To keep files that are related it makes sense to group them together in directories. For example it makes sense to create a folder that stores every file that's connected to the note function in the views and controller directory. 

Now that every file that is related to the notes function is in the same folder, names can be simplified by removing redundant prefixes like note and only including the actual function of the file:

`createNote.php` -> `create.php`

Common conventions for controllers are:

`index.php` for listing all notes
`show.php` for displaying a single note
`create.php` for showing the form to create a new note

And for the views:

`index.view.php`
`show.view.php`
`create.view.php`

### Base Path and Helper Functions

After moving the files are nested. This makes it hard to keep track of updating the file paths. To make things easier you can use `__DIR__` to navigate from the current file with a relative path:

`require __DIR__ . '/../partials/nav.php';`

You can define a constant thats pointing to the project root to use absolute paths in your files:

`const BASE_PATH = __DIR__ . '/../';`

With that you can create a helper function that returns the absolute path of your file:

```
function base_path($path) {
    return BASE_PATH . $path;
}
```

Since loading views is common, create a view() helper function that accepts a view path and an optional array of data. Use PHP's extract() to make data keys available as variables inside the view:

```
function view($path, $attributes = []) {
    extract($attributes);
    require base_path('views/' . $path);
}
```

This simplifies loading views and passing data.

### Autoloading classes 

Instead of manually requiring classes like Database or Validator in every controller, you can use PHP's spl_autoload_register() to automatically load classes when they're instantiated. Implement logic to map class names to file paths, typically inside a core directory for generic infrastructure classes.

Move generic classes like Database, Validator, Response, and helper functions into a core directory. This separates application-specific code (like Note or User classes) from reusable infrastructure code.


---

## Namespaces

In PHP, namespaces are used to organize code into hierarchical groupings, preventing name conflicts when using classes, interfaces, and functions with the same name from different libraries or projects. 
A namespace is a global scope that defines a section of code that's separate from other sections. It provides a way to group related code together and avoid naming collisions.

To declare a namespace, use the `namespace` keyword followed by the namespace name on top of the file:

`namespace Core;`

When you use this class elsewhere, you must reference it with its full namespaced path:

`$db = new \core\Database();`

Alternatively, you can import the class at the top of your file with the use keyword:

```
use core\Database;

$db = new Database();
```

This acts like an alias, allowing you to refer to the class without the full namespace.

### Autoloading with Namespaces

When using namespaces, your autoloader needs to translate the namespace separators `\` into directory separators `/` or `\` depending on your OS. For example, a class `core\Database` corresponds to the file path `core/Database.php`.

You can use PHP's `str_replace` function to replace backslashes with the appropriate directory separator:

```
$class = str_replace('\\', DIRECTORY_SEPARATOR, $class);
require basePath("{$class}.php");
```

If you reference a global PHP class like PDO inside a namespaced file, PHP will look for it inside the current namespace unless you prefix it with a backslash:

`$pdo = new \PDO(...);`

Or import it:

```
use PDO;
$pdo = new PDO(...);
```

## Delete Request

Using an anchor tag (`<a>`) for delete actions is inappropriate because anchor tags issue GET requests, which should be idempotent and not cause state changes. Deleting a note is not idempotent and should not be triggered by a GET request.

Instead, you should wrap the delete action in a form with a submit button:

```
<form method="POST">
  <input type="hidden" name="id" value="<?= $note['id'] ?>">
  <button type="submit" class="text-red-500 text-sm mt-2">Delete</button>
</form>
```

This submits a POST request, which is appropriate for actions that modify server state.

In the controller handling the note display, you should detect if the request method is POST:

```
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Handle deletion
}
```

Before deleting, you should verify that the current user owns the note to prevent unauthorized deletions:

```
$id = $_GET['id'];
    $queryNote = 'select * from notes where id = :id';

    $note = $db -> query($queryNote,[
        'id' => $id
    ]) -> findOrFail();

    authorize($note['user_id'] === $currentUserID);
```

Then use a prepared SQL statement to delete the note by ID:

```
$queryDeleteNote = "delete from notes where id = :id";

    $db -> query($queryDeleteNote, [
        'id' => $id
]);
```

After a successful deletion, it should redirect the user back to the notes list:

```
header('Location: /notes');
exit;
```

## Complex Router

A router needs to support different HTTP request types like GET, POST, DELETE, PATCH and PUT. This will help clean up messy controller code that currently handles multiple request types in one place.

For that I implemented a `Router.php` class.Instead of a flat array of routes, I'm using a router object with methods for each HTTP request type:

```
$router->get('/home', 'controllers/index.php');
$router->delete('/note', 'controllers/notes/destroy.php');
```

Each method registers a route for a specific request type, making routing clearer and more organized.

The router class has the methods:

```
get()
post()
delete()
patch()
put()
``` 

Each method accepts a URI and controller path and stores them in a protected routes array with the request method:

```
protected $routes = [];
protected function add($method, $uri, $controller)
    {
        $this->routes[] = compact('method', 'uri', 'controller');
    }
```

A `route()` method handles the actual routing. It accepts the current URI and request method. The it loops through the registered routes and matches both URI and method. If a match is found, it requires the corresponding controller. Otherwise, it aborts:

```
public function route($uri, $requestMethod)
{
    $requestMethod = strtoupper($requestMethod);

    foreach ($this->routes as $route) {
        if ($route['uri'] === $uri && strtoupper($route['method']) === $requestMethod) {
            require basePath($route['controller']);
            return;
        }
    }

    abort(404);
}
```

Since HTML forms only support `GET` and `POST`, to simulate other methods like `DELETE`, include a hidden input named `_method` with the desired method:

```
<input type="hidden" name="_method" value="DELETE">
```

In your router or request handling logic, you have to check for this `_method` field in POST data and use it as the request method if present:

```
$method = $_POST['_method'] ?? $_SERVER['REQUEST_METHOD'];
```

## One Controller per Request

It makes sense to extend a router to different HTTP request types, to allow to cleanly separate controller actions based on the request method. 

With the complex router you can define routes that respond specifically to GET, POST, DELETE, etc:

```
$router->get('/note/{id}', 'controllers/notes/show.php');
$router->delete('/note/{id}', 'controllers/notes/destroy.php');
```

This lets you have separate controller files for showing and deleting a note.

Since HTML forms only support GET and POST, to simulate a DELETE request, we use a hidden input named `_method` with the value DELETE. The router checks for this override and routes accordingly.

### Controller Flow for Store Action

1. Validate the input.
2. If validation passes, insert the note into the database
3. Redirect to the notes list.
4. If validation fails, return the form view with errors.

## Container

A container is a tool that helps manage object creation and dependencies in your application. While frameworks like Laravel or Symfony provide sophisticated containers out of the box, understanding the basics will give you insight into how they work.

Currently, in multiple places, I instantiate classes like the database and configure them repeatedly. This is tedious and error-prone, especially as the application grows and dependencies become more complex.

A container acts as a registry where you can bind (store) objects or factory functions and later resolve (retrieve) fully constructed instances.

```
class Container
{
    protected $bindings = [];

    public function bind(string $key, callable $resolver)
    {
        $this->bindings[$key] = $resolver;
    }

    public function resolve(string $key)
    {
        if (!isset($this->bindings[$key])) {
            throw new Exception("No binding found for key: {$key}");
        }
        return call_user_func($this->bindings[$key]);
    }
}
```

You bind a key to a factory function that creates the object:

```
$container->bind('core\Database', function () {
    return new \core\Database($config);
});
```

Later, you resolve the object anywhere in your app:

```
$db = $container->resolve('core\Database');
```

To avoid instantiating the container everywhere, you can create an App class that holds a static reference to the container:

```
class App
{
    protected static $container;

    public static function setContainer(Container $container)
    {
        static::$container = $container;
    }

    public static function container()
    {
        return static::$container;
    }

    public static function resolve(string $key)
    {
        return static::$container->resolve($key);
    }

    public static function bind(string $key, callable $resolver)
    {
        static::$container->bind($key, $resolver);
    }
}
```

With that you can bind and resolve via App:

```
App::bind('core\Database', function () {
    return new \core\Database($config);
});

$db = App::resolve('core\Database');
```

## Updating with PATCH Request

To complete the CRUD-App I added a `update.php` route to edit a note. This Routes listens to PATCH request to `/notes`.

Update Query:

```
$db -> query('update notes set body = :body where id = :id', [
    'id' => $_POST['id'],
    'body' => $_POST['body']
]);
```

---

## Sessions

PHP sessions represent a period that a user interacts with your website, maintaining state across multiple requests. Each user’s session is unique, allowing you to store data specific to that user temporarily.

Before interacting with session data, you must start the session early in your application, typically at the beginning of `index.php`:

`session_start();`

This initializes the session and makes the `$_SESSION` superglobal available.

You can store data in the session like an associative array:

`$_SESSION['name'] = 'Jeffrey';`

Session data is not permanent. It typically persists until the browser is closed or the session expires based on server settings. If you close the browser and reopen it, the session data will be lost unless you have configured persistent sessions.

Server-Side-Session:
- Session data is stored in files on the server, usually in a temporary directory

Client-Side-Session:
- A cookie named `PHPSESSID` stores the session ID, which links the browser to the server-side session data

If the cookie is deleted, the server will create a new session file for the user.

You can inspect session cookies in your browser’s developer tools under the Storage or Cookies tab. The session files on the server can be found in the directory specified by the `session.save_path` configuration.

Since users may not always have session data set (e.g., first visit or after clearing cookies), always check if session keys exist before using them, providing defaults if necessary.

## User Registration

For the user registration I created it's own Controller and View Pages:

`/controllers/registration/create.php` : creates the form page

`/controllers/registration/store.php` : handles the user input and logic to validate, store the data in the database and session and redirect to the home page

Inside the `views/partials/nav.php` I'm checking through the `$_SESSION` if there is already a logged in user to either display a user icon or the link for the registration page:

```
<?php if ($_SESSION['user'] ?? false) : ?>
    <button type="button" class="flex 
    </button>
<?php else : ?>
    <a href="/register" class="text-white">Register</a>
<?php endif; ?>
```

## Middleware

A middleware allows you to restrict access to certain routes based on user authentication status or other conditions.

Middleware acts as a bridge that inspects requests before they reach your application’s core logic. It can authorize users, check permissions, or perform other pre-processing.

### Adding Middleware to Routes

You can extend your router to allow chaining an `only()` method after defining a route, specifying middleware like guest or auth:

```
$router->get('/register', 'controllers/registration/create.php')->only('guest');
$router->get('/notes', 'controllers/notes/index.php')->only('auth');
```

The `only()` method associates middleware with the most recently added route.

### Implementing Middleware Handling


1. Store middleware keys on routes.
2. When routing, check if a route has middleware.
3. If so, instantiate the corresponding middleware class and call its handle() method.
4. Middleware classes implement a handle() method that decides whether to allow the request or redirect/abort.

Use a map to associate middleware keys with their classes:

```
class Middleware
{
    public const MAP = [
        'guest' => \core\middleware\Guest::class,
        'auth' => \core\middleware\Auth::class,
    ];

    public static function resolve(string $key)
    {
        if (!$key) {
            return null;
        }

        if (!isset(self::MAP[$key])) {
            throw new Exception("No matching middleware found for key '{$key}'.");
        }

        $class = self::MAP[$key];
        return (new $class())->handle();
    }
}
```

When a route matches, call the middleware resolver:

```
if ($route['middleware']) {
    Middleware::resolve($route['middleware']);
}
```

## Password Hashing

PHP provides a simple and secure way to hash passwords using the password_hash function. When inserting a new user, wrap the password with this function:

`$hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);`

## Loggin in 

After creating the necessary routes and controller for a Login its important that the  `session/store.php` controller does the following steps:

1. Validate the email and password inputs.
2. Query the database for a user with the provided email.
3. If no user is found, return the login view with an error.
4. If a user is found, verify the password using `password_verify`
5. If the password is incorrect, return the login view with an error.
6. If the password is correct, log the user in by storing their email in the session.
7. Redirect the user to the homepage or another protected page.

The `password_verify` function checks if the provided password string matches the stored hash from the database.

After a succesful Login verification it makes sense to store necessary information (like email) in the session global:

```
function login(array $user)
{
    $_SESSION['user'] = [
        'email' => $user['email'],
    ];
}
```

## Logging out 

To log a user out you need to destroy every information about the session. This includes:

1. emptying the session global array:
   
   `$_SESSION = [];`

2. destroying the session:

    `session_destroy();`

3. replace the stored cookie in the browser:

    ```
    $params = session_get_cookie_params();
    setcookie('PHPSESSIP', '', time() - 3600, $params['patch'], $params['domain']);
    ```

## Refactoring

Refactoring is a crucial part of programming that helps improve clarity, readability, and maintainability of your code. Initially, the focus is on making things work, but as you progress, you want your code to be understandable and reusable.

For that you can extract logic like the validation for the login into a dedicated class.

Since the `Core` directory is meant for reusable infrastructure code, and the `Http` directory is the entry point for application-specific code, it makes sense to place the LoginForm class inside an `Http/Forms` directory.

## PRG Pattern

With the PRG pattern you can avoud resubmission issues:

1. POST: User submits the form.
2. Redirect: Server responds with a redirect to a GET route.
3. GET: Browser requests the redirected page, avoiding resubmission issues.

Since a redirect initiates a new request, you can't pass errors directly. Instead, flash errors to the session—store them temporarily so they can be retrieved on the next request.

To implement flashing of errors, you need to store them in a special session key. After redirect, retrieve and display errors, then clear them from the session.

To help manage the session I implemented a `Session` class with static methods:


- `put($key, $value)`: Store data in session.
- `get($key, $default = null)`: Retrieve data from session.
- `has($key)`: Check if key exists.
- `flash($key, $value)`: Store data for one request.
- `unflash()`: Clear flashed data.
- `flush()`: Clear entire session.
- `destroy()`: Destroy session and delete cookie.

In the controller you should flash validation errors before redirecting. Then you should retrieve flashed errors in the view. After you displayed them you should unflash them. 

## Dedicated Exception Handling 

To make code easier to maintain and extend it's useful to have a clear separation of concerns for validation, authentication and response handling. 

A centralized error handling with exceptions reduces duplication and improves readability. 

In this example I split up the login controller that previously mixed validation, authentication, error handling and view rendering into separate classes. 

`LoginForm.php`: handles validations of login inputs and stores validation errors
`Authenticator.php`: handles user authentication including verifying credentials and logging users in 
`ValidationException.php`: throws the errors

## Composer

Composer is a dependency manager for PHP that allows you to easily install and manage PHP packages—collections of reusable code that you can include in your projects.

### Installing Composer

To globally install composer use:

```
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php -r "if (hash_file('sha384', 'composer-setup.php') === 'dac665fdc30fdd8ec78b38b9800061b4150413ff2e3b6f88543c636f7cd84f6db9189d43a81e5503cda447da73c7e5b6') { echo 'Installer verified'.PHP_EOL; } else { echo 'Installer corrupt'.PHP_EOL; unlink('composer-setup.php'); exit(1); }"
php composer-setup.php
php -r "unlink('composer-setup.php');"
```

Most likely, you want to put the composer.phar into a directory on your PATH, so you can simply call composer from any directory (Global install), using for example:

```
sudo mv composer.phar /usr/local/bin/composer
```

### Initializing Composer in Project

`composer init` creates a `composer.json` file in the project directory. This file manages the projects dependencies and autoloading configuration.

### Using Composers Autoloader

Composer provides a powerful PSR-4 autoloader that automatically loads classes based on their namespace and directory structure. It follows a standard convention (PSR-4) for organizing code. With that you don't have the need to manually require class files. 

To use it define your namespaces and their corresponding directories in `composer.json` under the `autoload` section:

```
"autoload": {
    "psr-4": {
        "Core\\": "core/",
        "Http\\": "http/"
    }
}
```

Run `composer dump-autoload` to generate the autoload files.

Require Composer’s autoload file in your `index.php` or entry point:

`require __DIR__ . '/../vendor/autoload.php';`

## Testing

Types of Tests

`Unit Tests`: Focus on small, isolated parts of your code like a single class or function.

`Feature Tests`: Cover broader application features, simulating user interactions and workflows.

### Using Pest for Testing

To install Pest in your project run:

`composer require pestphp/pest`

`./vendor/bin/pest --init`

And add the `tests` directory to the autoloader in the `composer.json`:

```
"autoload": {
        "psr-4": {
            "Core\\": "Core/",
            "Http\\": "Http/",
            "Tests\\": "tests/"
        }
    },
```

Tests typically follow three steps:

1. `Arrange`: Set up the environment or objects.
2. `Act`: Perform the action you want to test.
3. `Assert`: Verify the outcome matches expectations.

Some developers write tests before implementation (TDD), while others write tests after. Both approaches are valid; choose what works best for you.

To run the test use the command:

`vendor/bin/pest`

Too catch avoid mistakes you should test the positive and negative output of you code. So if you expect an output you should also test for what you don't expect.


