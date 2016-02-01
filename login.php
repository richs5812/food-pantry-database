<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="db_styles.css">
<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1.0">
<title>Brightmoor Connection Database - login</title>
</head>
<body>

<header>
<img src="images/brightmoor_logo.jpg" width=500px>
<h1>Brightmoor Connection Database</h1>
</header>

<section>
<form id='login' action='login_process.php' method='post'>
<div class="formStyle">
<h2>Login</h2>
<input type='hidden' name='submitted' id='submitted' value='1'/>
 
<label for='username' >User Name*:</label>
<input type='text' name='username' id='username' class="textInput" maxlength="30" required/>
 
<label for='password' >Password*:</label>
<input type='password' name='password' id='password' maxlength="50" required/>
 
<input type='submit' name='Submit' value='Submit' />
 
</form>
</div>
</section>
</body>
</html>