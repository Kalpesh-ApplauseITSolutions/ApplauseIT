<?php
// send_enquiry.php

// Include the configuration file for DB credentials
require 'config.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer files (adjust path if needed)
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize inputs
    $name    = trim($_POST['name']);
    $email   = trim($_POST['email']);
    $service = trim($_POST['service']);
    $message = trim($_POST['message']);

    // Basic validation
    $errors = [];
    if (empty($name))    { $errors[] = "Name is required."; }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "A valid email is required.";
    }
    if (empty($service)) { $errors[] = "Please select a service."; }
    if (empty($message)) { $errors[] = "Message is required."; }

    if (!empty($errors)) {
        // Show a red popup with validation errors
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

    // --- Database Insertion ---
    $mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
    if ($mysqli->connect_error) {
        die("Database connection failed: " . $mysqli->connect_error);
    }

    // Prepare insert statement; adjust table/column names as needed
    $stmt = $mysqli->prepare("INSERT INTO enquiries (name, email, service, message) VALUES (?, ?, ?, ?)");
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }
    $stmt->bind_param("ssss", $name, $email, $service, $message);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();

    // --- Email Sending via PHPMailer ---
    $mail = new PHPMailer(true);
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'kalpeshborse105@gmail.com';
        $mail->Password   = 'zqbgjnzjwjhtpfzp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;  // or 'tls'
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom($email, $name);
        $mail->addAddress('kalpeshborse105@gmail.com');

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Enquiry from Website';
        $mail->Body    = "<p><strong>Name:</strong> $name</p>
                          <p><strong>Email:</strong> $email</p>
                          <p><strong>Service:</strong> $service</p>
                          <p><strong>Message:</strong><br>$message</p>";
        $mail->AltBody = "Name: $name\nEmail: $email\nService: $service\nMessage: $message";

        $mail->send();

        // Show a short green popup for success, then redirect
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
            toastEl.innerText = "Message has been sent successfully. We will contact you shortly.";
            document.body.appendChild(toastEl);
            setTimeout(function() {
              toastEl.remove();
              window.location.href = "index.html";
            }, 3000);
          });
        </script>';
    } catch (Exception $e) {
        // Show a short red popup for mail error
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
            toastEl.innerText = "Message could not be sent. Mailer Error: ' . addslashes($mail->ErrorInfo) . '";
            document.body.appendChild(toastEl);
            setTimeout(function() {
              toastEl.remove();
              window.location.href = "contact.html";
            }, 4000);
          });
        </script>';
    }
} else {
    // Show a short red popup for invalid request
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
