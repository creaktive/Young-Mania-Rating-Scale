<?php
session_start();

$total_score = 0;
if (isset($_SESSION['answers'])) {
    foreach ($_SESSION['answers'] as $q_num => $score) {
        $total_score += $score;
    }
}

// Clear the session after displaying results
session_unset();
session_destroy();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>YMRS Results</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .container { max-width: 800px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 8px; text-align: center; }
        h1 { color: #333; }
        .score-display { font-size: 2.5em; color: #007bff; margin: 30px 0; font-weight: bold; }
        .interpretation { margin-top: 20px; text-align: left; }
        .interpretation p { margin-bottom: 10px; }
        .start-over-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #6c757d;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 30px;
        }
        .start-over-button:hover { background-color: #5a6268; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Young Mania Rating Scale Results</h1>
        <p>Thank you for completing the questionnaire.</p>
        <div class="score-display">Your Total YMRS Score: <?php echo $total_score; ?></div>

        <div class="interpretation">
            <h2>Interpretation Guidelines (General, for informational purposes only):</h2>
            <p><strong>0-5:</strong> Remission/Normal mood</p>
            <p><strong>6-12:</strong> Mild manic symptoms</p>
            <p><strong>13-20:</strong> Moderate manic symptoms</p>
            <p><strong>21-29:</strong> Severe manic symptoms</p>
            <p><strong>30 or higher:</strong> Very severe manic symptoms</p>
            <p><em>Please note: These are general guidelines. A clinical diagnosis can only be made by a qualified healthcare professional. This tool is for informational purposes and not a substitute for professional medical advice.</em></p>
        </div>

        <a href="index.php" class="start-over-button">Start Over</a>
    </div>
</body>
</html>
