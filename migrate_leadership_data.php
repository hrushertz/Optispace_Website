<?php
require_once 'database/db_config.php';
$conn = getDBConnection();

// Check if table is empty
$result = $conn->query("SELECT COUNT(*) as cnt FROM leadership");
$row = $result->fetch_assoc();

if ($row['cnt'] > 0) {
    die("Table 'leadership' already has data. Migration skipped.");
}

// Data from leadership.php
$name = "Minish Umrani";
$designation = "Founder & CEO, Solutions KMS";
$sub_designation = "Director, Solutions OptiSpace";
$location = "Pune, Maharashtra, India";
$quote = "\"The extraordinary begins where the ordinary ends.\"";
$philosophy_content = "<p>Minish brings a unique combination of lean manufacturing expertise and architectural thinking to every project. <strong>His belief that \"the extraordinary begins where the ordinary ends\" drives him to push beyond conventional factory architecture</strong> into the realm of Lean Factory Building (LFB).</p>
<p>With deep knowledge of Lean, Six Sigma, and Theory of Constraints, combined with practical experience across 75+ industrial segments, Minish ensures that every factory design is not just functional, but truly optimized for operational excellence.</p>
<p>He is known for his hands-on approach, attention to detail, and unwavering commitment to delivering measurable results for clients. His leadership has established OptiSpace as a thought leader in the inside-out design philosophy.</p>";

$education_items = json_encode([
    "DBM, Lean & Six Sigma Black Belt",
    "Anant English School, Satara (1975-1980)"
]);

$experience_items = json_encode([
    "Founder & CEO, Solutions Kaizen Management Systems",
    "Director, Solutions OptiSpace",
    "Crane Process Flow Technologies Ltd",
    "Acumen and Grin Solutions Pvt. Ltd."
]);

$recognition_items = json_encode([
    "250+ businesses transformed",
    "75+ industrial segments served",
    "20+ years of excellence",
    "Pioneer of Inside-Out Design"
]);

$skills = "Process Flow, Six Sigma, Lean Manufacturing, Value Stream Mapping, Factory Design, Theory of Constraints, TPM, Logistics";

// Since we don't have the image file path yet (it's SVG in the static code), we'll leave it empty or use a placeholder if available.
// The SVG in the static file was just an icon.
// The user said "the image will be 400x400px you can resize it as per requirement".
// I'll leave image_path null for now, the user can upload it via admin.
$image_path = "";

$stmt = $conn->prepare("INSERT INTO leadership (name, designation, sub_designation, location, image_path, quote, education_items, experience_items, recognition_items, skills, philosophy_content, sort_order, is_active) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1, 1)");
$stmt->bind_param("sssssssssss", $name, $designation, $sub_designation, $location, $image_path, $quote, $education_items, $experience_items, $recognition_items, $skills, $philosophy_content);

if ($stmt->execute()) {
    echo "Successfully migrated Minish Umrani's profile to the database.";
} else {
    echo "Error migrating data: " . $stmt->error;
}
$stmt->close();
$conn->close();
?>