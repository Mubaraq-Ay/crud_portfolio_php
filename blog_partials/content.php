<?php
require './partials/connection.php';

// Fetch blog posts from the database
$sql = "SELECT * FROM blogs ORDER BY created_at DESC"; // Adjust query if needed
$result = $conn->query($sql);
?>

<div class="container px-4 px-lg-5">
    <!-- Display the title once, above the loop -->
    <div class="">
        <h2 class="mb-5 text-center">
            My Blogs
        </h2>
    </div>

    <div class="row gx-4 gx-lg-5 justify-content-center">
        <div class="col-md-10 col-lg-8 col-xl-7">
            <!-- Check if there are any blog posts -->
            <?php if ($result->num_rows > 0) { ?>
                <!-- Loop through each blog post -->
                <?php while ($row = $result->fetch_assoc()) { 
                    $blogTitle = htmlspecialchars($row['blog_title']);
                    $blogContent = htmlspecialchars($row['blog_content']);
                    $createdAt = $row['created_at'];

                    
                    $imageSrc = $row['img']; 
                ?>

                <!-- Post preview -->
                <div class="post-preview d-flex align-items-center mb-4 mt-5">
                    <div class="post-image me-3">
                        <!-- Display the image from either path or BLOB data -->
                        <img src="<?php echo 'assets/' . $imageSrc; ?>" alt="Blog Image" class="img-fluid rounded-3" style="max-width: 120px; max-height: 90px; object-fit: cover;">
                    </div>
                    <div class="post-text">
                        <a href="post.php?blog_id=<?php echo $row['blog_id']; ?>" class="text-decoration-none">
                            <h2 class="post-title h4"><?php echo $blogTitle; ?></h2>
                        </a>
                        <h3 class="post-subtitle text-muted"><?php echo substr($blogContent, 0, 100) . '...'; ?></h3>
                        <p class="post-meta text-muted">
                            Posted on <?php echo date("F j, Y", strtotime($createdAt)); ?>
                        </p>
                    </div>
                </div>
                <hr class="my-4" />
                <?php } ?>
            <?php } else { ?>
                <!-- Message when there are no blogs -->
                <p class="text-center">No blogs yet.</p>
            <?php } ?>
        </div>
    </div>
</div>
