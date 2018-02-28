<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Vietnamese learning</title>
  <link href="../css/services.css" rel="stylesheet">
</head>
<body>
  <div id="wrapper">
  <div id="inputs">
  <div>
  <center><form name="input" action="#" method="post">
  <h1>Order</h1>
  First name: <input type="text" name="firstname"><br/>
  Last name: <input type="text" name="lastname"><br/>
  Email: <input type="email" name="email">
  <br/>
    <p>Courses Option</p>
    <label><input type="radio" name="indoor-outdoor" checked>Basic</label>
    <label><input type="radio" name="indoor-outdoor">Intermediate</label>
    <label><input type="radio" name="indoor-outdoor">Advance</label>
    <p>Comments:</br></p>
    <textarea></textarea>
    <p></p>
  <input type="submit" value="Submit" href="#">
  <?php
    require_once "errors.php";
    require_once "database.php";

    function updateUserInformation($email, $key, $value)
    {
        validate ("email", $email);
        validate ("key", $key);
        validate ("value", $value);

        $db = db_connect();
        if ($db ['error_code'] == E_OK)
        {
            $mysqli = $db['mysqli'];
            
            $query = "select LastLogin from Users where email='$email'";

            $result = $mysqli -> query ($query);

            if (!$result)
            {
                return E_MYSQLIERROR;
            }
            else 
            {
                if ($result -> num_rows == 0)
                {
                    $query = "insert into Users value (null, '$email', '********', '***', '***', '***', null)";
                    if ($mysqli -> query ($query))
                    {
                        $query = "update Users set $key='$value' where email='$email'";
                        if ($mysqli -> query ($query))
                            return E_OK;
                        else 
                            return E_MYSQLIQUERY;
                    }
                    else 
                    {
                        return E_MYSQLIQUERY;
                    }
                }
                else if ($result -> num_rows == 1)
                {
                    $query = "update users set $key='$value' where email='$email'";
                    if ($mysqli -> query ($query))
                    {
                        return E_OK;
                    }
                    else 
                        return E_MYSQLIQUERY;
                }
                else 
                {
                    return E_EMAILNOTUNIQUE;
                }
            }
        }
        else 
            return $db['error_code'];
        return E_FAILED;
    }

    function placeAnOrder($product_code, $email)
    {
        //add order to database
        validate ("email", $email);
        $db = db_connect();
        if ($db['error_code'] == 0)
        {
            $mysqli = $db['mysqli'];
            //verify product_code
            $query = "select * from Products where PRODUCTID=$product_code";
            $result = $mysqli -> query ($query);
            if (!$result)
            {
                return E_MYSQLIQUERY;
            }
            else 
            {
                if ($result -> num_rows == 0)
                {
                    return E_PRODUCTNOTEXISTS;
                }
                else if ($result -> num_rows > 1)
                {
                    return E_PRODUCTNOTUNIQUE;
                }
                else 
                {
                    //product_code ok
                    $query = "insert into Orders value (null, '$email', $product_code, 0, 'No comment')";
                    if ($mysqli -> query ($query))
                    {
                        sendConfirmationEmail ($product_code, $email);
                        return E_OK;
                    }
                    else 
                        return E_MYSQLIQUERY;
                }
            }
        }
        else 
            return $db['error_code'];
        return E_FAILED;
    }

    function sendConfirmationEmail ($product_code, $email)
    {

    }
    ?>
  </form>
  </div>
  </div>

  <div id="right">
    <div id="info">
      <ul>
        <li>Basic course</li>
        <li>Intermediate course</li>
        <li>Advance course</li>
      </ul>
    </div>
  </div>
</body>
</body>
</html>