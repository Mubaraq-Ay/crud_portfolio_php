<?php
require '../partials/connection.php';  

// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Initialize error messages
  $errors = [];

  // Sanitize and validate form input
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';

  // Basic validation
  if (empty($email)) {
    $errors[] = 'Email address is required.';
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Invalid email format.';
  }

  if (empty($password)) {
    $errors[] = 'Password is required.';
  }

  // If no validation errors, proceed with authentication
  if (empty($errors)) {
    // Prepare the SQL query to check if the user exists
    $sql = "SELECT * FROM admin WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $email); // Bind the email to the query

    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user with the given email exists
    if ($result->num_rows > 0) {
      // User exists, now check password
      $user = $result->fetch_assoc();

      // Use password_verify to check the hash
      if (password_verify($password, $user['password'])) {
        // Correct password, proceed with the login (or session start)
        session_start();
        $_SESSION['user_id'] = $user['admin_id'];
        $_SESSION['user_email'] = $user['email'];

        // Redirect to the admin page
        echo "<script>alert('Login successful!'); window.location.href='../admin.php';</script>";
        exit(); // Make sure no further code is executed after the redirect
      } else {
        $errors[] = 'Invalid password.';
      }
    } else {
      $errors[] = 'No user found with this email address!.';
    }

    // Close the statement
    $stmt->close();
  }

  // Display validation errors if any
  if (!empty($errors)) {
    foreach ($errors as $error) {
      echo "<script>alert('$error');</script>";
    }
  }
}
?>

<!-- HTML Form for Login -->
<!doctype html>
<html>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://cdn.tailwindcss.com"></script>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">


</head>

<body>


  <div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h1 class="display-1 text-center mt-0">Login</h1>

      <h2 class="mt-10 text-center text-2xl/9 font-bold tracking-tight text-gray-900">Restricted Access, Only Admins are allowed!!</h2>
    </div>

    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="" method="POST">

        <div>
          <label for="email" class="block text-sm/6 font-medium text-gray-900">Email address</label>
          <div class="mt-2">
            <input type="email" name="email" id="email" autocomplete="email" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
          </div>
        </div>

        <div>
          <label for="password" class="block text-sm/6 font-medium text-gray-900">Password</label>
          <div class="mt-2">
            <input type="password" name="password" id="password" autocomplete="current-password" required class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
          </div>
        </div>

        <div>
          <button type="submit" name="btn_submit" class="flex w-full justify-center rounded-md bg-indigo-600 px-3 py-1.5 text-sm/6 font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600">Sign in</button>
        </div>
      </form>
    </div>
  </div>
</body>

</html>