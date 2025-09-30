<?php
require 'partials/connection.php';

$sql = "SELECT * FROM blogs ORDER BY created_at DESC";
$result = $conn->query($sql);
?>

<?php 
    require 'admin_partials/header.php';
?>
<body>
    <div class="container py-5">
        <h1 class="mb-4 text-center">Admin Dashboard</h1>

        <!-- Check if there are any blogs -->
        <!-- Check if there are any blogs -->
        <?php if ($result->num_rows > 0) { ?>
            <div class="row">
                <?php while ($row = $result->fetch_assoc()) {
                    $blogId = $row['blog_id'];
                    $blogTitle = htmlspecialchars($row['blog_title']);
                    $blogContent = htmlspecialchars($row['blog_content']); // Get blog content
                    $contentSnippet = substr($blogContent, 0, 150); // Get the first 150 characters of content
                ?>
                    <div class="col-md-6 mb-4">
                        <div class="card shadow-sm">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $blogTitle; ?></h5>
                                <p class="card-text"><?php echo $contentSnippet . '...'; ?></p> <!-- Display part of the content -->
                                <div class="d-flex justify-content-between">
                                    <a href="admin_partials/edit.php?blog_id=<?php echo $blogId; ?>" class="btn btn-secondary btn-sm">Edit</a>
                                    <a href="admin_partials/delete.php?blog_id=<?php echo $blogId; ?>"
                                        class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this post?')">Delete</a>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } else { ?>
            <!-- Message when there are no blogs -->
            <p class="text-center">No blogs yet. Start by adding some!</p>
        <?php } ?>
    </div>


    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>