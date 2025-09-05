<?php
require_once 'db.php';
$pdo = getPDO();

// Get student ID from URL
$student_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Fetch student details
$stmt = $pdo->prepare("SELECT * FROM student WHERE student_id = :id");
$stmt->execute([':id' => $student_id]);
$student = $stmt->fetch();

if (!$student) {
    header('Location: index.php');
    exit;
}

// Handle form submission
$message_sent = false;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $message = trim($_POST['message']);
    
    // In a real application, you would send an email here
    // For now, we'll just set a flag that the message was "sent"
    $message_sent = true;
}
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Contact <?php echo e($student['full_name']); ?></title>
  <link rel="stylesheet" href="css/style.css">
  <style>
    .contact-form {
      max-width: 600px;
      margin: 0 auto;
    }
    .form-group {
      margin-bottom: 15px;
    }
    label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
    }
    .input, textarea {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 8px;
    }
    textarea {
      min-height: 120px;
      resize: vertical;
    }
    .success-message {
      background: #e7f9ee;
      color: #066;
      padding: 15px;
      border-radius: 8px;
      margin-bottom: 20px;
      text-align: center;
    }
  </style>
</head>
<body>
  <div class="header">
    <div><a href="index.php" style="color:#fff">Home</a></div>
  </div>

  <div class="container">
    <div class="card contact-form">
      <h2>Contact <?php echo e($student['full_name']); ?></h2>
      
      <?php if ($message_sent): ?>
        <div class="success-message">
          Your message has been sent successfully. <?php echo e($student['full_name']); ?> will contact you soon.
        </div>
        <div style="text-align: center; margin-top: 20px;">
          <a href="profile.php?id=<?php echo $student_id; ?>" class="btn">Back to Profile</a>
        </div>
      <?php else: ?>
        <p>Fill out the form below to contact this student. They will receive your message and contact information.</p>
        
        <form method="post">
          <div class="form-group">
            <label for="name">Your Name</label>
            <input type="text" id="name" name="name" class="input" required>
          </div>
          
          <div class="form-group">
            <label for="email">Your Email</label>
            <input type="email" id="email" name="email" class="input" required>
          </div>
          
          <div class="form-group">
            <label for="phone">Your Phone Number</label>
            <input type="tel" id="phone" name="phone" class="input" required>
          </div>
          
          <div class="form-group">
            <label for="message">Message</label>
            <textarea id="message" name="message" class="input" required placeholder="What would you like to discuss?"></textarea>
          </div>
          
          <button type="submit" class="btn">Send Message</button>
          <a href="profile.php?id=<?php echo $student_id; ?>" style="margin-left: 10px;">Cancel</a>
        </form>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>