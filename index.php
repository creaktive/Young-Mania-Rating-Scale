<?php
session_start();
session_unset(); // Clear any previous session data on starting
session_destroy();
session_start(); // Start a new session

// Initialize the answers array in the session
$_SESSION['answers'] = [];
$_SESSION['current_question'] = 1;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Young Mania Rating Scale</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; }
        h1, h2, h3 { color: #333; }
        p { line-height: 1.6; }
        .start-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .start-button:hover { background-color: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Welcome to the Young Mania Rating Scale</h1>
        <h2>Young Mania Rating Scale (YMRS)</h2>
        <h3>OVERVIEW</h3>
        <p>The Young Mania Rating Scale (YMRS) is one of the most frequently utilized rating scales to assess manic symptoms. The
        scale has 11 items and is based on the patient's subjective report of his or her clinical condition over the previous 48
        hours. Additional information is based upon clinical observations made during the course of the clinical interview. The
        items are selected based upon published descriptions of the core symptoms of mania. The YMRS follows the style of the
        Hamilton Rating Scale for Depression (HAM-D) with each item given a severity rating. There are four items that are
        graded on a 0 to 8 scale (irritability, speech, thought content, and disruptive/aggressive behavior), while the remaining
        seven items are graded on a 0 to 4 scale. These four items are given twice the weight of the others to compensate for poor
        cooperation from severely ill patients. There are well described anchor points for each grade of severity. The authors
        encourage the use of whole or half point ratings once experience with the scale is acquired. Typical YMRS baseline scores
        can vary a lot. They depend on the patients' clinical features such as mania (YMRS = 12), depression (YMRS = 3), or
        euthymia (YMRS = 2). Sometimes a clinical study entry requirement of YMRS > 20 generates a mean YMRS baseline of
        about 30. Strengths of the YMRS include its brevity, widely accepted use, and ease of administration. The usefulness of the
        scale is limited in populations with diagnoses other than mania.</p>

        <p>The YMRS is a rating scale used to evaluate manic symptoms at baseline and over time in individuals with mania.</p>

        <p>The scale is generally done by a clinician or other trained rater with expertise with manic patients and takes 15-30
        minutes to complete.</p>
        <a href="question.php" class="start-button">Start Questionnaire</a>
    </div>
</body>
</html>
