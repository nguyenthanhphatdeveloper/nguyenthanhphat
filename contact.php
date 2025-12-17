<?php
	if (isset($_POST["submit"])) {
		// Lấy và làm sạch dữ liệu từ form
		$name = isset($_POST['name']) ? trim(strip_tags($_POST['name'])) : '';
		$email = isset($_POST['email']) ? trim(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL)) : '';
		$subject = isset($_POST['subject']) ? trim(strip_tags($_POST['subject'])) : '';
		$message = isset($_POST['message']) ? trim(strip_tags($_POST['message'])) : '';
		
		// Validation
		$errors = array();
		
		if (empty($name)) {
			$errors[] = "Tên không được để trống";
		}
		
		if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$errors[] = "Email không hợp lệ";
		}
		
		if (empty($subject)) {
			$errors[] = "Tiêu đề không được để trống";
		}
		
		if (empty($message)) {
			$errors[] = "Nội dung tin nhắn không được để trống";
		}
		
		// Nếu không có lỗi, gửi email
		if (empty($errors)) {
			$to = 'nguyenthanhphatdeveloper@gmail.com';
			
			// Tạo nội dung email
			$email_subject = "Contact Form: " . $subject;
			$email_body = "Bạn đã nhận được tin nhắn từ form liên hệ trên website.\n\n";
			$email_body .= "Thông tin người gửi:\n";
			$email_body .= "Tên: " . $name . "\n";
			$email_body .= "Email: " . $email . "\n";
			$email_body .= "Tiêu đề: " . $subject . "\n\n";
			$email_body .= "Nội dung tin nhắn:\n";
			$email_body .= $message . "\n\n";
			$email_body .= "---\n";
			$email_body .= "Email này được gửi tự động từ form liên hệ trên website.";
			
			// Headers cho email
			$headers = "From: " . $name . " <" . $email . ">\r\n";
			$headers .= "Reply-To: " . $email . "\r\n";
			$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
			$headers .= "X-Mailer: PHP/" . phpversion();
			
			// Gửi email
			if (mail($to, $email_subject, $email_body, $headers)) {
				// Chuyển hướng đến trang cảm ơn
				header("Location: thank-you.html");
				exit();
			} else {
				// Redirect về form với thông báo lỗi
				$error_msg = urlencode("Có lỗi xảy ra khi gửi email. Vui lòng thử lại sau hoặc liên hệ trực tiếp qua email: nguyenthanhphatdeveloper@gmail.com");
				header("Location: index.html?error=" . $error_msg . "#contact");
				exit();
			}
		} else {
			// Redirect về form với thông báo lỗi
			$error_msg = urlencode(implode(" | ", $errors));
			header("Location: index.html?error=" . $error_msg . "#contact");
			exit();
		}
	}
?>