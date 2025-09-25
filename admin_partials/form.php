<?php

require './partials/connection.php';


// Directory where I store the pics
$directory = 'assets/blog_pictures/';

// Handle the pic upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $fileTmpName = $_FILES['image']['tmp_name'];
    $fileName = $_FILES['image']['name'];
    $fileSize = $_FILES['image']['size'];

    // Define the maximum file size allowed (64MB in bytes)
    $maxFileSize = 64 * 1024 * 1024; // 64MB

    // Check if the file size exceeds the maximum allowed
    if ($fileSize > $maxFileSize) {
        echo "<script>alert('The uploaded file is too large. Please upload a file smaller than 64 MB.');</script>";
    } else {
        // Ensure the upload directory exists
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true); // Create directory if not exists
        }

        // Create a unique file name using timestamp and original file extension
        $timestamp = time(); // Current timestamp
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = $timestamp . '.' . $fileExtension; // E.g., 1648567890.jpg

        // The file path without directory exposure (only the file name will be stored in the DB)
        $filePath = 'blog_pictures/' . $newFileName; // Store relative path without the directory

        // Move the uploaded file to the target directory
        if (move_uploaded_file($fileTmpName, $directory . $newFileName)) {
            // Now insert the file path into the database
            $blogTitle = $_POST['blog_title']; // Get the blog title from the form
            $blogContent = $_POST['blog_content']; // Get the blog content from the form

            // Prepare the SQL query to prevent SQL injection
            $sql = "INSERT INTO blogs (blog_title, blog_content, img, created_at, updated_at) 
                    VALUES (?, ?, ?, NOW(), NOW())";

            // Prepare the statement
            if ($stmt = $conn->prepare($sql)) {
                // Bind parameters (s = string)
                $stmt->bind_param('sss', $blogTitle, $blogContent, $filePath);

                // Execute the query
                if ($stmt->execute()) {
                    // After successful insertion, redirect to the blogs page (or any page you choose)
                    header("Location: blog_index.php"); // Redirect to the blogs page
                    exit(); // Don't forget to call exit() after the header redirect
                } else {
                    echo "<script>alert('Failed to save blog post.');</script>";
                }

                // Close the prepared statement
                $stmt->close();
            } else {
                echo "<script>alert('Error in preparing the query.');</script>";
            }
        } else {
            echo "<script>alert('Failed to upload the file.');</script>";
        }
    }
}

?>


<head>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<div class="container">
    <div class="d-flex justify-end align-items-center">
    <a role="button" href="logout.php" class="btn btn-danger btn-sm">Logout</a>
    </div>
    <h1>Post a blog...</h1>
    <p class="text-center">
        <a href="../admin_dashboard.php">Admin Dashboard</a>
    </p>
    <!-- Blog Form -->
    <div class="form-container">
        <form id="blogForm" enctype="multipart/form-data" action="" method="POST">
            <!-- Title Input -->
            <div class="form-group">
                <label for="title">Blog Title:</label>
                <input type="text" id="title" name="blog_title" placeholder="Blog title..." required>
            </div>

            <!-- Content Input -->
            <div class="form-group">
                <label for="content">Blog Content:</label>
                <textarea id="content" name="blog_content" rows="5" placeholder="Blog Content..." required></textarea>
            </div>

            <!-- Image Upload -->
            <div class="form-group">
                <label for="image">Upload Image:</label>
                <input type="file" id="image" name="image" accept="image/*" onchange="previewImage(event)" required>
                <div class="image-preview" id="imagePreview">
                    <p>Image preview will appear here</p>
                </div>
            </div>

            <!-- Submit Button -->
            <div class="form-group">
                <button type="submit" name="btn_submit">Post Blog</button>

            </div>


        </form>
    </div>
</div>