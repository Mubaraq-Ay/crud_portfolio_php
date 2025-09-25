<?php
require '../partials/connection.php';

// Check if blog_id is set in the URL
if (isset($_GET['blog_id'])) {
    $blogId = $_GET['blog_id'];

    // Fetch the blog post details from the database
    $sql = "SELECT * FROM blogs WHERE blog_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $blogId);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Blog ID is missing.";
    exit();
}

// Handle form submission for updating the blog post
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $blogId = $_POST['blog_id'];
    $blogTitle = $_POST['blog_title'];
    $blogContent = $_POST['blog_content'];

    // Update the post in the database
    $sql = "UPDATE blogs SET blog_title = ?, blog_content = ?, updated_at = NOW() WHERE blog_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssi', $blogTitle, $blogContent, $blogId);

    if ($stmt->execute()) {
        echo "<script>alert('Post updated successfully!');</script>";
        header("Location: ../admin_dashboard.php"); // Redirect to the dashboard
        exit();
    } else {
        echo "<script>alert('Failed to update the post.');</script>";
    }

    $stmt->close();
}
?>

<!-- HTML and Modal for Editing Blog Post -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Blog Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Modal for Editing Blog Post -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Blog Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="edit.php?blog_id=<?php echo $blog['blog_id']; ?>" method="POST">
                        <input type="hidden" name="blog_id" value="<?php echo $blog['blog_id']; ?>">
                        <div class="mb-3">
                            <label for="blog_title" class="form-label">Blog Title</label>
                            <input type="text" name="blog_title" class="form-control" id="blog_title" value="<?php echo htmlspecialchars($blog['blog_title']); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label for="blog_content" class="form-label">Blog Content</label>
                            <textarea name="blog_content" class="form-control" id="blog_content" rows="5" required><?php echo htmlspecialchars($blog['blog_content']); ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Changes</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Automatically trigger the modal when the page loads -->
    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();  // Show the modal
        });
    </script>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
