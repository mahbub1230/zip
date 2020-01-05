<?php
require_once "./classes/user.php";
include_once './classes/Database.php';
class UserTest extends \PHPUnit_Framework_TestCase
{
    protected $_user;
    protected $_post;
    protected function userData()
    {
        $email = "test".rand()."@gmail.com";
        $post = array (
            "username" => "Test user",
            "email" => $email,
            "salary" => 5000,
            "expenses" => 2000
            );
    $this->_post = $post;
}
    public function setup()
    {
       $this->_user = new user();
    }

    /** unit test insert new user */

    public function testInsertUser()
    {
        
        $userId =null;
        $tableName = "users";
        $this->userData();
        $response = $this->_user->insertUser($this->_post,$userId,$tableName);
        $this->assertEquals($response['status'],200);

    }

    /** unit test insert existing user */
    
    public function testInsertUserWithExistingEmail()
    {
        
        $userId =null;
        $tableName = "users";
        $this->userData();
        $this->_user->insertUser($this->_post,$userId,$tableName);
        $response=$this->_user->insertUser($this->_post,$userId,$tableName);
        $this->assertEquals($response['status'],201);

    }

    /** Search user by using user Id */
    public function testSearchUser()
    {
        $userId = null;
        $tableName = "users";
        $this->userData();
        $response = $this->_user->insertUser($this->_post,$userId,$tableName);
        $data = $response['data'];
        $userId = $data['user_id'];
        $userInfo = $this->_user->getUser($userId,$tableName); 
        $this->assertTrue($this->arraysAreSimilar($response['data'], $userInfo['data']));
    }

    /** Create new account for the user */
    public function testCreateAccount()
    {
        $accountId = null;
        $userId = null;
        $tableName = "users";
        $this->userData();
        $userInfo = $this->_user->insertUser($this->_post,$userId,$tableName);
        $data = $userInfo['data'];
        $post = array (
            "email" => $data['email']
        );
        $tableName = "accounts";
        $response = $this->_user->createAccount($post,$accountId,$tableName);
        $this->assertEquals($response['status'],200);

    }

    /** try to Create new account for the user which monthly income is less than $1000*/
    public function testCreateAccountWithLess()
    {
        $accountId = null;
        $userId = null;
        $tableName = "users";
        $email = "test".rand()."@gmail.com";
        $post = array (
            "username" => "Test user",
            "email" => $email,
            "salary" => 4000,
            "expenses" => 3500
            );
        $userInfo = $this->_user->insertUser($post,$userId,$tableName);
        $data = $userInfo['data'];
        $accountPost = array (
            "email" => $data['email']
        );
        $tableName = "accounts";
        $response = $this->_user->createAccount($accountPost,$accountId,$tableName);
        $this->assertEquals($response['status'],202);

    }

    /** try to create duplicate account for the user. */
    public function testCreateAccountWithExistingUserAccount()
    {
        $accountId = null;
        $userId = null;
        $tableName = "users";
        $this->userData();
        $userInfo = $this->_user->insertUser($this->_post,$userId,$tableName);
        $data = $userInfo['data'];
        $post = array (
            "email" => $data['email']
        );
        $tableName = "accounts";
        $this->_user->createAccount($post,$accountId,$tableName);
        $response = $this->_user->createAccount($post,$accountId,$tableName);
        $this->assertEquals($response['status'],201);

    }
    
    /** Account search by using account */
    public function testSearchAccount()
    {
        $accountId = null;
        $userId = null;
        $tableName = "users";
        $this->userData();
        $userInfo = $this->_user->insertUser($this->_post,$userId,$tableName);
        $data = $userInfo['data'];
        $post = array (
            "email" => $data['email']
        );
        $tableName = "accounts";
        $account = $this->_user->createAccount($post,$accountId,$tableName);
        $accountInfo = $account['data'];
        $accountId = $accountInfo['account_id'];
        $response = $this->_user->getAccount($accountId,$tableName); 
        $this->assertTrue($this->arraysAreSimilar($account['data'], $response['data']));

    }
    /**
 * Determine if two associative arrays are similar
 *
 * Both arrays must have the same indexes with identical values
 * without respect to key ordering 
 * 
 * @param array $a
 * @param array $b
 * @return bool
 */
  protected function arraysAreSimilar($a, $b) {
    // if the indexes don't match, return immediately
    if (count(array_diff_assoc($a, $b))) {
      return false;
    }
    // we know that the indexes, but maybe not values, match.
    // compare the values between the two arrays
    foreach($a as $k => $v) {
      if ($v !== $b[$k]) {
        return false;
      }
    }
    // we have identical indexes, and no unequal values
    return true;
  }
}