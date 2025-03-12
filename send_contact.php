<?php
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $message = trim($_POST['message']);

    $errors = [];
    if (empty($name))    { $errors[] = "Name is required."; }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($subject)) { $errors[] = "Subject is required."; }
    if (empty($message)) { $errors[] = "Message is required."; }

    if (!empty($errors)) {
        echo '<script>
          document.addEventListener("DOMContentLoaded", function() {
            var toastEl = document.createElement("div");
            toastEl.style.position = "fixed";
            toastEl.style.bottom = "20px";
            toastEl.style.right = "20px";
            toastEl.style.padding = "12px 20px";
            toastEl.style.backgroundColor = "#dc3545";
            toastEl.style.color = "#fff";
            toastEl.style.borderRadius = "4px";
            toastEl.style.zIndex = "9999";
            toastEl.innerText = "'. implode("\\n", $errors) .'";
            document.body.appendChild(toastEl);
            setTimeout(function() {
              toastEl.remove();
              window.location.href = "contact.html";
            }, 3000);
          });
        </script>';
        exit;
    }

    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        die("Database connection failed: " . $mysqli->connect_error);
    }

    $stmt = $mysqli->prepare("INSERT INTO contacts (name, email, subject, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("ssss", $name, $email, $subject, $message);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kalpeshborse105@gmail.com';
        $mail->Password   = 'zqbgjnzjwjhtpfzp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->setFrom($email, $name);
        $mail->addAddress('kalpeshborse105@gmail.com');

        $mail->isHTML(true);
        $mail->Subject = 'New Contact Form Submission: ' . $subject;
        $mail->Body    = "<p><strong>Name:</strong> $name</p>
                          <p><strong>Email:</strong> $email</p>
                          <p><strong>Subject:</strong> $subject</p>
                          <p><strong>Message:</strong><br>$message</p>";
        $mail->AltBody = "Name: $name\nEmail: $email\nSubject: $subject\nMessage: $message";

        $mail->send();

        echo '<script>
          document.addEventListener("DOMContentLoaded", function() {
            var toastEl = document.createElement("div");
            toastEl.style.position = "fixed";
            toastEl.style.bottom = "20px";
            toastEl.style.right = "20px";
            toastEl.style.padding = "12px 20px";
            toastEl.style.backgroundColor = "#28a745";
            toastEl.style.color = "#fff";
            toastEl.style.borderRadius = "4px";
            toastEl.style.zIndex = "9999";
            toastEl.innerText = "Message sent successfully! We\'ll respond shortly.";
            document.body.appendChild(toastEl);
            setTimeout(function() {
              toastEl.remove();
              window.location.href = "contact.html";
            }, 3000);
          });
        </script>';
    } catch (Exception $e) {
        echo '<script>
          document.addEventListener("DOMContentLoaded", function() {
            var toastEl = document.createElement("div");
            toastEl.style.position = "fixed";
            toastEl.style.bottom = "20px";
            toastEl.style.right = "20px";
            toastEl.style.padding = "12px 20px";
            toastEl.style.backgroundColor = "#dc3545";
            toastEl.style.color = "#fff";
            toastEl.style.borderRadius = "4px";
            toastEl.style.zIndex = "9999";
            toastEl.innerText = "Message could not be sent. Error: ' . addslashes($mail->ErrorInfo) . '";
            document.body.appendChild(toastEl);
            setTimeout(function() {
              toastEl.remove();
              window.location.href = "contact.html";
            }, 4000);
          });
        </script>';
    }
} else {
    echo '<script>
      document.addEventListener("DOMContentLoaded", function() {
        var toastEl = document.createElement("div");
        toastEl.style.position = "fixed";
        toastEl.style.bottom = "20px";
        toastEl.style.right = "20px";
        toastEl.style.padding = "12px 20px";
        toastEl.style.backgroundColor = "#dc3545";
        toastEl.style.color = "#fff";
        toastEl.style.borderRadius = "4px";
        toastEl.style.zIndex = "9999";
        toastEl.innerText = "Invalid request.";
        document.body.appendChild(toastEl);
        setTimeout(function() {
          toastEl.remove();
          window.location.href = "contact.html";
        }, 3000);
      });
    </script>';
}
?>