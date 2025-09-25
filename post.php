<?php
require 'partials/connection.php';

// Get the blog ID from the URL query string
if (isset($_GET['blog_id'])) {
    $blogId = $_GET['blog_id'];
} else {
    // Redirect if blog_id is not present
    header('Location: index.php');
    exit;
}

// Fetch the blog post by ID
$sql = "SELECT * FROM blogs WHERE blog_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $blogId); // 'i' for integer
$stmt->execute();
$result = $stmt->get_result();

// Check if the post exists
if ($result->num_rows > 0) {
    // Fetch the blog details
    $blog = $result->fetch_assoc();
    $blogTitle = $blog['blog_title'];
    $blogContent = $blog['blog_content'];
    $blogImage = $blog['img']; // The filename stored in your database
    $createdAt = $blog['created_at'];
} else {
    // No blog found with that ID
    echo "<script>alert('Blog post not found.'); window.location.href='index.php';</script>";
    exit;
}

require 'blog_partials/header.php';
?>


<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>


    <!-- Page Header-->
    <header class="masthead" style="background-image: url('assets/img/post-bg.jpg')">
        <div class="container position-relative px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <div class="post-heading">
                        <h1><?php echo htmlspecialchars($blogTitle); ?></h1>
                        <span class="meta">
                            Posted on <?php echo date("F j, Y", strtotime($createdAt)); ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Post Content-->
    <article class="mb-4">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-md-10 col-lg-8 col-xl-7">
                    <!-- Display the image from the relative path -->
                    <!-- <img src="<?php echo 'assets/' . $imageSrc; ?>" alt="Blog Image" class="img-fluid rounded-3" style="max-width: 120px; max-height: 80px; object-fit: cover;"> -->

                    <!-- Display the blog content -->
                    <p><?php echo nl2br(htmlspecialchars($blogContent)); ?></p>
                </div>
            </div>
        </div>
    </article>

    <a href="./" role="button" class="d-flex lead">Back</a>


    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
</body>

</html>