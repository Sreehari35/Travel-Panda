<?php
include 'database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $gender = $_POST['gender'];
    $email = $_POST['email'];
    $suggestion = $_POST['suggestion'];

    $query = "INSERT INTO feedback (fname, lname, gender, email, suggestion) VALUES ('$fname', '$lname', '$gender', '$email', '$suggestion')";

    if ($conn->query($query) === TRUE) {
        echo "<script>alert('Feedback submitted successfully!'); window.location.href='index.php';</script>";
    } else {
        echo "<script>alert('Error submitting feedback.'); window.location.href='index.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Us</title>
    <link rel="stylesheet" href="contacts.css">
</head>
<body>
    <div class="container">
        <h1>Get In Touch With Us</h1>
        <form action="contacts.php" method="POST">
            <label for="fname">First Name:</label>
            <input type="text" name="fname" id="fname" placeholder="Enter your first name" required><br><br>

            <label for="lname">Last Name:</label>
            <input type="text" name="lname" id="lname" placeholder="Enter your last name" required><br><br>

            <label for="gender">Gender:</label>
            <div class="radio-group">
                <label>
                    <input type="radio" name="gender" value="MALE" required> Male
                </label>
                <label>
                    <input type="radio" name="gender" value="FEMALE" required> Female
                </label>
            </div><br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" placeholder="Enter your email address" required><br><br>

            <label for="suggestion">Suggestions:</label>
            <textarea name="suggestion" id="suggestion" rows="5" cols="40" placeholder="Share your thoughtful suggestions" required></textarea><br><br>

            <input type="submit" value="Submit">
        </form>

        <footer>
            <hr>
            <h4>Contact Information</h4>
            <p>
                Address: Pillai College of Engineering, Panvel, Navi Mumbai<br>
                Email: <a href="mailto:pillai@nodata.com">pillai@nodata.com</a><br>
                Phone: +91 9876543201
            </p>
        </footer>
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
