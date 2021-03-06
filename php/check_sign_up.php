<!-- redirected from sign.php when the user press "sign up"
    Check values of the entered fields and if the customer does
    not already exist. If the registration values are correct,
    creation of an account in customer.xml and redirection to
    sign in page. If an error occurs, the user will be redirected
    to sign up page.
-->


<?php
    session_start();
    if (isset($_POST) && !empty($_POST)) {
        $xml = simplexml_load_file("../data/customers.xml");
        
        // error variable
        $error = false;

        // collection of registration data
        $username = $_POST["username"];
        $email = $_POST["email"];
        $password = $_POST["password"];
        $c_password = $_POST["c_password"];
        $name = $_POST["name"];
        $lastname = $_POST["lastname"];
        $adress = $_POST["adress"];
        $gender = $_POST["genre"];
        $bdate = $_POST["bdate"];
        $id = 0;


        // checks if all fields are filled in
        if ($username === "" || $email === "" || $password === "" || $c_password === "" || $name === "" || $lastname === ""){
            $_SESSION["empty_error"] = "One or more fields are not filled in.";
            $error = true;
        }
        // check if the account already exists
        foreach ($xml->children() as $customer){
            if ((strval($customer->login) === $username ||
            strval($customer->email) === $email)) {
                $_SESSION["already_exist_error"] = "This email or username already exist.";
                $error = true;
            }
            $id = $id +1;
        }
        $id = $id +1;
        // check if the password confirmation is correct
        if ($password !== $c_password){
            $_SESSION['mdp_error'] = "Passwords are not the same.";
            $error = true;
        }
        if ($error === true){
            header("Location: /sign.php?page=signup");
        }
        else {
            // add the new customer to the customer file (XML)
            $xml2 = new DOMDocument();
            // pretty print for XML file
            $xml2->formatOutput = true;
            $xml2->preserveWhiteSpace = false;
            
            $xml2->load("../data/customers.xml");
            $cust = $xml2->firstChild;
            $newcust = $xml2->createElement("customer");
            
            $xmlid = $xml2->createElement("id",$id);
            $xmladmin = $xml2->createElement("admin",'0');
            $xmllogin = $xml2->createElement("login",$username);
            $xmlpassword = $xml2->createElement("password", hash('sha256', $password));
            $xmlfirstname = $xml2->createElement("firstname",$name);
            $xmllastname = $xml2->createElement("lastname",$lastname);
            $xmlgender = $xml2->createElement("gender",$gender);
            $xmlemail = $xml2->createElement("email",$email);
            $xmlbdate = $xml2->createElement("birthdate",$bdate);
            $xmladress = $xml2->createElement("adress",$adress);
            
            $newcust->appendChild($xmlid);
            $newcust->appendChild($xmladmin);
            $newcust->appendChild($xmllogin);
            $newcust->appendChild($xmlpassword);
            $newcust->appendChild($xmlfirstname);
            $newcust->appendChild($xmllastname);
            $newcust->appendChild($xmlgender);
            $newcust->appendChild($xmlemail);
            $newcust->appendChild($xmlbdate);
            $newcust->appendChild($xmladress);
            
            $cust->appendChild($newcust);
            $xml2->save("../data/customers.xml");
            $_SESSION['success_sign_up'] = "Sign up success";
            header("Location: /sign.php?page=signin");
        }
    }
?>