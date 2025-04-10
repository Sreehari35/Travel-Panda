<?php
session_start();
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $old_password = $_POST['oldpassword'];
    $new_password = $_POST['newpassword'];

    // Check if email exists and old password matches
    $query = "SELECT * FROM users WHERE email='$email' AND password='$old_password'";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Update new password in database without hashing
        $update_query = "UPDATE users SET password='$new_password' WHERE email='$email'";
        if ($conn->query($update_query) === TRUE) {
            echo "<script>alert('Password updated successfully!'); window.location.href='login.html';</script>";
        } else {
            echo "<script>alert('Error updating password.'); window.location.href='forgot_password.php';</script>";
        }
    } else {
        echo "<script>alert('Invalid email or old password!'); window.location.href='forgot_password.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginst.css">
    <title>Forgot Password</title>
</head>
<body>
    <div class="login-box">
        <div class="login-header">
            <header>Forgot password</header>
        </div>
        <form action="forgot.php" method="POST">
            <div class="input-box">
                <input type="text" name="email" class="input-field" placeholder="Email" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="oldpassword" class="input-field" placeholder="Old Password" autocomplete="off" required>
            </div>
            <div class="input-box">
                <input type="password" name="newpassword" class="input-field" placeholder="New Password" autocomplete="off" required>
            </div>
            <div class="input-submit">
                <button type="submit">RESET PASSWORD</button>
            </div>
        </form>

        <div class="sign-up-link">
            <p>Don't have an account? <a href="register.html">Sign Up</a></p>
        </div>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const speechSynthesisInstance = window.speechSynthesis;

    if (!speechSynthesisInstance) {
        console.warn("Speech Synthesis not supported in this browser.");
        return;
    }

    let currentUtterance = null;
    let isPlaying = true;
    let availableVoices = [];
    let currentLanguage = 'en';

    function speakText(text, langCode = currentLanguage) {
        if (!text || !isPlaying) return;

        text = text.replace(/\bUS\b/g, "us").replace(/\bHTML\b/g, "H T M L");
        speechSynthesisInstance.cancel();

        currentUtterance = new SpeechSynthesisUtterance(text);
        currentUtterance.lang = langCode;

        const matchedVoice = availableVoices.find(v => v.lang.toLowerCase().includes(langCode.toLowerCase()));
        currentUtterance.voice = matchedVoice || availableVoices[0];

        setTimeout(() => {
            speechSynthesisInstance.speak(currentUtterance);
        }, 100);
    }

    function stopSpeaking() {
        if (speechSynthesisInstance.speaking || speechSynthesisInstance.pending) {
            speechSynthesisInstance.cancel();
        }
    }

    function addListenersToElement(element) {
        const text = element.innerText.trim() ||
            element.getAttribute("aria-label") ||
            element.placeholder ||
            element.alt ||
            element.getAttribute("title") || "";
        if (text) {
            element.addEventListener("mouseenter", () => {
                if (isPlaying) speakText(text);
            });
            element.addEventListener("mouseleave", stopSpeaking);
            element.addEventListener("focus", () => {
                if (isPlaying) speakText(text);
            });
            element.addEventListener("blur", stopSpeaking);
            element.addEventListener("touchstart", () => {
                if (isPlaying) speakText(text);
            });
            element.addEventListener("touchend", stopSpeaking);
        }
    }

    const allTextElements = document.querySelectorAll("p, h1, h2, h3, h4, h5, h6, li, span, button, input[aria-label], input[placeholder], img[alt], div[aria-label]");
    allTextElements.forEach(addListenersToElement);

    const pageTitle = document.title;
    if (isPlaying && pageTitle) {
        speakText("Page Title: " + pageTitle);
    }

    const readAllButton = document.createElement("button");
    readAllButton.innerText = "Read All Content";
    readAllButton.title = "Button: Read all content on the page";
    readAllButton.style.position = "fixed";
    readAllButton.style.bottom = "10px";
    readAllButton.style.right = "10px";
    readAllButton.style.zIndex = 1000;
    document.body.appendChild(readAllButton);
    addListenersToElement(readAllButton);

    readAllButton.addEventListener("click", function () {
        const allContent = Array.from(allTextElements)
            .map(element => element.innerText.trim())
            .filter(text => text.length > 0)
            .join(". ");
        speakText(allContent);
    });

    const startStopButton = document.createElement("button");
    startStopButton.innerText = "Start/Stop Playback";
    startStopButton.title = "Button: Start or stop playback";
    startStopButton.style.position = "fixed";
    startStopButton.style.top = "10px";
    startStopButton.style.right = "10px";
    startStopButton.style.zIndex = 1000;
    document.body.appendChild(startStopButton);
    addListenersToElement(startStopButton);

    startStopButton.addEventListener("click", function () {
        isPlaying = !isPlaying;
        stopSpeaking();

        if (isPlaying) {
            speakText("Playback started.");
        } else {
            speakText("Playback stopped.");
        }
    });

    const languageSelector = document.createElement("select");
    languageSelector.title = "Dropdown: Select language";
    languageSelector.style.position = "fixed";
    languageSelector.style.top = "50px";
    languageSelector.style.right = "10px";
    languageSelector.style.zIndex = 1000;
    document.body.appendChild(languageSelector);
    addListenersToElement(languageSelector);

    const languages = {
        en: "English",
        es: "Spanish",
        fr: "French"
    };

    Object.entries(languages).forEach(([code, name]) => {
        const option = document.createElement("option");
        option.value = code;
        option.innerText = name;
        languageSelector.appendChild(option);
    });

    languageSelector.addEventListener("change", function () {
        currentLanguage = this.value;
        speakText(`Language changed to ${languages[this.value]}`, currentLanguage);
    });

    window.speechSynthesis.onvoiceschanged = () => {
        availableVoices = speechSynthesisInstance.getVoices();
    };

    availableVoices = speechSynthesisInstance.getVoices();
});
</script>

</body>
</html>
