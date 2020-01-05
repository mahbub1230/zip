<?php

Class User
{

    /** get all users */
    public function getAllUsers($tableName)
    {
        try {
            $data = DB::query("SELECT * FROM $tableName");
            if ($data != null) {
                $response['status'] = 200;
                $response['data'] = $data;
            } else {
                $response['status'] = 404;
                $response['data'] = 'user Not Found';
            }
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['data'] = $e->getMessage();
        }
        return $response;
    }

    /** get all accounts */
    public function getAllAccounts($tableName)
    {
        try {
            $data = DB::query("SELECT  users.user_name,users.email,users.monthly_salary, users.monthly_expenses, $tableName.*
								 FROM $tableName left join users on $tableName.user_id = users.user_id");
            if ($data != null) {
                $response['status'] = 200;
                $response['data'] = $data;
            } else {
                $response['status'] = 404;
                $response['data'] = 'Account Not Found';
            }
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['data'] = $e->getMessage();
        }
        return $response;
    }

    /** get a user info by using user id */
    public function getUser($userId, $tableName)
    {
        try {
            $data = DB::query("SELECT * FROM $tableName WHERE user_id=:id", array(':id' => $userId));
            if ($data != null) {
                $response['status'] = 200;
                $response['data'] = $data[0];
            } else {
                $response['status'] = 404;
                $response['data'] = 'user Not Found';
            }
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['data'] = $e->getMessage();
        }
        return $response;
    }

    /** get user's account' info by using account id */
    public function getAccount($accountId, $tableName)
    {
        try {
            $data = DB::query("SELECT  users.user_name,users.email,users.monthly_salary, users.monthly_expenses, $tableName.*
								 FROM $tableName left join users on $tableName.user_id = users.user_id
									WHERE $tableName.account_id=:id", array(':id' => $accountId));
            if ($data != null) {
                $response['status'] = 200;
                $response['data'] = $data[0];
            } else {
                $response['status'] = 404;
                $response['data'] = 'Account Not Found';
            }
        } catch (Exception $e) {
            $response['status'] = 400;
            $response['data'] = $e->getMessage();
        }
        return $response;
    }

    /** insert a new user */
    public function insertUser($post, $userId, $tableName)
    {
        // check input completeness
        if ($post != null && !$userId) {
            extract($post);
            if (!isset($username) || $username == "") {
                $response['status'] = 400;
                $response['data'] = 'User name is empty';
            } else if (!isset($email) || $email == "") {
                $response['status'] = 400;
                $response['data'] = 'User email is empty';
            } else if (!isset($salary) || $salary == "") {
                $response['status'] = 400;
                $response['data'] = 'User salary is empty';
            } else if (!isset($expenses) || $expenses == "") {
                $response['status'] = 400;
                $response['data'] = 'User expense is empty';
            } else if ($salary <= 0) {
                $response['status'] = 400;
                $response['data'] = 'User salary is negative/zero';
            } else if ($expenses <= 0) {
                $response['status'] = 400;
                $response['data'] = 'User expense is negative/zero';
            } else {
                date_default_timezone_set('Australia/Sydney');
                $createDate = date('Y-m-d H:i:s');
                $updateDate = date('Y-m-d H:i:s');
                try {
                    DB::query("INSERT INTO $tableName VALUES(null, :user_name, :email, :monthly_salary, :monthly_expenses,:create_date,:update_date)",
                        array(
                            ':user_name' => $username,
                            ':email' => $email,
                            ':monthly_salary' => $salary,
                            ':monthly_expenses' => $expenses,
                            ':create_date' => $createDate,
                            ":update_date" => $updateDate
                        ));
                    $data = DB::query("SELECT * FROM $tableName WHERE email=:email", array(':email' => $email));
                    $response['status'] = 200;
                    $response['data'] = $data[0];
                } catch (Exception $e) {
                    // Check duplicate email
                    if ($e->getCode() == 23000) {
                        $response['status'] = 201;
                        $response['data'] = "User already exist.";
                    } else {
                        $response['status'] = 400;
                        $response['data'] = $e->getMessage();
                    }
                }

            }
        } else {
            $response['status'] = 400;
            $response['data'] = 'Please enter required value';
        }
        return $response;
    }

    public function createAccount($post, $accountId, $tableName)
    {
        // check input completeness
        if ($post != null && !$accountId) {
            extract($post);
            if (!isset($email) || $email == "") {
                $response['status'] = 400;
                $response['data'] = 'User email is empty';
            } else {
                try {
                    $data = DB::query("SELECT * FROM users WHERE email=:email", array(':email' => $email));
                    if (isset($data[0]['user_id'])) {
                        date_default_timezone_set('Australia/Sydney');
                        $createDate = date('Y-m-d H:i:s');
                        $updateDate = date('Y-m-d H:i:s');
                        $monthlyIncome = $data[0]['monthly_salary'] - $data[0]['monthly_expenses'];
                        /** check user montly income is greater than or equal $1000 */
                        if ($monthlyIncome >= 1000) {
                            DB::query("INSERT INTO $tableName VALUES(null, :user_id, :amount, :create_date,:update_date)",
                                array(
                                    ':user_id' => $data[0]['user_id'],
                                    ':amount' => 1000,
                                    ':create_date' => $createDate,
                                    ":update_date" => $updateDate
                                ));
                            $data = DB::query("SELECT  users.user_name,users.email,users.monthly_salary, users.monthly_expenses, $tableName.*
								 FROM $tableName left join users on $tableName.user_id = users.user_id
									WHERE users.email=:email", array(':email' => $email));
                            $response['status'] = 200;
                            $response['data'] = $data[0];
                        } else {
                            $response['status'] = 202;
                            $response['data'] = 'User monthly income must be greater than equal $1000. ';
                        }
                    } else {
                        $response['status'] = 404;
                        $response['data'] = 'User is not exist.';
                    }
                } catch (Exception $e) {
                    // this user has already account
                    if ($e->getCode() == 23000) {
                        $response['status'] = 201;
                        $response['data'] = "This user account already exist.";
                    } else {
                        $response['status'] = 400;
                        $response['data'] = $e->getMessage();
                    }
                }
            }
        } else {
            $response['status'] = 400;
            $response['data'] = 'Please enter required value';
        }
        return $response;
    }
}

?>