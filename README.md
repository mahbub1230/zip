# zip
* Create sub directory zip and pull all the code to this direcory.
* Create database zipmoney and import zipmoney.sql from database floder
* modify the classes/Database.php as per database cradentials 
'''
 private static function connect()
    {
        $dbhost = "localhost";
        $dbuser = "root";
        $dbpass = "";
        $dbname = "zipmoney";
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $pdo;
    }
 '''
 
 ##api url
 * get users list 'http://localhost/zip/api/users' method is get
 * get user by user id 'http://localhost/zip/api/users/1' method is get
 * create user 'http://localhost/zip/api/users' .method is post. Form data are username,email,salary,expenses
 * get accounts list 'http://localhost/zip/api/accounts' method is get
 * get user account by account id 'http://localhost/zip/api/accounts/1' method is get
 * create account 'http://localhost/zip/api/accounts' .method is post. Form data are email
 
### You can find postman screenshot api
