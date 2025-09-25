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

    // Check if any record was found
    if ($result->num_rows === 0) {
        echo "Blog post not found!";
        exit();
    }

    $blog = $result->fetch_assoc();
    $stmt->close();
} else {
    echo "Blog ID is missing.";
    exit();
}

// Handle form submission for deleting the blog post
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] == 'delete') {
    $blogId = $_POST['blog_id'];

    // Delete the blog post from the database
    $sql = "DELETE FROM blogs WHERE blog_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $blogId);

    if ($stmt->execute()) {
        // Redirect to the dashboard after successful deletion
        header("Location: ../admin_dashboard.php");
        exit();
    } else {
        echo "<script>alert('Failed to delete the post.');</script>";
    }

    $stmt->close();
}
?>

<!-- HTML and Modal for Confirming Deletion -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Blog Post</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Delete Blog Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete the blog post titled "<?php echo htmlspecialchars($blog['blog_title']); ?>"?
                </div>
                <div class="modal-footer">
                    <!-- Deletion Form inside the modal -->
                    <form action="delete.php?blog_id=<?php echo $blog['blog_id']; ?>" method="POST">
                        <input type="hidden" name="blog_id" value="<?php echo $blog['blog_id']; ?>">
                        <input type="hidden" name="action" value="delete">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <!-- Delete button inside the modal form -->
                        <button type="submit" class="btn btn-danger">Delete Post</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS and dependencies -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>

    <!-- JavaScript to automatically show the modal when the page loads -->
    <script>
        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function () {
            var modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();  // Show the delete confirmation modal
        });
    </script>
</body>
</html>
